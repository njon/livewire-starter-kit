<?php
// app/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Lunar\Models\ProductType;
use Lunar\Models\TaxClass;
use Lunar\Models\Collection;
use Lunar\Models\Language;
use Lunar\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with(['variants'])->paginate(25);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $productTypes = ProductType::all();
        $taxClasses = TaxClass::all();
        $collections = Collection::with(['defaultUrl'])->get();
        $languages = Language::all();
        $channels = Channel::all();
        
        return view('admin.products.create', compact(
            'productTypes',
            'taxClasses',
            'collections',
            'languages',
            'channels'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        
        $product = Product::create([
            'product_type_id' => $validated['product_type_id'],
            'status' => $validated['status'],
            'brand_id' => $validated['brand_id'] ?? null,
        ]);
        
        // Save attributes
        foreach ($validated['name'] as $locale => $name) {
            $product->translate('name', $locale, $name);
            $product->translate('description', $locale, $validated['description'][$locale] ?? null);
        }
        
        // Save URLs
        foreach ($validated['urls'] as $locale => $slug) {
            $product->urls()->create([
                'slug' => $slug,
                'language_id' => Language::where('code', $locale)->first()->id,
                'default' => true,
            ]);
        }
        
        // Save pricing
        $product->variants()->create([
            'sku' => $validated['sku'],
            'tax_class_id' => $validated['tax_class_id'],
            'prices' => [
                [
                    'price' => $validated['price'] * 100, // Convert to cents
                    'currency_id' => 1, // Default currency
                ]
            ],
        ]);
        
        // Save collections
        if (!empty($validated['collections'])) {
            $product->collections()->sync($validated['collections']);
        }
        
        // Save channels
        if (!empty($validated['channels'])) {
            foreach ($validated['channels'] as $channelId => $channelData) {
                $product->channels()->attach($channelId, [
                    'starts_at' => $channelData['start_date'] ?? null,
                    'ends_at' => $channelData['end_date'] ?? null,
                    'enabled' => $channelData['enabled'] ?? false,
                ]);
            }
        }
        
        // Save variants
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'stock' => $variantData['stock'] ?? 0,
                    'prices' => [
                        [
                            'price' => $variantData['price'] * 100,
                            'currency_id' => 1,
                        ]
                    ],
                ]);
            }
        }
        
        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $product->load(['variants', 'collections', 'channels', 'urls']);
        $productTypes = ProductType::all();
        $taxClasses = TaxClass::all();
        $collections = Collection::with(['defaultUrl'])->get();
        $languages = Language::all();
        $channels = Channel::all();
        
        return view('admin.products.edit', compact(
            'product',
            'productTypes',
            'taxClasses',
            'collections',
            'languages',
            'channels'
        ));
    }

    public function update(Request $request, Product $product)
    {
                dd(1);
$validated = $this->validateRequest($request);

        dd($validated);
        
        $product->update([
            'status' => $validated['status'],
        ]);
        
        // Update attributes
        foreach ($validated['name'] as $locale => $name) {
            $product->translate('name', $locale, $name);
            $product->translate('description', $locale, $validated['description'][$locale] ?? null);
        }
        
        // Update URLs
        $product->urls()->delete();
        foreach ($validated['urls'] as $locale => $slug) {
            $product->urls()->create([
                'slug' => $slug,
                'language_id' => Language::where('code', $locale)->first()->id,
                'default' => true,
            ]);
        }
        
        // Update pricing for default variant
        $defaultVariant = $product->variants()->first();
        if ($defaultVariant) {
            $defaultVariant->update([
                'sku' => $validated['sku'],
                'tax_class_id' => $validated['tax_class_id'],
            ]);
            
            $defaultVariant->prices()->delete();
            $defaultVariant->prices()->create([
                'price' => $validated['price'] * 100,
                'currency_id' => 1,
            ]);
        }
        
        // Update collections
        $product->collections()->sync($validated['collections'] ?? []);
        
        // Update channels
        $product->channels()->detach();
        if (!empty($validated['channels'])) {
            foreach ($validated['channels'] as $channelId => $channelData) {
                $product->channels()->attach($channelId, [
                    'starts_at' => $channelData['start_date'] ?? null,
                    'ends_at' => $channelData['end_date'] ?? null,
                    'enabled' => $channelData['enabled'] ?? false,
                ]);
            }
        }
        
        // Update variants
        $product->variants()->where('id', '!=', $defaultVariant->id ?? null)->delete();
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'stock' => $variantData['stock'] ?? 0,
                    'prices' => [
                        [
                            'price' => $variantData['price'] * 100,
                            'currency_id' => 1,
                        ]
                    ],
                ]);
            }
        }
        
        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        dd(1);
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'product_type_id' => 'required|exists:lunar_product_types,id',
            'status' => 'required|string',
            'brand_id' => 'nullable|exists:lunar_brands,id',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'urls.*' => 'required|string|max:255|unique:lunar_urls,slug',
            'tax_class_id' => 'required|exists:lunar_tax_classes,id',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:255|unique:lunar_product_variants,sku',
            'track_inventory' => 'nullable|boolean',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:lunar_collections,id',
            'channels' => 'nullable|array',
            'channels.*.id' => 'exists:lunar_channels,id',
            'channels.*.start_date' => 'nullable|date',
            'channels.*.end_date' => 'nullable|date|after:channels.*.start_date',
            'channels.*.enabled' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.sku' => 'required|string|max:255|unique:lunar_product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);
    }
}