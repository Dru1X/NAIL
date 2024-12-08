<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:1', 'max:75'],
            'last_name'  => ['required', 'string', 'min:1', 'max:75'],
        ];
    }
}
