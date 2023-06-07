<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\Plan;
use Stripe\Token;

interface SubscribeRepositoryInterface
{
  public function InvoicePaymentSucceeded(User $user, array $payload): bool;

  /**
   * Create a new stripe subscription
   */
  public function CreateStripeSubscription(User $user, Plan $plan, string $pay_tokien): bool;

  public function FindStripePaymentMethodByToken(User $user, Token $token);
}
