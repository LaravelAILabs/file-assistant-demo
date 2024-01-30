<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileAssistantStoreRequest extends FormRequest
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
			'file' => ['required_without:conversation_id', 'file'],
			'message' => ['required', 'string', 'min:20', 'max:1000'],
			'conversation_id' => ['numeric', 'gt:0']
		];
	}
}
