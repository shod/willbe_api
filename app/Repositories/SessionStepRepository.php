<?php

namespace App\Repositories;

use App\Interfaces\SessionStepRepositoryInterface;
use App\Models\SessionStep;
use App\Models\Session;
use App\Models\UserStep;

class SessionStepRepository implements SessionStepRepositoryInterface
{
  public function getSteps(Session $session)
  {
    return SessionStep::where('session_id', $session->id)->get();
  }

  /** Get user steps with statuses */
  public function getStepsByUser(Session $session, $userId)
  {
    $stepsInfo = [];
    $arr_status_value = UserStep::arr_status_value;

    $steps = SessionStep::query()
      ->where('session_id', $session->id)
      ->orderBy('num')
      ->get();

    foreach ($steps as $step) {
      $user_step = $step->userstepinfo()->where('user_id', $userId)->first();

      //$status_bit = 0;
      $value = $arr_status_value[UserStep::STATUS_TODO];
      if ($user_step) {
        //$status_bit = $user_step->status_bit;
        $value = $user_step->getMaxFlag($arr_status_value);
      }

      $stepsInfo[] = [
        'id' => $step->id,
        'name' => $step->name,
        'num' => $step->num,
        'status' => $value,
        //'status_bit' => $status_bit,
      ];
    }

    return $stepsInfo;
  }

  public function getStepsById($stepId)
  {
    abort(404, "Method not implemented");
  }

  public function deleteStep($stepId)
  {
    SessionStep::destroy($stepId);
  }

  public function createStep(array $Details)
  {
    return SessionStep::create($Details);
  }

  public function updateStep($stepId, array $Details)
  {
    $step = SessionStep::find($stepId);

    $step->name = $Details['name'];
    $step->num = $Details['num'];

    $step->save();
    return $step;
  }
}
