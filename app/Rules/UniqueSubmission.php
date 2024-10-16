<?php

namespace App\Rules;

use Closure;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueSubmission implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userId = Auth::id();
        // Check if the user has already submitted for the given assignment_id
        $submissionExists = Submission::where('assignment_id', $value)
                                      ->where('user_id', $userId)
                                      ->exists();

        if ($submissionExists) {
            $fail('You have already submitted for this assignment.');
        }
    }
}
