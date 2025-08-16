<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:clients,email,' . $this->route('client'),
            'phone' => 'nullable|string|max:20',
            'age'   => 'nullable|integer|min:0|max:120',
            'photo' => 'nullable|url',
        ];
    }
}
