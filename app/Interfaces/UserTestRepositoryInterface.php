<?php

namespace App\Interfaces;

interface UserTestRepositoryInterface
{
  public function getUserTests(int $userId);
  public function getUserTest(int $id);
  // public function getUserTestById($UserTestId);
  //public function deleteUserTest($UserTestId);
  public function createUserTest(array $Details);
  public function updateUserTest($UserTestId, array $Details);
}
