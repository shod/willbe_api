<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Interfaces\MailRepositoryInterface;
use App\Models\User;

use App\Jobs\SendResetPasswordEmail;
use App\Jobs\SendContactEmail;

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
}
