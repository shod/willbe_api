<?php

namespace App\Interfaces;

interface TargetRepositoryInterface
{
  public function getTargets($userId);
  public function deleteTarget($sessionId);
  public function createTarget(array $Details);
  public function updateTarget($targetId, array $Details);
}
