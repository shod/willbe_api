<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\Question;

interface UserQuestionAnswerRepositoryInterface
{
  public function getList(User $user, Question $question): array;
  public function setAnswer(User $user, Question $question, int $point): array;
}
