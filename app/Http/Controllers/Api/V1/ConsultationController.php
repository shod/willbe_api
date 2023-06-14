<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\ConsultationResource;
use App\Http\Resources\ConsultationResourceCollection;
use App\Http\Requests\UserUuidRequest;

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
    public function index(UserUuidRequest $request)
    {
        if ($user_uuid = $request->get('user_uuid')) {
            if (Str::isUuid($user_uuid)) {
                $consultation = $this->programConsultation->getConsultations($user_uuid);
            }
        } else {
            $consultation = $this->programConsultation->getConsultations();
        }

        return new ConsultationResourceCollection($consultation);
    }
}
