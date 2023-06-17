<?php

namespace App\Repositories;

use App\Interfaces\SubscribeRepositoryInterface;
use App\Models\Subscribe;
use App\Models\User;
use App\Models\Plan;

use Laravel\Cashier\Cashier;
use Stripe\Token;
use Illuminate\Support\Str;

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

  /**
   * Fabrique of subscriptions. Programms | Questions
   */
  public function CreateStripeSubscription(User $user, Plan $plan, $pay_method): bool
  {
    $method = "CreateStripeSubscription" .  Str::studly(str_replace('.', '_', $plan->entity));

    return $this->$method($user, $plan, $pay_method);
  }

  /**
   * Create subscription for Programm
   */
  private function CreateStripeSubscriptionProgram(User $user, Plan $plan, $pay_method): bool
  {
    // Create a new subscription        
    $res = $user->newSubscription($plan->name, $plan->stripe_plan)->create($pay_method->id);

    // Check the subscription
    $subscription = $user->subscription($plan->name);

    if ($subscription) {
      $subscribeRepository = new ProgramRepository();
      $subscribeRepository->Subscribe($user, $plan->entity_id);

      return true;
    } else {
      return false;
    }
  }

  /**
   * Create subscription for Programm
   */
  private function CreateStripeSubscriptionConsultation(User $user, Plan $plan, $pay_method): bool
  {
    // Create a new subscription        
    $res = $user->newSubscription($plan->name, $plan->stripe_plan)->create($pay_method->id);

    // Check the subscription
    $subscription = $user->subscription($plan->name);

    if ($subscription) {
      $subscribeRepository = new ConsultationRepository();
      $subscribeRepository->Subscribe($user);

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
