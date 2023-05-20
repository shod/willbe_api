<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class StripeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:create_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::find(1004);
        //$stripeCustomer = $user->createOrGetStripeCustomer();

        //dd($stripeCustomer);
        return Command::SUCCESS;
    }
}
