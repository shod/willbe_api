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

    // 1. Get Part
    $parts = Question::query()
      ->where('parent_id', $question->id)
      ->get();

    // 2. Get SubPart    
    $subs = [];
    $questions = [];
    $question_results = [];
    $is_show_result = 1;

    foreach ($parts as $key => $part) {
      $subs = Question::query()
        ->where('parent_id', $part['id'])
        ->get();

      // 3. Get Subs
      $data_subs = [];
      foreach ($subs as $sub) {
        $specific_answer = $sub->specific_answer;
        $questions = $this->getQuestion($sub->id, $user->id, $specific_answer);
        $question_stats = $this->getQuestionStats($sub, $user->id);
        $data_subs[] = array_merge(['id' => $sub->id, 'name' => $sub->name, 'questions' => $questions], $question_stats);

        // Get Results
        $question_results[] = $this->getSubpartResults($sub, $question_stats['points']);
        $is_show_result = $is_show_result * ($question_stats['is_filled'] == true ? 1 : 0);
      }

      $data_parts[] = ['id' => $part->id, 'name' => $part->name, 'subs' => $data_subs];
      unset($data_subs);
    }

    $is_question_results = 1;
    if (!$is_show_result) {
      $question_results = [];
      $is_question_results = 0;
    }

    $data['parts'] = $data_parts;
    $data['total_score'] = $this->getTotalScore($question->id, $user->id);
    $data['question_results'] = array_values(array_filter($question_results));
    $data['is_question_results'] = $is_question_results;

    $data['status'] = UserQuestionAnswer::QWESTION_STATUS_OPEN;
    $main_status = 0;
    if ($data['total_score'] > 0) {
      $data['status'] = UserQuestionAnswer::QWESTION_STATUS_PROGRESS;
      $main_status = 1;
    }
    if ($data['is_question_results'] === 1) {
      $data['status'] = UserQuestionAnswer::QWESTION_STATUS_CLOSE;
      $main_status = 2;
    }

    $main->point = $main_status;
    $main->save();

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
      $point = 0;

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
    if ($question->specific_answer === null) {
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

    // Добавить расчет оставшихся    
    $question_count = $results->countBy(function ($item, $key) {
      return $item->user_id != null;
    });

    // Recalc points 
    $points = $results->sum(function ($item) {
      return $item->point;
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

    return ['question_all' => $question_all, 'question_ready' => $question_ready, 'label' => $label, 'points' => $points, 'is_filled' => $is_filled];
  }

  /**
   * Get total number of points
   */
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

  /**
   * Get Results for sub
   */
  private function getSubpartResults($subpart, int $points)
  {
    $result = "";
    $point_results = $subpart->question_results()
      ->where('min_points', '<=', $points)
      ->orderBy('min_points', 'desc')
      ->first();

    if ($point_results) {
      $result = $point_results['description'];
    }

    return $result;
  }
}
