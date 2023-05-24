<?php

namespace App\Repositories;

use App\Interfaces\UserQuestionAnswerRepositoryInterface;
use App\Models\User;
use App\Models\Question;
use App\Models\UserQuestionAnswer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserQuestionAnswerRepository implements UserQuestionAnswerRepositoryInterface
{
  const ANSWER_WERSIONS = ['0' => 'Never have the symptom', '1' => 'Mild symptom, experience rarely', '2' => 'Moderate symptom, experience occasionally', '3' => 'Severe symptom, experience frequently/daily'];

  public function getList(User $user, Question $question): array
  {
    // $main = UserQuestionAnswer::query()
    //   ->where(['question_id' => $question->id, 'user_id' => $user->id])
    //   ->first();

    $main = UserQuestionAnswer::updateOrCreate(
      ['user_id' => $user->id, 'question_id' => $question->id],
      ['user_id' => $user->id, 'question_id' => $question->id, 'point' => 0]
    );

    if ($main) {
      $created = new Carbon($main->updated_at);
      $data = [
        'id' => $question->id, 'name' => $question->name, 'status' => UserQuestionAnswer::QWESTION_STATUSES[$main['point']], 'parts' => [], 'updated_at' => $created->toDateString()
      ];
    }

    $parts = Question::query()
      ->where('parent_id', $question->id)
      ->get();

    //  Get SubPart    
    $subs = [];
    $questions = [];
    foreach ($parts as $key => $part) {
      $subs = Question::query()
        ->where('parent_id', $part['id'])
        ->get();

      //  Get Subs
      $data_subs = [];
      foreach ($subs as $sub) {
        $specific_answer = $sub->specific_answer;
        $questions = $this->getQuestion($sub->id, $user->id, $specific_answer);
        $question_stats = $this->getQuestionStats($sub, $user->id);
        $data_subs[] = array_merge(['id' => $sub->id, 'name' => $sub->name, 'questions' => $questions], $question_stats);
      }

      $data_parts[] = ['id' => $part->id, 'name' => $part->name, 'subs' => $data_subs];
      unset($data_subs);
    }

    $data['parts'] = $data_parts;
    $data['total_score'] = $this->getTotalScore(1, $user->id);

    return $data;
  }

  /** Get Questions */
  private function getQuestion(int $sub_id, int $user_id, $specific_answer = null): array
  {
    $questions_data = [];
    $questions = Question::query()
      ->where('parent_id', $sub_id)
      ->get();

    foreach ($questions as $question) {
      $user_question_answer = $question->user_question_answer()->where('user_id', $user_id)->first();
      $point = null;

      if ($user_question_answer) {
        $point = $user_question_answer->point;
      }

      $answer = [];
      $answer = $this->getAnswerVarions($question, $specific_answer);

      $questions_data[] = ['id' => $question->id, 'name' => $question->name, 'point' => $point, 'answers' => $answer];
    }

    return $questions_data;
  }

  /**
   * Get answer variants
   */
  private function getAnswerVarions(Question $question, $specific_answer)
  {
    if ($specific_answer != null) {
      $question->specific_answer = $specific_answer;
    }

    if ($question->specific_answer === null) {
      foreach (self::ANSWER_WERSIONS as $key => $value) {
        $data[] = ['name' => $value, 'point' => $key];
      }
    } else {
      $data = json_decode($question->specific_answer);
    }

    return $data;
  }

  /**
   *  Set answer for user 
   */
  public function setAnswer(User $user, Question $question, int $point): array
  {

    $answer = UserQuestionAnswer::updateOrCreate(
      ['user_id' => $user->id, 'question_id' => $question->id],
      ['user_id' => $user->id, 'question_id' => $question->id, 'point' => $point]
    );

    $parent = Question::query()
      ->where('id', $question->parent_id)
      ->first();

    $question_stats = $this->getQuestionStats($parent, $user->id);
    $total_score = $this->getTotalScore(1, $user->id);

    return array_merge(['question_id' => $question->id, 'parent_id' => $question->parent_id, 'total_score' => $total_score], $question_stats);
  }

  /**
   * Get user answer
   */
  public function getQuestionStats(Question $question, int $user_id): array
  {
    $results = DB::table('questions')
      ->leftJoin('user_question_answers as uq', function ($join) use ($user_id) {
        $join->on('questions.id', '=', 'uq.question_id')
          ->where('uq.user_id', '=', $user_id);
      })
      ->where('questions.parent_id', $question->id)
      ->select('questions.id', 'uq.point', 'uq.user_id')
      ->get();

    //$question_all = $results->count();    

    // Добавить расчет оставшихся    
    $question_count = $results->countBy(function ($item, $key) {
      return $item->user_id != null;
    });

    $question_not_ready = isset($question_count['0']) ? $question_count['0'] : 0;
    $question_ready = isset($question_count['1']) ? $question_count['1'] : 0;

    $question_all = $question_ready + $question_not_ready;

    $is_filled = false;
    if ($question_all == $question_ready) {
      $is_filled = true;
    }

    $total_score = $this->getTotalScore($question->id, $user_id);

    $label = sprintf("%d/%d filled", $question_ready, $question_all);

    return ['question_all' => $question_all, 'question_ready' => $question_ready, 'label' => $label, 'is_filled' => $is_filled];
  }

  private function getTotalScore(int $question_id, $user_id)
  {
    $results = DB::table('questions as parq')
      ->leftJoin('questions as subq', 'parq.id', '=', 'subq.parent_id')
      ->leftJoin('questions as chq', 'subq.id', '=', 'chq.parent_id')
      ->leftJoin('user_question_answers as uq', function ($join) use ($user_id) {
        $join->on('chq.id', '=', 'uq.question_id')
          ->where('uq.user_id', '=', $user_id);
      })
      ->where('parq.parent_id', $question_id)
      ->select('uq.id', 'uq.point', 'uq.user_id')
      ->get();

    $total_score = $results->pluck('point')->sum();

    return $total_score;
  }
}
