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
    return new GeneralJsonException('Method is not implemented', 400);
  }
}
