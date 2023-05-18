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
    $message_text = "Your verification code:" . $code;
    $message = (new SinchMessage())->to($phone)->channel('sms')->sms($message_text);
    $client = new SinchSender();
    $res = $client->send($message);
    return true;
  }
}
