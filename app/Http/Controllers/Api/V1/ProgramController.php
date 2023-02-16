<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\ProgramRepositoryInterface;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Http\Requests\ProgramStoreRequest;

use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramResourceCollection;
use App\Http\Resources\BaseJsonResource;

use App\Models\Program;

use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        if ($user_id = $request->get('user_id')) {
            $programs = $this->programRepository->getProgramsByUser($user_id);
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
     */
    public function status(Requests\ProgramStatusRequest $request, Program $program)
    {
        $details['user_id'] = $request->get('user_id');
        $details['status'] = [];

        $program = $this->programRepository->setStatusProgram($program, $details);
        return new ProgramResource($program);
    }
}
