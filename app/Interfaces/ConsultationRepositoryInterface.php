<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Http\Request;

interface ConsultationRepositoryInterface
{
  public function getConsultations(Request $request);
  public function updateConsultations(int $consultationId, array $details);
}
