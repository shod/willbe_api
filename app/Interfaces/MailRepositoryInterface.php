<?php

namespace App\Interfaces;

use App\Models\User;

interface MailRepositoryInterface
{
  public function resetPassword(User $user, $token): bool;
}
