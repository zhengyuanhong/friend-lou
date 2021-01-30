<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LouRequest extends FormRequest
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
            'amount' => 'required',
            'duration' => 'required',
            'lou_type' => 'required',
            'note' => ''
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => '未输入金额',
            'duration' => '未设置还款期限',
            'lou_type' => '未指定类型',
            'user_id' => '没有用户ID',
        ];
    }
}
