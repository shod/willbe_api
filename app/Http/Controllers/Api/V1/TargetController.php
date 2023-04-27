<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\TargetRepositoryInterface;
use App\Models\User;
use App\Models\Target;
use App\Http\Requests;
use App\Http\Requests\TargetStoreRequest;

use App\Http\Resources;
use App\Http\Resources\TargetResource;
use App\Http\Resources\TargetResourceCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


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

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\TargetStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TargetStoreRequest $request)
    {
        $user_uuid = $request->get('user_uuid');
        $user_id = User::whereUuid($user_uuid)->first()->id;
        $details = [
            'user_id' => $user_id,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status' => $request->get('status'),
        ];

        $target = $this->targetRepository->createTarget($details);
        return new TargetResource($target);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\TargetStoreRequest $request, Target $target)
    {
        $details = [
            'name' => $request->get('name'),
            'num' => $request->get('num'),
        ];

        $target = $this->targetRepository->updateTarget($target->id, $details);
        return new TargetResource($target);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Target  $target
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
        $this->targetRepository->deleteTarget($target->id);
        return new Resources\BaseJsonResource(new Request());
    }
}
