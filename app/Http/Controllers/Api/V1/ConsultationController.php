<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Resources\ConsultationResource;
use App\Http\Resources\ConsultationResourceCollection;

class ConsultationController extends Controller
{
    private ConsultationRepositoryInterface $programConsultation;

    public function __construct(ConsultationRepositoryInterface $programConsultation)
    {
        $this->programConsultation = $programConsultation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($user_id = $request->get('user_id')) {
            $consultation = $this->programConsultation->getConsultations($request);
        } else {
            $consultation = $this->programConsultation->getConsultations($request);
        }

        return new ConsultationResourceCollection($consultation);
    }
}
