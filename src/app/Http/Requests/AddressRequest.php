<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'ship_postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'ship_address' => ['required'],
            'ship_building' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'ship_postal_code.required' => '郵便番号を入力してください',
            'ship_postal_code.regex' => '郵便番号は123-4567の形式で入力してください',
            'ship_address.required' => '住所を入力してください'
        ];
    }
}
