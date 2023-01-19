<?php

namespace App\Interfaces;

use App\Models\User;

interface ClientUserRepositoryInterface
{
  public function getList(User $user);
}
