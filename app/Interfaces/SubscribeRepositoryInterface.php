<?php

namespace App\Interfaces;

use App\Models\User;

interface SubscribeRepositoryInterface
{
  public function InvoicePaymentSucceeded(User $user, array $payload): bool;
}
