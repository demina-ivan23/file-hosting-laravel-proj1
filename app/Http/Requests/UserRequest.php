<?php

namespace App\Http\Requests;

use GuzzleHttp\Psr7\MimeType;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // $mimeType = MimeType::fromFilename($this['profileImage']);
        // dd($mimeType);
        return [
            'name' => 'min:3|max:225|required|string',
            'email' => 'email|required',
            'password' => 'min:8|max:225|nullable',
            'passwordConfirmation' => 'min:8|max:225|nullable',
            'profileImage' => 'nullable|mimes:jpg,jpeg,png'
        ];
    }
}
