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
        $this->authorize('update', $product);

        $request->validate(['file' => 'required|image|max:2048']);
        $validated = $request->validate(['thumbnail' => 'boolean']);

        $collection = $validated['thumbnail'] ?? false ? 'thumbnails' : 'products';

        if ($collection === 'thumbnails') {
            $product->clearMediaCollection('thumbnails');
        }

        $media = $product
            ->addMediaFromRequest('file')
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
        $this->authorize('update', $product);

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

        return response()->json(['success' => false, 'message' => __('Media not found')], 404);
    }

    public function index()
    {
        $products = Product::with(['variants'])->ownedByUser('lunar_products')
            ->leftJoin('lunar_order_lines', 'lunar_order_lines.id', '=', 'lunar_products.id')
            ->select('lunar_products.*', DB::raw('SUM(lunar_order_lines.quantity) as total_sales'))
            ->groupBy('lunar_products.id')
            ->get();

        $productTypes = ProductType::all();

        return view('admin.products.index', compact('products', 'productTypes'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $product->load([
            'variants.prices.currency', 
            'collections.defaultUrl', 
            'channels', 
            'urls.language',
            'media'
        ]);
        
        $productTypes = ProductType::all();
        $taxClasses = TaxClass::all();
        $collections = Collection::with(['defaultUrl', 'children.defaultUrl'])->get();
        $languages = Language::all();
        $filterCategories = FilterCategory::with('options')->get();
        $channels = Channel::where('owner_id', auth()->user()->owner_id)->get();
        
        $variant = $product->variants->first();
        $variants = $product->variants->slice(1);
        $sub_category = $product->collections->where('parent_id', '!==', null)->pluck('id')->first();
        $price = $variant?->prices?->first()?->price?->value / 100 ?? 0;

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
            'variants',
            'price'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name.gr' => 'nullable|string|max:255',
            'product_type_id' => 'required|exists:'.ProductType::class.',id',
        ]);

        $commonAttributes = common_attributes($validated);

        $product = Product::create($commonAttributes + [
            'product_type_id' => $validated['product_type_id'],
            'status' => 'draft',
            'owner_id' => auth()->user()->owner_id
        ]);

        $variant = $product->variants()->create($commonAttributes);

        $variant->prices()->create([
            'price' => 0,
            'currency_id' => Currency::first()->id ?? 1, 
        ]);

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', __('Product created successfully. Please complete the details.'));
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
            'attribute_data' => attributes_data($validated)
        ];
        $product->update($productAttributes);

        $variantAttributes = Arr::except($productAttributes, ['product_type_id', 'status']);
        
        $defaultVariant = $product->variants()->updateOrCreate(['id' => $product->variants()->first()->id], $variantAttributes );
        $defaultVariant->prices()->first()->update(['price' => $validated['price'] * 100]);

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
            ->with('success', __('Product updated successfully'));
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // delete product variants from active carts ( IMPORATNT, otherwise Errors )
        $deleted_ids = $product->variants->pluck('id')->all();

        remove_deleted_services_from_carts($deleted_ids);

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', __('Product deleted successfully'));
    }
}