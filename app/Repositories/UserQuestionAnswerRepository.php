<?php

namespace App\Repositories;

use App\Interfaces\UserQuestionAnswerRepositoryInterface;
use App\Models\User;
use App\Models\Question;
use App\Models\UserQuestionAnswer;

class UserQuestionAnswerRepository implements UserQuestionAnswerRepositoryInterface
{

  public function getList(User $user, Question $question): array
  {
    $main = UserQuestionAnswer::query()
      ->where(['question_id' => $question->id, 'user_id' => $user->id])
      ->first();

    if ($main) {
      $data = [
        'id' => $question->id, 'name' => $question->name, 'status' => UserQuestionAnswer::QWESTION_STATUSES[$main['point']], 'parts' => []
      ];
    } else {
      $data = [
        'id' => $question->id, 'name' => $question->name, 'status' => UserQuestionAnswer::QWESTION_STATUSES[0], 'parts' => []
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
        $questions = $this->getQuestion($sub->id, $user->id);
        $data_subs[] = ['id' => $sub->id, 'name' => $sub->name, 'questions' => $questions];
      }

      $data_parts[$part['id']] = ['name' => $part->name, 'subs' => $data_subs];
      unset($data_subs);
    }

    $data['parts'] = $data_parts;

    return $data;
  }

  /** Get Questions */
  private function getQuestion(int $sub_id, int $user_id): array
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
      $questions_data[] = ['id' => $question->id, 'name' => $question->name, 'point' => $point];
    }

    return $questions_data;
  }
}
