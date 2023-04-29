<?php

namespace App\Repositories;

use App\Interfaces\UserTestRepositoryInterface;
use App\Models\UserTest;

class UserTestRepository implements UserTestRepositoryInterface
{
  public function getUserTests(int $userId)
  {
    $tests = UserTest::query()
      ->with('test')
      //->with('program')
      ->where('user_id', $userId)->get();
    return $tests;
  }

  public function getUserTest(int $id)
  {
    $test = UserTest::query()
      ->with('test')
      ->where('user_tests.id', $id)->first();
    return $test;
  }

  public function createUserTest(array $Details)
  {
    return UserTest::create($Details);
  }

  public function updateUserTest($testId, array $Details)
  {
    try {
      $res = UserTest::findOrFail($testId)
        ->update($Details);
      return $res;
    } catch (\Exception $e) {
      return $e;
    }
  }
}
