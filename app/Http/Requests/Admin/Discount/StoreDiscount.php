<?php

namespace App\Http\Requests\Admin\Discount;

use Carbon\Carbon;
use Core\Repositories\Eloquents\DiscountRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreDiscount extends FormRequest
{
    private $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.discount.create');
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
            'image' => ['required'],
            'date_show_image' => ['required', 'string'],
            'deposit' => ['required', 'numeric', 'gt:0'],
            'discount' => ['required', 'numeric', 'gt:0', 'lte:100'],
            'from_date' => ['required', 'string'],
            'to_date' => ['required', 'string'],

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

    public function withValidator($validator)
    {
        if ($this->date_show_image && $this->from_date) {
            $date_show_image = Carbon::createFromFormat('Y-m-d H:i:s', $this->date_show_image)->format('Y-m-d');
            $from_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->from_date)->format('Y-m-d');
            if ($date_show_image > $from_date) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('date_show_image', 'Date show image must be lower or equal from date');
                });
            }
        }
        if ($this->from_date && $this->to_date) {
            $from_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->from_date)->format('Y-m-d');
            $to_date = Carbon::createFromFormat('Y-m-d H:i:s', $this->to_date)->format('Y-m-d');
            if ($from_date > $to_date) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('to_date', 'To date must be greater or equal from date');
                });
            } else {
                $discountExists = $this->discountRepository->validDiscount($from_date, $to_date);
                if (!empty($discountExists)) {
                    $validator->after(function ($validator) use ($discountExists) {
                        $validator->errors()->add('from_date',
                            "Duplicate date from $discountExists->from_date to $discountExists->to_date");
                        $validator->errors()->add('to_date',
                            "Duplicate date from $discountExists->from_date to $discountExists->to_date");
                    });
                }
            }
        }
    }
}
