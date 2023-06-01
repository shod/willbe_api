<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
  public function registerByEmail(array $userDetails): User;
  /*
  public function login(array $authDetails);
  public function logout(array $userDetails);
  */
}
