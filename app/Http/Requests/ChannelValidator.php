<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChannelValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You should change this to your authorization logic
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'attribute_data.name.en' => 'required|string|max:255',
            'attribute_data.name.gr' => 'required|string|max:255',
            'attribute_data.description.en' => 'required|string|max:500',
            'attribute_data.description.gr' => 'required|string|max:500',
            'attribute_data.url.en' => 'required|string|max:255',
            'attribute_data.url.gr' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'email|string|max:255',
            'website' => 'string|max:255',
            'address' => 'nullable|string|max:500',
            'map_location' => 'nullable|string',
            'working_hours' => 'json',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add rules specific to store or update
        if ($this->isMethod('post')) {
            // Store-specific rules
            $rules['handle'] = 'sometimes|array';
            $rules['status'] = 'sometimes|in:active,inactive';
        }
        // No additional rules needed for update in this case

        return $rules;
    }
}