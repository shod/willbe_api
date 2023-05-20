<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Models\User;

class StripeController extends CashierController
{
    public function webhooks(Request $request)
    {
        $log = Log::channel('stripe');
        $log->info('callback');
        $log->info($request);
    }

    protected function handleCustomerUpdated(array $payload)
    {
        $log = Log::channel('stripe');
        $log->info('callback');
        $log->info(print_r($payload, true));

        $user = User::query()->whereEmail($payload['data']['object']['email']);

        if ($user) {
            $user->updateDefaultPaymentMethodFromStripe();
        }

        return $this->successMethod();
    }
}
