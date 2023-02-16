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
    Session::destroy($sessionId);
  }
  public function createSession(array $Details)
  {
    return $session = Session::create($Details);
  }
  public function updateSession($sessionId, array $Details)
  {
    $session = Session::find($sessionId);

    $session->name = $Details['name'];
    $session->description = $Details['description'];

    $session->save();
    return $session;
  }
}
