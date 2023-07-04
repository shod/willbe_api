<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface ConsultationRepositoryInterface
{
  public function getConsultations(Request $request);
}
