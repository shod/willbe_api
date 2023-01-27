<?php

namespace App\Interfaces;

use App\Models\Session;

interface SessionStepRepositoryInterface
{
  public function getSteps(Session $sessionId);
  public function getStepsByUser(Session $stepId, $userId);
  public function getStepsById($stepId);
  public function deleteStep($stepId);
  public function createStep(array $Details);
  public function updateStep($stepId, array $Details);
}
