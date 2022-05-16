<?php

namespace App\Http\Requests\Admin\AutoBot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreAutoBot extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.auto-bot.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'commission_f1' => ['nullable', 'numeric'],
            'commission_7' => ['required', 'numeric'],
            'commission_21' => ['required', 'numeric'],
            'commission_30' => ['required', 'numeric'],
            'commission_90' => ['required', 'numeric'],
            'risk' => ['nullable', 'numeric'],

        ];
    }

    /**
    * Modify input data
    *
    * @return array
    */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
