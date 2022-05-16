<?php

namespace App\Http\Requests\Admin\Coin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCoin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.coin.edit', $this->coin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', Rule::unique('coins', 'name')->ignore($this->coin->getKey(), $this->coin->getKeyName()), 'string'],
            'image' => ['sometimes', 'string', 'max:255'],
            'alias' => ['sometimes', 'string', 'max:20'],
            'range' => ['sometimes', 'integer', 'min:0', 'not_in:0'],
            'min' => ['sometimes', 'numeric', 'lt:max', 'min:0', 'not_in:0'],
            'max' => ['sometimes', 'numeric', 'min:0', 'not_in:0']
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
