<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseJsonResourceCollection;

class UserResourceCollection extends BaseJsonResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return [
        //     //'id' => $this->id, 
        //     'uuid' => $this->uuid,
        //     'email' => $this->email,
        //     'user_info' => new UserInfoResource($this->user_info()),
        // ];
    }
}
