<?php

namespace App\Repositories;

use App\Interfaces\TargetRepositoryInterface;
use App\Models\Target;

class TargetRepository implements TargetRepositoryInterface
{
  public function getTargets($userId)
  {
    return Target::query()->whereUser_id($userId)->orderBy('created_at', 'ASC')->get();
  }
}
