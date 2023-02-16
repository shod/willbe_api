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
  public function setStatusProgram(Program $program, array $Details);
}
