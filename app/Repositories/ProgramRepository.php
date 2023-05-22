<?php

namespace App\Repositories;

use App\Interfaces\ProgramRepositoryInterface;
use App\Models\Program;
use App\Models\UserProgram;
use App\Models\User;
use App\Models\UserSession;
use App\Models\Session;
use App\Enums\SessionUserStatus;

class ProgramRepository implements ProgramRepositoryInterface
{
  public function getPrograms()
  {
    return Program::all();
  }

  public function getProgramsByUser($userUuid)
  {
    $arr_status_value = UserProgram::ARR_STATUS_VALUE;

    $programs = Program::all();

    $user_id = User::whereUuid($userUuid)->first()->id;

    foreach ($programs as $program) {
      $user_program = $program->userProgramInfo()->where('user_id', $user_id)->first();

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
          'session' => []
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

  /**
   * $Details['user_id' => 1, 'status' => [UserProgram::STATUS_ACTIVE]]
   */
  public function setStatusProgram(Program $program, int $user_id, int $status, bool $status_value): array
  {
    $arr_status_value = UserProgram::ARR_STATUS_VALUE;
    /*
    ->firstOrCreate(
        ['program_id' => $program->id],
        ['user_id' => $Details['user_id']],
        ['status_bit' => 0]
      );
    */
    $user_program = $program->userProgramInfo()->where('user_id', $user_id)->first();

    if ($user_program === null) {
      $user_program = new UserProgram();
      $user_program->program_id = $program->id;
      $user_program->user_id = $user_id;
      $user_program->status_bit = 0;
      $user_program->save();
    }

    $user_program->status_bit = $user_program->setFlag($status, $status_value);
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

  /**
   * Подписка программы после оплаты
   */
  public function Subscribe(User $user, int $plan_id)
  {
    //TODO: Find program by $plan_id
    $program_id = 1;
    $program = Program::find(1);

    $data = [
      'program_id'    => $program->id,
      'user_id'       => $user->id,
      'status_bit'    => 3
    ];
    $res = UserProgram::firstOrCreate($data);
    $this->startProgramm($user, $program);
  }

  private function startProgramm(User $user, Program $program)
  {
    $session = $program->session()->where('num', 1)->first();

    $userSession = UserSession::firstOrNew(
      [
        "user_id"     => $user->id,
        "session_id"  => $session->id,
      ]
    );

    if (!$userSession->exists) {
      $userSession->status = SessionUserStatus::IN_PGROGRESS;
      $userSession->save();
    }
  }
}
