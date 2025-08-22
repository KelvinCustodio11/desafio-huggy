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
            'address' => 'nullable|array',
            'address.street' => 'nullable|string|max:255',
            'address.city'   => 'nullable|string|max:100',
            'address.state'  => 'nullable|string|max:100',
            'address.neighborhood' => 'nullable|string|max:100',
        ];
    }
}
