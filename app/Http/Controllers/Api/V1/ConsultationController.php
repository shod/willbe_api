<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Resources\ConsultationResourceCollection;
use App\Models\User;
use App\Exceptions\GeneralJsonException;

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
        $user_uuid = $request->header('uuid');

        $user = User::whereUuid($user_uuid)->first();
        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        $consultation = $this->programConsultation->getConsultations($user_uuid);

        return new ConsultationResourceCollection($consultation);
    }
}
