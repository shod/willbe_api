<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\Api\V1\StripeController;
use Stripe\Stripe;
use Stripe\Token;

class StripeCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'stripe:token';

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
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $token = Token::create([
      'card' => [
        'number' => '5555555555554444',
        'exp_month' => 6,
        'exp_year' => 2024,
        'cvc' => '314',
      ],
    ]);
    dd($token->id);
    return Command::SUCCESS;
  }
}
