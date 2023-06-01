<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\CreateUserStripe;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendCreateUserStripeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $registration_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $registration_url)
    {
        $this->user = $user;
        $this->registration_url = $registration_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new CreateUserStripe($this->registration_url));
    }
}
