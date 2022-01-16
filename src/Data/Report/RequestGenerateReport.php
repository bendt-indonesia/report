<?php

namespace Bendt\Report\Data\Report;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestGenerateReport extends FormRequest
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
            'query' => 'required',
			'file' => 'nullable',
			'orientation' => 'nullable|in:landscape,portrait',
			'filters' => 'nullable|json',
			'keywords' => 'nullable|json',
            'limit' => 'nullable|numeric|min:0',
            'auto' => 'nullable|in:0,1',
        ];
    }

    /**
     * Failed validation disable redirect
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
