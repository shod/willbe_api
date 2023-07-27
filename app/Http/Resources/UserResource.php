<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\UserInfoResource;

class UserResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //new UserInfoResource(),
        return [
            'uuid' => $this->uuid,
            //'name' => $this->name,
            'email' => $this->email,
            'user_info' => new UserInfoResource($this->user_info()),            
        ];
    }

    public function with($request)
    {
        return [
            'success' => true
        ];
    }
}
