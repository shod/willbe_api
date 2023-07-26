<?php

namespace App\Repositories;

use App\Interfaces\SessionRepositoryInterface;
use App\Models\Session;
use App\Models\SessionStep;
use App\Models\UserStep;
use App\Models\UserSession;
use App\Enums\SessionUserStatus;

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

  /** Change session status for user*/
  public function updateSessionStatus(int $sessionId, int $userId)
  {
    $user_step_status = [];
    $user_session_status = SessionUserStatus::TODO;
    //$session = Session::find($sessionId);
    $session_steps = SessionStep::query()->where('session_id', $sessionId)->get();

    foreach ($session_steps as $step) {
      $user_step = UserStep::query()->where(['session_step_id' => $step->id, 'user_id' => $userId])->first();
      if ($user_step) {
        $user_step_status[$user_step->status_bit] = 1;
      } else { //If step not found? set default        
        $user_step_status[0] = 1;
      }
    }

    if (key_exists(UserStep::STATUS_DONE, $user_step_status)) {
      $user_session_status = SessionUserStatus::DONE->value;
    }

    if (key_exists(UserStep::STATUS_TODO, $user_step_status)) {
      $user_session_status = SessionUserStatus::TODO->value;
    }

    if (key_exists(UserStep::STATUS_PGROGRESS, $user_step_status)) {
      $user_session_status = SessionUserStatus::IN_PGROGRESS->value;
    }

    $user_session = UserSession::query()->where(['session_id' => $sessionId, "user_id" => $userId])
      ->update(['status' => $user_session_status]);

    return $user_session_status;
  }
}
