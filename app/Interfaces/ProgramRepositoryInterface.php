<?php

namespace App\Interfaces;

use App\Models\Program;

interface ProgramRepositoryInterface
{
  public function getPrograms();
  public function getProgramsByUser(int $userId);
  public function getProgramById(int $programId);
  public function deleteProgram($programId);
  public function createProgram(array $Details);
  public function updateProgram($programId, array $Details);

  /**
   * @param Program $program
   * @param int $user_id
   * @param int $status UserProgram::const
   * @param bool $status_value true|false
   */
  public function setStatusProgram(Program $program, int $user_id, int $status, bool $status_value);
}
