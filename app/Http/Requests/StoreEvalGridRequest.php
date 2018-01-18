<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use CPNVEnvironment\Environment;
use App\Evaluation;

class StoreEvalGridRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Environment::currentUser()->getLevel() > 0) {
            // If the user is a teacher or admin the request is authored
            return true;
        } elseif (Environment::currentUser()->getLevel() == 0) {
            // If the user is a studend, we check if is this eval
            // Check if this eval belongs to this user
            if (Evaluation::find($this->route('gridID'))->visit->internship->student == Environment::currentUser()->getId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Validates all the specification fields
            '*.specs' => 'nullable|max:500',
            // Validates all the master comment fields
            '*.mComm' => 'nullable|max:500',
            // Validates all the student comments
            '*.sComm' => 'nullable|max:500',
            // Validates all the grades
            '*.grade' => 'nullable|numeric',
            // Check the submit field is present
            'submit' => 'required'
        ];
    }
}
