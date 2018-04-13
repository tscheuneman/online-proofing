<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'job_id' => 'required|string|unique:orders,job_id',
            'category' => 'required|exists:categories,id',
            'adminValues' => 'required|json',
            'userValues' => 'required|json',
            'projectValues' => 'required|json'
        ];
    }
}
