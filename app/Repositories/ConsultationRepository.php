<?php

namespace App\Repositories;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationRepository implements ConsultationRepositoryInterface
{
  public function getConsultations($userUuid = null)
  {

    if ($userUuid == null) {
      return Consultation::all();
    } else {
      $user_id = User::whereUuid($userUuid)->first()->id;
      $consultations = Consultation::query()
        //->select('user_id','')
        ->with('coach')
        ->where('client_id', $user_id)->get();

      return $consultations;
    }
  }

  public function getProgramById($consultationId)
  {
    abort(404, "Method not implemented");
  }
  public function deleteConsultation($consultationId)
  {
    Consultation::destroy($consultationId);
  }
  public function createConsultation(array $Details)
  {
    return Consultation::create($Details);
  }
  public function updateConsultation($consultationId, array $Details)
  {
    $consultation = Consultation::find($consultationId);

    $consultation->coach_id = $Details['coach_id'];
    $consultation->user_id = $Details['user_id'];
    $consultation->description = $Details['description'];
    $consultation->meet_time = $Details['meet_time'];

    $consultation->save();
    return $consultation;
  }

  /**
   * Create consultation after pay
   */
  public function Subscribe(User $user)
  {

    $Details = [
      'client_id' => $user->id,
    ];

    $consultation = Consultation::create($Details);

    if ($consultation) {
      $consultation->status_bit = $consultation->setFlag(Consultation::STATUS_PAYED, true);
      $consultation->save();
    }
    return $consultation;
  }

  public function updateConsultations(int $consultationId, array $details)
  {
    try {
      $res = Consultation::findOrFail($consultationId)
        ->update($details);

      return Consultation::find($consultationId);
    } catch (\Exception $e) {
      return $e;
    }
  }
}
