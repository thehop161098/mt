<?php

namespace App\Http\Requests\Admin\Coin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCoin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.coin.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('coins', 'name'), 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'alias' => ['required', 'string', 'max:20'],
            'range' => ['required', 'integer', 'min:0', 'not_in:0'],
            'min' => ['required', 'numeric', 'lt:max', 'min:0', 'not_in:0'],
            'max' => ['required', 'numeric', 'min:0', 'not_in:0'],
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
