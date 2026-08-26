<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('app.customer_name'),
            'phone' => __('app.phone'),
        ];
    }
}
