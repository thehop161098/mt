<?php

namespace App\Http\Requests\Frontend\User;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class GG2FARequest extends FormRequest
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
            'current_password_secret' => ['required', new MatchOldPassword],
            'secret' => ['required'],
        ];
    }
}
