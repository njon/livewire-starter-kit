<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \Lunar\Models\TaxClass;
use \Lunar\Models\Channel;
use \Lunar\Models\Collection;
use Lunar\Models\Language;
use Lunar\Models\Product;
use Lunar\Models\Currency;
use Lunar\FieldTypes\TranslatedText;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\Price;
use Lunar\Models\ProductType;

class AdminProductController extends Controller
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

    // public function store(Request $request)
    // {
    //     $validated = $this->validateRequest($request);
        
    //     $product = Product::create([
    //         'product_type_id' => $validated['product_type_id'],
    //         'status' => $validated['status'],
    //         'brand_id' => $validated['brand_id'] ?? null,
    //     ]);
        
    //     // Save attributes
    //     foreach ($validated['name'] as $locale => $name) {
    //         $product->translate('name', $locale, $name);
    //         $product->translate('description', $locale, $validated['description'][$locale] ?? null);
    //     }
        
    //     // Save URLs
    //     foreach ($validated['urls'] as $locale => $slug) {
    //         $product->urls()->create([
    //             'slug' => $slug,
    //             'language_id' => Language::where('code', $locale)->first()->id,
    //             'default' => true,
    //         ]);
    //     }
        
    //     // Save pricing
    //     $product->variants()->create([
    //         'sku' => $validated['sku'],
    //         'tax_class_id' => $validated['tax_class_id'],
    //         'prices' => [
    //             [
    //                 'price' => $validated['price'] * 100, // Convert to cents
    //                 'currency_id' => 1, // Default currency
    //             ]
    //         ],
    //     ]);
        
    //     // Save collections
    //     if (!empty($validated['collections'])) {
    //         $product->collections()->sync($validated['collections']);
    //     }
        
    //     // Save channels
    //     if (!empty($validated['channels'])) {
    //         foreach ($validated['channels'] as $channelId => $channelData) {
    //             $product->channels()->attach($channelId, [
    //                 'starts_at' => $channelData['start_date'] ?? null,
    //                 'ends_at' => $channelData['end_date'] ?? null,
    //                 'enabled' => $channelData['enabled'] ?? false,
    //             ]);
    //         }
    //     }
        
    //     // Save variants
    //     if (!empty($validated['variants'])) {
    //         foreach ($validated['variants'] as $variantData) {
    //             $variation = $product->variants()->create([
    //                 'sku' => $variantData['sku'],
    //                 'stock' => $variantData['stock'] ?? 0,
    //                 'tax_class_id' => $validated['tax_class_id'],
    //                 'attribute_data' => [
    //                     'name' => new TranslatedText([
    //                         'en' => new Text($variantData['name']['en']),
    //                     ]),
    //                 ]
    //             ]);

    //             $variation->prices()->create([
    //                 'price' => $variantData['price'],
    //                 'currency_id' => 1, // Adjust as needed for your currency setup
    //             ]);
    //         }
    //     }

    //     return redirect()->route('admin.products.edit', $product->id)
    //         ->with('success', 'Product created successfully');
    // }

    public function edit(Product $product)
    {
        $product->load(['variants', 'collections', 'channels', 'urls']);
        $productTypes = ProductType::all();
        $taxClasses = TaxClass::all();
        $collections = Collection::with(['defaultUrl'])->get();
        $languages = Language::all();
        $channels = Channel::all();
        $variant = $product->variants->first();


        return view('admin.products.edit', compact(
            'product',
            'productTypes',
            'taxClasses',
            'collections',
            'languages',
            'channels',
            'variant'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateRequest($request);

        // Update product basic info
        $product->update([
            'product_type_id' => $validated['product_type_id'],
            'status' => $validated['status'],
        ]);


        // Handle default variant when no variants are provided
        $defaultVariant = $product->variants()->updateOrCreate(
                ['id' => $product->variants()->first()?->id],
                [
                    'sku' => $validated['sku'],
                    'tax_class_id' => $validated['tax_class_id'],
                    'stock' => 500,
                    'attribute_data' => [
                        'name' => new TranslatedText([
                            'en' => new Text($validated['name']['en']),
                            'gr' => new Text($validated['name']['gr']),
                        ]),
                        'description' => new TranslatedText([
                            'en' => new Text($validated['description']['en']),
                            'gr' => new Text($validated['description']['gr']),
                        ]),
                    ]
                ]
            );

            if ($defaultVariant) {
                $defaultVariant->prices()->delete();
                $defaultVariant->prices()->create([
                    'price' => $validated['price'],
                    'currency_id' => 1,
                    'priceable_type' => Product::class,
                    'priceable_id' => $defaultVariant->id,
                ]);
            }
            
        if(!empty($validated['variants'])) {
            // Handle when variants are provided
            $existingVariantIds = [];
            $existingVariantIds[] = $product->variants()->first()?->id; 


            
            foreach ($validated['variants'] as $variantData) {
                $variant = $product->variants()->updateOrCreate(
                    ['id' => $variantData['id']],
                    [
                        'stock' => $variantData['stock'] ?? 0,
                        'tax_class_id' => $validated['tax_class_id'],
                        'attribute_data' => [
                            'name' => new TranslatedText([
                                'en' => new Text($variantData['name']['en']),
                            ]),
                        ]
                    ]
                );

                $variant->prices()->delete();
                $variant->prices()->create([
                    'price' => $variantData['price'],
                    'currency_id' => 1,
                ]);

                $existingVariantIds[] = $variant->id;
            }

            
            // Delete variants that weren't included in the request
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
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

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
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
            // 'urls.*' => 'required|string|max:255|unique:lunar_urls,slug',
            'urls.*' => 'required|string|max:255',
            'tax_class_id' => 'required|exists:lunar_tax_classes,id',
            'price' => 'required|numeric|min:0',
            // 'sku' => 'required|string|max:255|unique:lunar_product_variants,sku',
            'sku' => 'required|string|max:255',
            'track_inventory' => 'nullable|boolean',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:lunar_collections,id',
            'channels' => 'nullable|array',
            'channels.*.id' => 'exists:lunar_channels,id',
            'channels.*.start_date' => 'nullable|date',
            'channels.*.end_date' => 'nullable|date|after:channels.*.start_date',
            'channels.*.enabled' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'string',
            'variants.*.name.*' => 'required|string|max:255',
            // 'variants.*.sku' => 'required|string|max:255|unique:lunar_product_variants,sku',
            'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);
    }

    public function slugExists(Request $request)
    {
        $slug = $request->input('slug');
        $locale = $request->input('locale', 'en');

        $exists = DB::table('lunar_urls')
            ->where('slug', $slug)
            ->where('language_id', Language::where('code', $locale)->first()->id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}