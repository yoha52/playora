<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroundRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:500'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'parking_available' => ['boolean'],
            'camera_allowed' => ['boolean'],
            'waiting_area' => ['boolean'],
            'changing_room' => ['boolean'],
            'security_locker' => ['boolean'],
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
            'parking_available' => $this->boolean('parking_available'),
            'camera_allowed' => $this->boolean('camera_allowed'),
            'waiting_area' => $this->boolean('waiting_area'),
            'changing_room' => $this->boolean('changing_room'),
            'security_locker' => $this->boolean('security_locker'),
            'active' => $this->boolean('active'),
        ]);
    }
}
