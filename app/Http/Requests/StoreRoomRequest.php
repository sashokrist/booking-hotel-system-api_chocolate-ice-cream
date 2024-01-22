<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|string|unique:rooms',
            'type' => 'required|string',
            'price_per_night' => 'required|numeric',
            'status' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'number.unique' => 'The room number is already in use.'
        ];
    }
}
