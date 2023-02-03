<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class ProgramStoreRequest extends BaseFormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'cost' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'description.required' => 'A description is required',
            'description.cost' => 'A cost is required',
        ];
    }
}
