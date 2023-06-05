<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\MailRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
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
    private AuthRepositoryInterface $authRepository;
    private MailRepositoryInterface $mailRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        MailRepositoryInterface $mailRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->authRepository = $authRepository;
        $this->mailRepository = $mailRepository;
        $this->userRepository = $userRepository;
    }

    /** Get Stripe_id for user */
    public function stripe_user(Request $request)
    {
        // TODO:Validate email
        $user = User::query()->whereEmail($request->email)->first();

        if (!$user) {
            // Create a new user            
            $user = $this->createBaseUser($request->email);
            $user->createAsStripeCustomer();
        }

        // If none stripe_id yet
        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        return response()->json(['data' => ["stripe_id" => $user->stripe_id], "success" => true], 200);
    }

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

        if (!$user) {
            // Create a new user            
            $user = $this->createBaseUser($payload['data']['object']['email']);
        }

        $stripe_id = $payload['data']['object']['id'];

        $user->stripe_id = $stripe_id;
        $user->save();

        $this->mailRepository->createUserStripe($user);

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
    /** 
     * Create a new user            
     */
    private function createBaseUser($email): User
    {
        // Create a new user            
        $userDetails['name'] = 'Client-' . time();
        $userDetails['email'] = $email;
        $userDetails['role'] = 'client';
        $userDetails['password'] = '-123456-';

        $userDetails['gender'] = 'female';
        $userDetails['phone'] = '10101010101';

        $user = $this->authRepository->registerByEmail($userDetails);
        $user->save();

        return $user;
    }
}
