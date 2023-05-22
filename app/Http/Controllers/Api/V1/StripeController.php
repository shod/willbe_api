<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Models\User;
use App\Models\UserProgram;
use App\Models\Program;
use Stripe\PaymentMethod;
use Laravel\Cashier\Cashier;
use App\Repositories\SubscribeRepository;

/**
 * See CashierController and web /stripe/webhook
 * protected
 */
class StripeController extends CashierController
{
    public function handleInvoicePaymentSucceeded(array $payload)
    {
        $customer_id = $payload['data']['object']['customer'];

        if ($user = $this->getUserByStripeId($customer_id)) {

            $subscribeRepository = new SubscribeRepository();
            $res = $subscribeRepository->InvoicePaymentSucceeded($user, $payload);
        }

        return $this->successMethod();
    }


    public function handleCustomerCreated(array $payload)
    {
        /** Find user by email */
        $user = User::query()->whereEmail($payload['data']['object']['email'])->first();

        if ($user) {
            $stripe_id = $payload['data']['object']['id'];

            $user->stripe_id = $stripe_id;
            $user->save();

            ///$user->updateDefaultPaymentMethodFromStripe();
        }

        return $this->successMethod();
    }

    private function checkUserStripe(array $payload)
    {
        $log = Log::channel('stripe');
        $log->info('callback');
        $log->info(print_r($payload, true));

        if (!$this->getUserByStripeId($payload['data']['object']['id'])) {
            $user = User::query()->whereEmail($payload['data']['object']['email'])->first();
            if ($user) {
                $stripe_id = $payload['data']['object']['id'];

                $user->stripe_id = $stripe_id;
                $user->save();

                $user->updateDefaultPaymentMethodFromStripe();
            }
        }
    }
}
