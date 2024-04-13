<?php

namespace App\Http\Requests\Admin\Parcel;

use Illuminate\Foundation\Http\FormRequest;

class ParcelUpdateRequest extends FormRequest
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
            'merchant' => 'required',
            'customer_name' => 'required',
            'customer_invoice_no' => 'required',
            'customer_phone_number' => 'required|numeric|digits:11',
            'customer_address' => 'required',
            'parcel_type' => 'required',
            'weight' => 'required',
            'price' => 'required|numeric',
            'selling_price' => 'numeric',
            'shop_phone_number'=> 'numeric|digits:11',
        ];
    }
}
