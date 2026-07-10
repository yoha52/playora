<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourtRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'ground_id' => ['required', 'exists:grounds,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'opening_time' => ['required', 'date_format:H:i'],
            'closing_time' => ['required', 'date_format:H:i', 'after:opening_time'],
            'rate_per_hour' => ['required', 'numeric', 'min:0'],
            'active' => ['boolean'],
            'image' => ['nullable', 'string'],
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = ['required', 'string'];
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'active' => $this->boolean('active'),
        ]);
    }
}
