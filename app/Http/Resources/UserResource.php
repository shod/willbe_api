<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserInfoResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
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
