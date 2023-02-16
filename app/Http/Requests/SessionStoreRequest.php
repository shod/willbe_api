<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionStoreRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'program_id' => 'required|integer',
            'name' => 'required|string',
            'description' => 'required|string',
            'num' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'description.required' => 'A description is required',
            'program_id.required' => 'A program ID is required',
        ];
    }
}
