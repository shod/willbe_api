<?php

namespace App\Http\Resources;

use App\Http\Resources\BaseJsonResource;

class UserTestResource extends BaseJsonResource
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
            'program_id' => $this->program_id,
            'status' => $this->status,
            'attach_files' => ($this->attach_files === null) ? "[]" : $this->attach_files,
            'name' => $this->test['name'],
            'labname' => $this->labname,
            //'testid' => $this->test_id,
        ];
    }
}
