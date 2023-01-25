<?php

namespace App\Repositories;

use App\Interfaces\SessionRepositoryInterface;
use App\Models\Session;

class SessionRepository implements SessionRepositoryInterface
{
  public function getSessions($programId)
  {
    return Session::where(['program_id' => $programId])->orderBy('num')->get();
  }
  public function getSessionById($sessionId)
  {
    abort(404, "Method not implemented");
  }
  public function deleteSession($sessionId)
  {
    abort(404, "Method not implemented");
  }
  public function createSession(array $Details)
  {
    abort(404, "Method not implemented");
  }
  public function updateSession($sessionId, array $Details)
  {
    abort(404, "Method not implemented");
  }
}
