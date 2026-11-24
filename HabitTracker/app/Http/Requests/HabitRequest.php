<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HabitRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tree_type' => ['required', 'exists:tree_types,id'],
            'timing' => ['max:100'],
        ];

        if ($this->isMethod('POST')) {
        $rules['name'] = ['required', 'string', 'max:100'];
    }
     return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => '習慣化したい行動を入力してください',
            'name.max' => '行動は100字以内で入力してください',
            'name.string' => '行動は文字で入力してください',
            'tree_type.required' => '木の種類を選択してください',
            'tree_type.exsits' => '木の種類は選択肢から選択してください',
            'timing.max' => 'タイミングは100字以内で入力してください',
        ];
    }
}
