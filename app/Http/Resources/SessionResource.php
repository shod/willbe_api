<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Models\Session;

class SessionResource extends BaseJsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'num' => $this->num,
            //'status' => ($this->status === null) ? Session::STATUS_TODO : $this->status,
            'status' => $this->when(property_exists($this, 'status'), function () {
                return $this->status;
            }),
        ];
    }
}
