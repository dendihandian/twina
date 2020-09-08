<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class TopicRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'result_type' => 'required',
        ];
    }

    public function getValidatorInstance()
    {
        if ($this->request->has('name')) {
            $this->merge([
                'name' => Str::lower($this->request->get('name')),
            ]);
        }

        return parent::getValidatorInstance();
    }
}
