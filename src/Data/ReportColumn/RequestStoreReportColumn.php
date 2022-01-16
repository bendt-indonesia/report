<?php

namespace Bendt\Report\Data\ReportColumn;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestStoreReportColumn extends FormRequest
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
            'report_id' => 'required|exists:report,id',
			'table_name' => 'required|max:250',
			'column_name' => 'required|max:250',
			'sort_no' => 'required|numeric|min:0|max:8',
			'label' => 'max:250',
			'align' => 'max:250',
			'formatter' => 'nullable|in_array:date,time,dateTime,number',
			'is_checked' => 'required|in:0,1',
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
