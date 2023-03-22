<?php

namespace App\Repositories;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationRepository implements ConsultationRepositoryInterface
{
  public function getConsultations(Request $request)
  {
    return Consultation::all();
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
}
