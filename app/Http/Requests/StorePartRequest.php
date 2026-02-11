<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartRequest extends FormRequest
{
    private $validationMessages = [
        'name.required' => "Pole názov dielu je povinné !",
        'name.max' => "Pole názov dielu môže mať maximálne 255 znakov !",
        'serial_number.required' => "Pole :attribute je povinné !",
        'serial_number.min' => "Pole :attribute musí mať minimálne 6 a maximálne 12 znakov !",
        'serial_number.max' => "Pole :attribute musí mať minimálne 6 a maximálne 12 znakov !",
        'serial_number.unique' => "Zadané :attribute je už zabraté !",
        'car_id.integer' => "Zvolili ste nesprávne auto !",
        'car_id.exists' => "Zvolené auto už neexistuje !"
    ];
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
            'name' => 'required|string|max:255',
            'serial_number' => [
                'required',
                'string',
                'min:6',
                'max:12',
                Rule::unique('parts')
            ],
            'car_id' => [
                'nullable',
                'integer',
                Rule::exists('cars', 'id')
            ]
        ];
    }

    public function messages(): array
    {
        return $this->validationMessages;
    }
}

