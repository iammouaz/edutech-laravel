<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultipleSubmissionsRequest extends FormRequest
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
            'submissions' => 'required|array|max:5',  // Restrict to a maximum of 5 submissions
            'submissions.*.assignment_id' => 'required|integer|exists:assignments,id',
            'submissions.*.content' => 'required|string',
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'submissions.required' => 'Submissions array is required.',
            'submissions.max' => 'You can only submit up to 5 assignments at a time.',
            'submissions.*.assignment_id.required' => 'Assignment ID is required for each submission.',
            'submissions.*.content.required' => 'Content is required for each submission.',
        ];
    }
}
