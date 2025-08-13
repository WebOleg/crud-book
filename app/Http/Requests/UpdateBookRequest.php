<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'publication_date' => 'required|date',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'publication_date.required' => 'Publication date is required',
            'authors.required' => 'At least one author must be selected',
            'authors.min' => 'At least one author must be selected',
            'image.max' => 'Image size should not exceed 2MB',
            'image.mimes' => 'Image must be JPG or PNG format',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
