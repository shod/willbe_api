<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\UserResource;

class ConsultationResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'notice' => $this->notice,
            'meet_time' => $this->meet_time,
            'status' => $this->status,
            'coach' => new UserResource($this->coach),
            'user' => new UserResource($this->client),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
