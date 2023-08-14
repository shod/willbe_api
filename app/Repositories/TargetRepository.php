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

  public function createTarget(array $Details)
  {
    return Target::create($Details);
  }

  public function updateTarget($targetId, array $Details)
  {
    try {
      Target::findOrFail($targetId)
        ->update($Details);
      return Target::find($targetId);
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function deleteTarget($targetId)
  {
    Target::destroy($targetId);
  }
}
