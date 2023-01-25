<?php

namespace App\Interfaces;

interface ProgramRepositoryInterface
{
  public function getPrograms();
  public function getProgramById($programId);
  public function deleteProgram($programId);
  public function createProgram(array $Details);
  public function updateProgram($programId, array $Details);
}