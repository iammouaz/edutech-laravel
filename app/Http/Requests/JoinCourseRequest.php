<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JoinCourseRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'courseId' => [
                'required',
                'integer',
                'exists:courses,id',
                function ($attribute, $value, $fail) {
                    $user = Auth::user();

                    $alreadyJoined = DB::table('course_user')
                        ->where('course_id', $value)
                        ->where('user_id', $user->id)
                        ->exists();

                    if ($alreadyJoined) {
                        $fail('You have already joined this course.');
                    }
                },
            ],
        ];
    }

    public function all($keys = null)
    {
        // Merge the route parameters into the validation data
        return array_merge(parent::all(), $this->route()->parameters());
    }

    public function messages()
    {
        return [
            'courseId.required' => 'The course ID is required.',
            'courseId.integer'  => 'The course ID must be a valid integer.',
            'courseId.exists'   => 'The course with this ID does not exist.',
        ];
    }
}
