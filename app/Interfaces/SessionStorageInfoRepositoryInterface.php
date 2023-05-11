<?php

namespace App\Interfaces;

use App\Models\Session;

interface SessionStorageInfoRepositoryInterface
{
  public function getInfo(Session $session, array $options = array());

  // public function deleteUser($userId);
  // public function createUser(array $userDetails);
  // public function updateUser($userId, array $newDetails);
}
