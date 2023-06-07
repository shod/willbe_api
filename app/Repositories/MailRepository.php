<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Interfaces\MailRepositoryInterface;
use App\Models\User;

use App\Jobs\SendResetPasswordEmail;
use App\Jobs\SendContactEmail;
use App\Jobs\SendCreateUserStripeEmail;

class MailRepository implements MailRepositoryInterface
{
  public function resetPassword(User $user, $token): bool
  {
    dispatch(new SendResetPasswordEmail($user, $token));
    return true;
  }

  public function contactSend(Request $request)
  {
    $data = [
      "subject" => env('MAIL_FROM_NAME') . " Contact Form Message",
      "email" => $request->email,
      "name" => $request->name,
      "description" => $request->description,
    ];

    $email_to = env("ADMIN_EMAIL");

    dispatch(new SendContactEmail($email_to, $data));
    return true;
  }

  public function createUserStripe(User $user): bool
  {
    $token = $user->getRememberToken();
    if (!$token) {
      $token = md5(time());
      $user->remember_token = $token;
      $user->save();
    }

    $registration_url = "https://mywillbe.com/register?token=" . $token;
    dispatch(new SendCreateUserStripeEmail($user, $registration_url));
    return true;
  }
}
