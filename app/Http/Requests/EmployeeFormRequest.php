<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'organization_id' => [
                'required'
            ],
            'supervisor_id' => [
                'required'],
            'regional_id' => [
                'required'
            ],
            'role_id' => [
                'required'
            ],
            'join_date' => [
                'required'
            ],
            'quit_date' => [
                'required'
            ],
            'employee_birth' => [
                'required'
            ],
            'employee_name' => [
                'required'
            ]
        ];

        if ($this->getMethod() == "POST") {
            $rules += [
                'email' => [
                    'required',
                    'email',
                    'unique:employee_tbl, employee_email'
                ]
            ];
        }

        return $rules;
    }
}
