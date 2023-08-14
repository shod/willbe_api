<?php

namespace App\Repositories;

use App\Interfaces\UserTestRepositoryInterface;
use App\Models\UserTest;
use App\Models\File;

class UserTestRepository implements UserTestRepositoryInterface
{
  public function getUserTests(int $userId)
  {
    $tests = UserTest::query()
      ->with('test')
      ->where('user_id', $userId)->get();

    foreach ($tests as $test) {
      $test->attach_files = "";
      $files = File::query()->where(['type' => File::FILE_TEST, 'object_id' => $test->id])->get();

      if ($files) {
        $attach_files = [];
        foreach ($files as $file) {
          $attach_files[] = $file->getInfo();
        }
        $test->attach_files = json_encode($attach_files);
        unset($attach_files);
      }
    }
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

  public function deleteUserTest($userTestId)
  {
    UserTest::destroy($userTestId);
  }
}
