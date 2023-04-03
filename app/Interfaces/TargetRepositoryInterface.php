<?php

namespace App\Interfaces;

interface TargetRepositoryInterface
{
  public function getTargets($userId);
  // public function getSessionById($sessionId);
  // public function deleteSession($sessionId);
  // public function createSession(array $Details);
  // public function updateSession($sessionId, array $Details);
}
