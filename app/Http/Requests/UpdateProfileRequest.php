<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'full_name' => 'required',
            'avatar' => 'file|max:5120|mimes:jpg,png,jpeg',
//            'phone' => ['required', 'regex:/(03|07|08|09|01[2|6|8|9])+([0-9]{8})\b/'],
            'phone_country' => ['required'],
        ];
    }
    
}
