<?php

namespace App\Interfaces;

interface UserTestRepositoryInterface
{
  public function getUserTests(int $userId);
  public function getUserTest(int $id);
  public function deleteUserTest(int $userTestId);
  public function createUserTest(array $Details);
  public function updateUserTest($UserTestId, array $Details);
}
