<?php

namespace App\Repositories;

use App\Interfaces\ProgramRepositoryInterface;
use App\Models\Program;

class ProgramRepository implements ProgramRepositoryInterface
{
  public function getPrograms()
  {
    return Program::all();
  }
  public function getProgramById($programId)
  {
    abort(404, "Method not implemented");
  }
  public function deleteProgram($programId)
  {
    abort(404, "Method not implemented");
  }
  public function createProgram(array $Details)
  {
    abort(404, "Method not implemented");
  }
  public function updateProgram($programId, array $Details)
  {
    abort(404, "Method not implemented");
  }
}
