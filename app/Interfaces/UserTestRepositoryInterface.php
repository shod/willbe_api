<?php

namespace App\Interfaces;

interface UserTestRepositoryInterface
{
  public function getUserTests(int $userId);
  // public function getSessionById($sessionId);
  // public function deleteSession($sessionId);
  // public function createSession(array $Details);
  // public function updateSession($sessionId, array $Details);
}
