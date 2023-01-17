<?php

namespace App\Repositories;

use App\Interfaces\SmsRepositoryInterface;
use App\Models\User;

use Boyo\Sinch\SinchMessage;
use Boyo\Sinch\SinchSender;

class SmsRepository implements SmsRepositoryInterface
{
  static public function send_code(User $user, int $code): bool
  {
    $phone = $user->user_info()->phone;

    $message = (new SinchMessage())->to($phone)->channel('sms')->sms($code);
    $client = new SinchSender();
    $res = $client->send($message);
    return true;
  }
}
