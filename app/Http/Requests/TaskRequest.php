<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $titleRule = [Rule::unique('tasks', 'title')->whereNull('deleted_at'), 'max:255'];
        $descriptionRule = ['max:500'];
        $userIdRule = ['uuid'];

        if ($this->method() === 'POST')
        {
            array_push($titleRule,'required');
            array_push($descriptionRule,'required');
            array_push($userIdRule,'required');
        }

        return [
            'title'         => $titleRule,
            'description'   => $descriptionRule,
            'attachment'    => 'array',
            'is_completed'  => 'boolean',
            'user_id'       => $userIdRule
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400));
    }
}
