<?php

namespace App\Repositories;

use App\Interfaces\SubscribeRepositoryInterface;
use App\Models\Subscribe;
use App\Models\User;
use App\Models\Plan;

use Laravel\Cashier\Cashier;
use Stripe\Token;

class SubscribeRepository implements SubscribeRepositoryInterface
{
  public function InvoicePaymentSucceeded(User $user, array $payload): bool
  {
    $product_id = $payload['data']['object']['lines']['data'][0]['plan']['product'];
    //TODO: Find plan_id by product_id
    $plan_id = 1;
    $subscribeRepository = new ProgramRepository();
    $subscribeRepository->Subscribe($user, $plan_id);
    return true;
  }


  public function CreateStripeSubscription(User $user, Plan $plan, $pay_method): bool
  {
    // Create a new subscription        
    $res = $user->newSubscription($plan->name, $plan->stripe_plan)->create($pay_method->id);

    // Check the subscription
    $subscription = $user->subscription($plan->name);

    if ($subscription) {
      $subscribeRepository = new ProgramRepository();
      $subscribeRepository->Subscribe($user, $plan->id);

      return true;
    } else {
      return false;
    }
  }

  public function FindStripePaymentMethodByToken(User $user, Token $token)
  {
    $paymentMethods = $user->paymentMethods();

    foreach ($paymentMethods as $paymentMethod) {
      if ($paymentMethod->card->fingerprint == $token->card->fingerprint) {
        return $paymentMethod;
      }
    }
    return null;
  }
}
