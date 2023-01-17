<?php

namespace App\Interfaces;

use App\Models\User;

interface SmsRepositoryInterface
{
  static public function send_code(User $user, int $code): bool;
}
