<?php

namespace App\Interfaces;

interface SessionRepositoryInterface
{
  public function getSessions($programId);
  public function getSessionById($sessionId);
  public function deleteSession($sessionId);
  public function createSession(array $Details);
  public function updateSession($sessionId, array $Details);
  public function updateSessionStatus(int $sessionId, int $userId);
}
