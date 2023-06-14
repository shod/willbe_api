<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Interfaces\ProgramRepositoryInterface;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Http\Requests\ProgramStoreRequest;

use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramResourceCollection;
use App\Http\Resources\BaseJsonResource;
use App\Http\Requests\UserUuidRequest;

use App\Models\Program;
use App\Models\UserProgram;

use App\Exceptions\GeneralJsonException;

class ProgramController extends Controller
{
    private ProgramRepositoryInterface $programRepository;

    public function __construct(ProgramRepositoryInterface $programRepository)
    {
        $this->programRepository = $programRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserUuidRequest $request)
    {
        $programs = [];
        if ($user_uuid = $request->get('user_uuid')) {
            if (Str::isUuid($user_uuid)) {
                $programs = $this->programRepository->getProgramsByUser($user_uuid);
            }
        } else {
            $programs = $this->programRepository->getPrograms();
        }

        return new ProgramResourceCollection($programs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramStoreRequest $request)
    {
        $details = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'cost' => $request->get('cost'),
        ];
        $program = $this->programRepository->createProgram($details);
        return new ProgramResource($program);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        return new ProgramResource($program);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        $details['name'] = $request->get('name');
        $details['description'] = $request->get('description');
        $details['cost'] = $request->get('cost');

        $program = $this->programRepository->updateProgram($program->id, $details);
        return new ProgramResource($program);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $this->programRepository->deleteProgram($program->id);
        return new BaseJsonResource(new Request());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     * 
     * 'status' => ['active|puschased|not_active|paused' => 'true|false']
     */
    public function status(Requests\ProgramStatusRequest $request, Program $program)
    {
        $arr_const = UserProgram::ARR_STATUS_VALUE;

        $details['user_id'] = $request->get('user_id');
        [$status_name, $status_value] = Arr::divide($request->get('status'));

        $status = array_search($status_name[0], $arr_const);
        if (false === $status) {
            throw new GeneralJsonException('Not valid status', 404);
        }

        $program = $this->programRepository->setStatusProgram($program, $request->get('user_id'), $status, $status_value[0]);
        return new ProgramResource($program);
    }
}
