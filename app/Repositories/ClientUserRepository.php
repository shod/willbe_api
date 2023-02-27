<?php

namespace App\Repositories;

use App\Interfaces\ClientUserRepositoryInterface;
use App\Models\ClientUser;
use App\Models\User;

use App\Exceptions\GeneralJsonException;

class ClientUserRepository implements ClientUserRepositoryInterface
{
  public function getList(User $user)
  {
    return $user_list = $user->clients->all();
  }
}
