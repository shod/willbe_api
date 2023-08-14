<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Http\Request;

interface ConsultationRepositoryInterface
{
  public function getConsultations(string $userUuid);
  public function getConsultationsByCoach(string $coachId, ?string $userUuid);
  public function updateConsultations(int $consultationId, array $details);
}
