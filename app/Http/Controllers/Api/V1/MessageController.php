<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\MailRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private MailRepositoryInterface $mailRepository;

    public function __construct(MailRepositoryInterface $mailRepository)
    {
        $this->mailRepository = $mailRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form_send(Request $request)
    {
        $type = $request->header('X-Form-Type');
        $method = $type . "Send";
        $result = $this->mailRepository->$method($request);
        return response()->json([
            'success' => true
        ], 200);
    }
}
