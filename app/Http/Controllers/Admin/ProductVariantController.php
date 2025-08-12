<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Lunar\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name.en' => 'required|string',
            'name.gr' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
        ]);


        $variant = $product->variants()->create([
            'stock' => 555,
            'tax_class_id' => 1,
            'attribute_data' => [
                'name' => new TranslatedText([
                    'en' => new Text($validated['name']['en']),
                    'gr' => new Text($validated['name']['gr']),
                ])
            ]
        ]);

        $variant->prices()->create([
            'price' => $validated['price'] * 100,
            'currency_id' => 1,
        ]);

        $html = View::make('admin.products.variant', [
            'variant' => $variant,
            'product' => $product
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'message' => 'Variant created successfully'
        ]);
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'name.en' => 'required|string',
            'name.gr' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
        ]);

        $variant->update([
            'stock' => $validated['stock'] ?? $variant->stock,
            'attribute_data' => [
                'name' => new TranslatedText([
                    'en' => new Text($validated['name']['en']),
                    'gr' => new Text($validated['name']['gr'] ?? $validated['name']['en']),
                ]),
                'description' => $variant->attribute_data['description'] ?? new TranslatedText([
                    'en' => new Text(''),
                    'gr' => new Text(''),
                ]),
            ]
        ]);

        $variant->prices()->update([
            'price' => $validated['price'] * 100,
        ]);

        $html = View::make('admin.products.variant', [
            'variant' => $variant->fresh(),
            'product' => $product
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'message' => 'Variant updated successfully'
        ]);
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->prices()->delete();
        $variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully'
        ]);
    }
}