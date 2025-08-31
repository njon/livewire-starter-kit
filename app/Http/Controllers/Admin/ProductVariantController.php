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
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name.en' => 'required|string',
            'name.gr' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
        ]);

        $variant = $product->variants()->create(
            common_attributes($validated)
        );
        
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
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name.en' => 'required|string',
            'name.gr' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
        ]);

        $variant->update([
            'attribute_data' => attributes_data($validated)
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
        $this->authorize('delete', $product);

        $variant->prices()->delete();
        $variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully'
        ]);
    }
}