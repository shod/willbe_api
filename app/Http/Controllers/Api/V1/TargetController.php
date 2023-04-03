<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\TargetRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\TargetResource;
use App\Http\Resources\TargetResourceCollection;

class TargetController extends Controller
{
    private TargetRepositoryInterface $targetRepository;

    public function __construct(TargetRepositoryInterface $targetRepository)
    {
        $this->targetRepository = $targetRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_uuid = $request->get('user_uuid');

        if ($user_uuid = $request->get('user_uuid')) {
            if (Str::isUuid($user_uuid)) {
                $user_id = User::whereUuid($user_uuid)->first()->id;
                $targets = $this->targetRepository->getTargets($user_id);
            }
        }

        return new TargetResourceCollection($targets);
    }
}
