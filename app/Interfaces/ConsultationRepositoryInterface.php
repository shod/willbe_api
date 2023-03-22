<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface ConsultationRepositoryInterface
{
  public function getConsultations(Request $request);
  //public function getClientList(User $user);
  //public function getCoachList(User $user);
  //public function getCreate(User $use);

  // public function deleteConsultation($consultationId);
  // public function createConsultation(array $Details);
  // public function updateConsultation($consultationId, array $Details);

  /**
   * @param Program $program
   * @param int $user_id
   * @param int $status UserProgram::const
   * @param bool $status_value true|false
   */
  //public function setStatusProgram(Program $program, int $user_id, int $status, bool $status_value);
}
