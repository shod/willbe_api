<?php

namespace App\Repositories;

use App\Interfaces\UserTestRepositoryInterface;
use App\Models\UserTest;

class UserTestRepository implements UserTestRepositoryInterface
{
  public function getUserTests(int $userId)
  {
    $consultations = UserTest::query()
      ->with('test')
      ->with('program')
      ->where('user_id', $userId)->get();
    return $consultations;
  }
}
