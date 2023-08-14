<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Resources\ConsultationResource;
use App\Http\Resources\ConsultationResourceCollection;
use App\Models\User;
use App\Models\Consultation;
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
        $user_uuid = $request->header('X-UUID');
        $is_coach = $request->header('X-ISCOACH');

        if ($user_uuid) {
            $user = User::whereUuid($user_uuid)->first();
            //throw new GeneralJsonException('User is not found.', 409);
        }

        if ($is_coach) {
            $coach = $request->user();
            $consultation = $this->programConsultation->getConsultationsByCoach($coach->id, $user_uuid);
        } else {
            $consultation = $this->programConsultation->getConsultations($user_uuid);
        }
        return new ConsultationResourceCollection($consultation);
    }

    public function update(Request $request, int $consultation_id)
    {
        if (!$consultation_id) {
            throw new GeneralJsonException('Consultation is not found.', 409);
        }

        $details = [
            'notice' => $request->get('notice'),
            'description' => $request->get('description'),
            'status' => $request->get('status'),
        ];

        // Delete null properties
        $details = array_filter($details, function ($value) {
            return $value !== null;
        });

        if ($details['notice'] && $details['notice'] === '-delete-') {
            $details['notice'] = "";
        }

        $consultation = $this->programConsultation->updateConsultations($consultation_id, $details);

        return new ConsultationResource($consultation);
    }
}
