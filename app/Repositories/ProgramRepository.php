<?php

namespace App\Repositories;

use App\Interfaces\ProgramRepositoryInterface;
use App\Models\Program;
use App\Models\UserProgram;

class ProgramRepository implements ProgramRepositoryInterface
{
  public function getPrograms()
  {
    return Program::all();
  }

  public function getProgramsByUser(int $userId)
  {
    $arr_status_value = UserProgram::ARR_STATUS_VALUE;

    $programs = Program::all();

    foreach ($programs as $program) {
      $user_program = $program->userProgramInfo()->where('user_id', $userId)->first();

      $status_bit = 0;
      $value = $arr_status_value[UserProgram::STATUS_NOTACTIVE];
      if ($user_program) {
        $status_bit = $user_program->status_bit;
        $value = $user_program->getMaxFlag($arr_status_value);
      }

      $programInfo[] = array_merge(
        $program->toArray(),
        [
          'status' => $value,
          'status_bit' => $status_bit,
        ]
      );
    }
    return $programInfo;
  }

  public function getProgramById($programId)
  {
    abort(404, "Method not implemented");
  }
  public function deleteProgram($programId)
  {
    Program::destroy($programId);
  }
  public function createProgram(array $Details)
  {
    return $programs = Program::create($Details);
  }
  public function updateProgram($programId, array $Details)
  {
    $program = Program::find($programId);

    $program->name = $Details['name'];
    $program->description = $Details['description'];
    $program->cost = $Details['cost'];

    $program->save();
    return $program;
  }

  public function setStatusProgram(Program $program, array $Details)
  {
    $arr_status_value = UserProgram::ARR_STATUS_VALUE;
    /*
    ->firstOrCreate(
        ['program_id' => $program->id],
        ['user_id' => $Details['user_id']],
        ['status_bit' => 0]
      );
    */
    $user_program = $program->userProgramInfo()->where('user_id', $Details['user_id'])->first();

    if ($user_program === null) {
      $user_program = new UserProgram();
      $user_program->program_id = $program->id;
      $user_program->user_id = $Details['user_id'];
      $user_program->status_bit = 0;
      $user_program->save();
    }

    $user_program->status_bit = $user_program->setFlag(UserProgram::STATUS_ACTIVE, true);
    $user_program->status_bit = $user_program->setFlag(UserProgram::STATUS_PURCHASED, true);
    $user_program->save();

    $programInfo[] = array_merge(
      $program->toArray(),
      [
        'status' => $user_program->getMaxFlag($arr_status_value),
        'status_bit' => $user_program->status_bit
      ]
    );

    return $programInfo;
  }
}
