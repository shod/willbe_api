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
    return User::whereId($userId)->update($newDetails);
  }

  public function getFulfilledUsers()
  {
    return User::where('is_fulfilled', true);
  }
}
