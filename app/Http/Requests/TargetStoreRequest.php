<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TargetStoreRequest extends BaseFormRequest
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
            'uuid'      => ['string', 'required'],
            'name'      => ['string', 'required'],
            'description'   => ['string', 'required'],
            'status'    => ['string', 'required', Rule::in(['done', 'todo', 'inprogress'])],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'description.required' => 'A description is required',
            'name.status' => 'A status is required',
        ];
    }
}
