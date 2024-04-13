<?php

namespace App\Http\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class OthersAccountRequest extends FormRequest
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
            'bkash_ac_type' => 'required_with:bkash_number',
            'rocket_ac_type' => 'required_with:rocket_number',
            'nogod_ac_type' => 'required_with:nogod_number',
        ];
    }
}
