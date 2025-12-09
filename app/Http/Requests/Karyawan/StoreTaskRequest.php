<?php

namespace App\Http\Requests\Karyawan;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:128',
            'description' => 'required',
            'priority' => 'required|integer|in:1,2,3',
            'deadline' => 'required|date',
            'status' => 'required|integer|in:1,2,3,4',
        ];
    }
}
