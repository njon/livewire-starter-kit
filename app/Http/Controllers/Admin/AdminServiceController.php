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
use App\Policies\OwnershipPolicy;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Http\Requests\ServiceValidator;

class AdminServiceController extends Controller
{
    public function storeMedia(Request $request, Product $product)
    {
        $request->validate(['file' => 'required|image|max:2048']);
        $validated = $request->validate(['thumbnail' => 'boolean']);

        $collection = $validated['thumbnail'] ?? false ? 'thumbnails' : 'products';

        if ($collection === 'thumbnails') {
            $product->clearMediaCollection('thumbnails');
        }

        $media = $product->addMediaFromRequest('file')
        ->usingName($product->translateAttribute('name'))
        ->usingFileName($product->translateAttribute('name') . '.jpg')
        ->toMediaCollection($collection);
        
        return response()->json([
            'id' => $media->id, // Return media ID
            'url' => $media->getUrl('small')
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

        // $media = $product->media()->get();
        // foreach ($media as $item) {
        //     $item->delete();
        // }
        // return response()->json(['success' => true]);

        return response()->json(['success' => false, 'message' => 'Media not found'], 404);
    }

    public function index()
    {
        // @todo check only sold items
        // @todo add owner_id to other models
        $products = Product::with(['variants'])->where('lunar_products.owner_id', auth()->user()->owner_id)
            ->leftJoin('lunar_order_lines', 'lunar_order_lines.id', '=', 'lunar_products.id')
            ->select('lunar_products.*', DB::raw('SUM(lunar_order_lines.quantity) as total_sales'))
            ->groupBy('lunar_products.id')
            ->paginate(25);

        // return view('admin.products.only', compact('products'));
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:'.ProductType::class.',id',
        ]);

        $commonAttributes = [
            'tax_class_id' => 1,
            'owner_id' => auth()->user()->owner_id,
            'stock' => 5000,
            'attribute_data' => [
                'name' => new TranslatedText([
                    'gr' => new Text($validated['name']),
                ])
            ]
        ];

        $product = Product::create($commonAttributes + [
            'product_type_id' => $validated['product_type_id'],
            'status' => 'draft',
        ]);

        $productVariant = ProductVariant::create($commonAttributes + [
            'product_id' => $product->id,
        ]);

        $productVariant->prices()->create([
            'price' => 0, 
            'currency_id' => 1, 
        ]);

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product created successfully. Please complete the details.');
    }

    public function update(ServiceValidator $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validated();
        
        $validated['urls'] = collect($request->input('urls', []))
            ->mapWithKeys(fn ($slug, $locale) => [
                $locale => Str::slug($slug)
            ])
        ->all();
        
        $product->collections()->sync([$validated['category'], $validated['sub_category']]);
        
        $ids = collect($validated['filters'] ?? [])->flatten()->all();
        $product->filterOptions()->sync($ids);

        $productAttributes = Arr::only($validated, ['product_type_id', 'status', 'tax_class_id']) + [
            'stock' => 5000,
            'attribute_data' => product_attribute_data($validated)
        ];
        $product->update($productAttributes);


        $variantAttributes = Arr::except($productAttributes, ['product_type_id', 'status']);
        $defaultVariant = $product->variants()->updateOrCreate(['id' => $product->variants()->first()->id], $variantAttributes );

        $defaultVariant->prices()->first()->update(['price' => $validated['price']]);
            
        // @todo double check if works allright
        foreach ($validated['urls'] ?? [] as $locale => $url) {
            $product->urls()->updateOrCreate(
                ['language_id' => Language::firstWhere('code', $locale)?->id],
                ['slug' => $url, 'default' => true]
            );
        }

        $product->channels()->sync(
            collect($validated['channels'] ?? [])
                ->filter(fn ($channel) => $channel['enabled'] ?? false)
                ->keys()
                ->all()
        );

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

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

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }
}