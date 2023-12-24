<?php

namespace App\Http\Requests\UserContact;

use Illuminate\Foundation\Http\FormRequest;

class SendFileRequest extends FormRequest
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
            'title' => 'nullable|max:225',
            'description' => 'nullable|max:225',
            'category' => 'nullable|max:25',
            'file' => 'required|mimes:jpg,img,jpeg,png,wmv,mp3,mp4,avi,mpeg'
        ];
    }
}
