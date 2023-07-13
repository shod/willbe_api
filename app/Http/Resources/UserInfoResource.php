<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
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
            //'id'        => $this->id,
            'full_name' => $this->full_name,
            'gender'    => $this->gender,
            'birth_date' => $this->birth_date,
            'slug'      => $this->slug,
            'phone'     => $this->phone,
            'is_phone_verified'     => $this->is_phone_verified,
            'email'     => $this->email,
            'avatar'    => $this->avatar,
            'coach'     => $this->coach,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true
        ];
    }
}
