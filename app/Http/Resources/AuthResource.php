<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $access_token = $this->createToken('role:' . $this->role, ['*'])->plainTextToken;
        return [
            'user' => new UserResource($this),
            'access_token' => $access_token,
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        /**
         * Not all prerequisites were met.
         */
        $response->setStatusCode(401, 'Precondition Required');
    }

    public function with($request)
    {
        return [
            'success' => true
        ];
    }
}
