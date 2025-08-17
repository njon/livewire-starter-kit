<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Lunar\Models\Url;
use Lunar\Models\Language;

class ServiceValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Changed to true to allow validation
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'product_type_id' => 'required|exists:lunar_product_types,id',
            'status' => 'required|string|in:draft,published',
            'name.*' => 'required|string|max:255',
            'description.*' => 'required|nullable|string',
            'filters.*' => 'nullable|array',
            'tax_class_id' => 'required|exists:lunar_tax_classes,id',
            'price' => 'required|numeric|min:0',
            'channels.*' => 'array|required',
            'variants' => 'nullable|array',
            'variants.*.id' => 'string',
            'variants.*.name.*' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'category' => 'required|exists:lunar_collections,id',
            'sub_category' => 'required|exists:lunar_collections,id',
        ];

        if ($this->has('urls')) {
            $rules['urls'] = 'required|array';
            
            foreach ($this->input('urls', []) as $lang => $slug) {
                $rules["urls.{$lang}"] = [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($lang) {
                        $product = $this->route('product'); // Assuming route model binding
                        $languageId = Language::where('code', $lang)->value('id');
                        
                        $query = Url::where('slug', $value)
                            ->where('language_id', $languageId);
                        
                        if ($product) {
                            $query->where('element_id', '!=', $product->id);
                        }
                        
                        if ($query->exists()) {
                            $fail("The URL for {$lang} is already taken.");
                        }
                    }
                ];
            }
        }

        if ($this->input('status') === 'published') {
            $rules['_image_check'] = [
                'required',
                'accepted',
                function ($attribute, $value, $fail) {
                    $product = $this->route('product');
                    
                    if (!$product->hasMedia('thumbnails')) {
                        $fail('At least 1 thumbnail image is required for published products');
                        $product->update(['status' => 'draft']);
                    }
                    
                    if ($product->getMedia('products')->count() < 4) {
                        $fail('At least 4 product images are required for published products');
                        $product->update(['status' => 'draft']);
                    }
                }
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
             '_image_check.accepted' => 'The product must have at least 1 thumbnail and 4 product images to be published',
            'name.*.required' => 'Each language requires a product name',
            'variants.*.name.*.required' => 'Each variant requires names in all languages',
            'urls.*.required' => 'Each language requires a URL slug',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->input('status') === 'published') {
            $this->merge(['_image_check' => true]);
        }
    }
}