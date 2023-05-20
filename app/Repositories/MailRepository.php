<?php

namespace App\Repositories;

use App\Interfaces\MailRepositoryInterface;
use App\Models\User;

use App\Jobs\SendResetPasswordEmail;

class MailRepository implements MailRepositoryInterface
{
  public function resetPassword(User $user, $token): bool
  {
    //TODO: Need to send email notification via Jobs
    dispatch(new SendResetPasswordEmail($user, $token));
    return true;
  }
}
