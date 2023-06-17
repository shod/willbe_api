<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\MailRepositoryInterface;
use App\Interfaces\SubscribeRepositoryInterface;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use App\Models\User;
use App\Models\Plan;
use App\Models\UserProgram;
use App\Models\Program;
use Laravel\Cashier\Cashier;
use App\Repositories\SubscribeRepository;
use App\Exceptions\GeneralJsonException;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\PlanResource;
//use Laravel\Cashier\PaymentMethod;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\PaymentMethod;
use Stripe\StripeClient;

/**
 * See CashierController and web /stripe/webhook
 * protected
 */
class StripeController extends CashierController
{
    private AuthRepositoryInterface $authRepository;
    private MailRepositoryInterface $mailRepository;
    private SubscribeRepositoryInterface $subscribeRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        MailRepositoryInterface $mailRepository,
        SubscribeRepositoryInterface $subscribeRepository
    ) {
        $this->authRepository = $authRepository;
        $this->mailRepository = $mailRepository;
        $this->subscribeRepository = $subscribeRepository;
    }

    /**
     * Subscription create
     */
    public function subcription_create(Request $request)
    {
        // TODO:Validate email
        $user = User::query()->whereEmail($request->email)->first();
        $plan = Plan::query()->whereSlug($request->plan_slug)->first();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = Token::retrieve($request->pay_token);

        $paymentMethod = $this->subscribeRepository->FindStripePaymentMethodByToken($user, $token);

        if (!$paymentMethod) {
            /** Check if pay_token used */
            try {
                $stripe_client = new StripeClient(env('STRIPE_SECRET'));
                $stripe_client->customers->createSource(
                    $user->stripe_id,
                    ['source' => $token->id]
                );

                $paymentMethod = $user->paymentMethods()->last();
                $user->updateDefaultPaymentMethod($paymentMethod);
                $user->updateDefaultPaymentMethodFromStripe();
            } catch (\Throwable $th) {
                throw new GeneralJsonException($th->getMessage(), 404);
            }
        }

        try {
            $res = $this->subscribeRepository->CreateStripeSubscription($user, $plan, $paymentMethod);
        } catch (\Throwable $th) {
            throw new GeneralJsonException($th->getMessage(), 404);
        }

        //Check token exists for edit user info
        $is_token_exists = $user->getRememberToken() ? true : false;
        return new BaseJsonResource(['is_redirect' => $is_token_exists]);
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

    /**
     * Retrun info about plan
     */
    public function planinfo(string $slug)
    {
        $plan = Plan::query()->where('slug', $slug)->first();

        if (!$plan) {
            throw new GeneralJsonException("Plan not found", 404);
        }

        return new PlanResource($plan);
    }
}
