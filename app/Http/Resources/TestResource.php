<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
