<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarRequest extends FormRequest
{
    private $validationMessages = [
        'name.required' => "Pole názov auta je povinné !",
        'name.max' => "Pole názov auta môže mať maximálne 255 znakov !",
        'registration_number.required_if' => "Pole :attribute je povinné ak auto je zaregistrované !",
        'registration_number.unique' => "Zadané :attribute je už zabraté !",
        'registration_number.max' => "Pole :attribute musí mať :max znakov !",
        'registration_number.min' => "Pole :attribute musí mať :min znakov !",
        'registration_number.regex' => "Pole :attribute nespĺňa formát AB123XY",
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
            'registration_number' => [
                'nullable',
                'required_if:is_registered,true',
                Rule::unique('cars'),
                'string',
                'min:7',
                'max:7',
                'regex:/^[A-Za-z]{2}[0-9]{3}[A-Za-z]{2}$/',
            ],
            'is_registered' => 'boolean',
            'parts' => 'nullable|array'
        ];
    }

    public function messages(): array
    {
        return $this->validationMessages;
    }
}
