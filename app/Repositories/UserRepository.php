<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
  public function getAllUser()
  {
    return User::all();
  }

  public function getUserById($userId)
  {
    return User::findOrFail($userId);
  }

  public function deleteUser($userId)
  {
    User::destroy($userId);
  }

  public function createUser(array $UserDetails)
  {

    return User::create($UserDetails);
  }

  public function updateUser($userId, array $newDetails)
  {
    try {
      $res = User::findOrFail($userId)
        ->update($newDetails);
      return $res;
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function getFulfilledUsers()
  {
    return User::where('is_fulfilled', true);
  }
}
