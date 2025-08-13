<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Channel;
use Lunar\Models\Collection;
use Lunar\Models\Language;
use Lunar\Models\TaxClass;
use Lunar\Models\ProductVariant;
use App\Models\Product;
use Lunar\Models\Currency;
use Lunar\FieldTypes\TranslatedText;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\Price;
use Lunar\Models\ProductType;
use App\Models\FilterCategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
{
 public function storeMedia(Request $request, Product $product)
{
    $request->validate(['file' => 'required|image|max:2048']);
    $validated = $request->validate(['thumbnail' => 'boolean']);

    $collection = $validated['thumbnail'] ?? false ? 'thumbnails' : 'products';

    if ($collection === 'thumbnails') {
        $product->clearMediaCollection('thumbnails');
    }

    $media = $product->addMediaFromRequest('file')->toMediaCollection($collection);
    

    return response()->json([
        'id' => $media->id, // Return media ID
        'url' => $media->getUrl()
    ]);
}
public function destroyMedia(Request $request, Product $product)
{
    $mediaId = $request->input('media_id');
    $media = $product->media()->where('id', $mediaId)->first();

    if ($media) {
        $media->delete();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Media not found'], 404);
}

    public function index()
    {
        // @todo check only sold items
        // @todo add owner_id to other models
        $products = Product::with(['variants'])
            ->leftJoin('lunar_order_lines', 'lunar_order_lines.id', '=', 'lunar_products.id')
            ->select('lunar_products.*', DB::raw('SUM(lunar_order_lines.quantity) as total_sales'))
            ->groupBy('lunar_products.id')
            ->paginate(25);

        // return view('admin.products.only', compact('products'));
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $productTypes = ProductType::all();
        $taxClasses = TaxClass::all();
        $collections = Collection::with(['defaultUrl', 'children.defaultUrl'])->get();
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:'.ProductType::class.',id',
        ]);

        $product = Product::create([
            'owner_id' => auth()->user()->id,
            'stock' => 99999999,
            'product_type_id' => $validated['product_type_id'],
            'status' => 'published', 
            'attribute_data' => [
                'name' => new TranslatedText([
                    'gr' => new Text($validated['name']),
                ])
            ]
        ]);

        $productVariant = ProductVariant::create([
            'owner_id' => auth()->user()->id,
            'product_id' => $product->id,
            'stock' => 99999999, // Default stock
            'tax_class_id' => 1, // Default tax class
            'attribute_data' => [
                'name' => new TranslatedText([
                    'gr' => new Text($validated['name']),
                ]),
            ]
        ]);

        $productVariant->prices()->create([
            'price' => 0, 
            'currency_id' => 1, 
        ]);

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product created successfully. Please complete the details.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateRequest($request, $product);
        
        if ($request->has('urls')) {
            $urls = $request->input('urls');
            foreach ($urls as $locale => $slug) {
                $urls[$locale] = \Illuminate\Support\Str::slug($slug);
            }
            $request->merge(['urls' => $urls]);
        }
        
        $product->collections()->sync([$validated['category'], $validated['sub_category']]);

        $ids = collect($validated['filters'] ?? [])->flatten()->all();
        $product->filterOptions()->sync($ids);

        // Update product basic info
        $product->update([
            'product_type_id' => $validated['product_type_id'],
            'status' => $validated['status'],
            'tax_class_id' => $validated['tax_class_id'],
            'stock' => 9999,
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
        ]);

        $defaultVariant = $product->variants()->updateOrCreate(
            ['id' => $product->variants()->first()?->id],
            [
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
            
        $product->urls()->delete();
        foreach ($validated['urls'] as $locale => $slug) {
            $product->urls()->create([
                'slug' => $slug,
                'language_id' => Language::where('code', $locale)->first()->id,
                'default' => true,
            ]);
        }

        

        // Update channels
        $product->channels()->detach();
        if (!empty($validated['channels'])) {
            foreach ($validated['channels'] as $channelId => $channelData) {
                if(isset($channelData['enabled'])) {
                    $product->channels()->attach($channelId, [
                        'starts_at' => $channelData['start_date'] ?? null,
                        'ends_at' => $channelData['end_date'] ?? null,
                        'enabled' => $channelData['enabled'] ?? 1,
                    ]);
                }
                
            }
        }

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully');
    }

    public function edit(Product $product)
    {
        $product->when(auth()->user()->owner_id != 0, fn($q) => $q->where('owner_id', auth()->id()));
        
        $product->load(['variants', 'collections', 'channels', 'urls']);

        $productTypes = ProductType::all();
        $taxClasses = TaxClass::all();
        $collections = Collection::with(['defaultUrl', 'children.defaultUrl'])->get();
        $languages = Language::all();
        $filterCategories = FilterCategory::with('options')->get();
        $channels = Channel::all();
        $variant = $product->variants->first();
        $variants = $product->variants->slice(1);
        $sub_category = $product->collections->where('parent_id', '!==', null)->pluck('id')->first();

        return view('admin.products.edit', compact(
            'product',
            'productTypes',
            'taxClasses',
            'collections',
            'languages',
            'channels',
            'variant',
            'filterCategories',
            'sub_category',
            'variants'
        ));
    }


   protected function validateRequest(Request $request, $product = null)
    {
        $baseRules = [
            'product_type_id' => 'required|exists:lunar_product_types,id',
            'status' => 'required|string',
            'name.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'filters.*' => 'nullable|array',
            'tax_class_id' => 'required|exists:lunar_tax_classes,id',
            'price' => 'required|numeric|min:0',
            'channels.*' => 'array|required',
            'variants' => 'nullable|array',
            'variants.*.id' => 'string',
            'variants.*.name.*' => 'required|string|max:255',
            // 'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'category' => 'required|exists:lunar_collections,id',
            'sub_category' => 'required|exists:lunar_collections,id',
        ];

        // @todo check for multilanguage messages
        if ($request->has('urls')) {
                $baseRules['urls'] = 'required|array';

                foreach ($request->input('urls', []) as $lang => $slug) {
                    $baseRules["urls.{$lang}"] = [
                        'required',
                        'string',
                        'max:255',
                        function ($attribute, $value, $fail) use ($lang, $product) {
                            $existingUrl = \Lunar\Models\Url::where('slug', $value)
                                ->where('language_id',  Language::where('code', $lang)->first()->id)
                                ->first();

                            if ($existingUrl) {
                                // If creating new product or updating to a URL owned by another product
                                if (!$product || $existingUrl->element_id != $product->id) {
                                    $fail("The URL for {$lang} is already taken.");
                                }
                            }
                        }
                    ];
                }
            }

        return $request->validate($baseRules);
    }

    

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
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