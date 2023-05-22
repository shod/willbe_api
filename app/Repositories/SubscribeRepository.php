<?php

namespace App\Repositories;

use App\Interfaces\SubscribeRepositoryInterface;
use App\Models\Subscribe;
use App\Models\User;
use App\Models\Plan;

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
}
