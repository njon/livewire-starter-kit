<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Lunar\Models\Discount;
use Lunar\Models\Channel;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminDiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::ownedByUser()->get();

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products = Product::ownedByUser()->get();
        return view('admin.discounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validation = [
            'name' => 'required|string|max:255',
            'coupon' => 'nullable|string|unique:'.Discount::class.',coupon',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'priority' => 'required|integer|min:0',
            'stop' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:lunar_products,id',
        ];
        
        // Add validation for percentage discount
        $validation['discount_type'] = 'required';
        if ($request->discount_type === 'custom') {
            $validation['custom_percentage'] = 'required|numeric|min:0|max:100';
        }
        
        $data = $request->validate($validation);
        $products = $data['product_ids'];
        unset($data['product_ids']);
        
        $data['starts_at'] = Carbon::parse($data['starts_at']);
        $data['ends_at'] = $data['ends_at'] ? Carbon::parse($data['ends_at']) : null;
        $data['stop'] = $request->has('stop');
        $data['uses'] = 0;
        $data['type'] = 'Lunar\DiscountTypes\AmountOff';
        $data['owner_id'] = Auth::user()->owner_id;
        
        // Generate unique handle
        $data['handle'] = $this->generateUniqueHandle($data['name']);
        
        // Build the percentage data
        $percentage = $request->discount_type === 'custom' ? (float) $request->custom_percentage : (float) $request->discount_type;
        $data['data'] = [
            'percentage' => $percentage,
            'fixed_value' => false
        ];
        
        unset($data['discount_type'], $data['custom_percentage']);
        
        $discount = Discount::create($data);

        $discount->customerGroups()->attach(1, [
            'visible' => true,
            'enabled' => true,
        ]);

        $defaultChannel = Channel::where('default', true)->first()->id;

        $discount->channels()->attach($defaultChannel, [
            'enabled' => true,
            'starts_at' => Carbon::now(),
        ]);

        // Attach products if provided
        if ($request->has('product_ids') && is_array($request->product_ids)) {
            $productData = [];
            foreach ($request->product_ids as $productId) {
                $productData[] = [
                    'discount_id' => $discount->id,
                    'discountable_type' => 'product',
                    'discountable_id' => $productId,
                    'type' => 'limitation',
                ];
            }
            
            if (!empty($productData)) {
                \Lunar\Models\Discountable::insert($productData);
            }
        }
        
        return redirect()->route('admin.discounts.edit', $discount->id)
            ->with('success', __('Discount created successfully. Please complete the details.'));
    }

    public function edit(Discount $discount)
    {
         $selectedProducts = $discount->discountables()
                                ->get()
                                ->pluck('discountable_id')
                                ->toArray();

        $products = Product::ownedByUser()->get();
        return view('admin.discounts.edit', compact('discount', 'products', 'selectedProducts'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validation = [
            'name' => 'required|string|max:255',
            'coupon' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_uses' => 'nullable|integer|min:1',
            'priority' => 'required|integer|min:0',
            'stop' => 'boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:lunar_products,id',
        ];

        
        // Add validation for percentage discount
        $validation['discount_type'] = 'required';
        if ($request->discount_type === 'custom') {
            $validation['custom_percentage'] = 'required|numeric|min:0|max:100';
        }
        
        $data = $request->validate($validation);
        $products = $data['product_ids'];
        unset($data['product_ids']);
        
        $data['starts_at'] = Carbon::parse($data['starts_at']);
        $data['ends_at'] = $data['ends_at'] ? Carbon::parse($data['ends_at']) : null;
        $data['stop'] = $request->has('stop');
        $data['type'] = 'Lunar\DiscountTypes\AmountOff';
        
        // Build the percentage data
        $percentage = $request->discount_type === 'custom' ? (float) $request->custom_percentage : (float) $request->discount_type;
        $data['data'] = [
            'percentage' => $percentage,
            'fixed_value' => false
        ];
        
        // Remove the extra fields we don't want to save directly
        unset($data['discount_type'], $data['custom_percentage']);
        
        $discount->update($data);


        // Sync products
        $discount->discountables()->delete();

        if ($request->has('product_ids') && is_array($request->product_ids)) {
            $productData = [];
            foreach ($request->product_ids as $productId) {
                $productData[] = [
                    'discount_id' => $discount->id,
                    'discountable_type' => 'product',
                    'discountable_id' => $productId,
                    'type' => 'limitation',
                ];
            }

            if (!empty($productData)) {
                \Lunar\Models\Discountable::insert($productData);
            }
        }

        return redirect()->route('admin.discounts.edit', $discount->id)
            ->with('success', __('Discount updated successfully.'));
    }

    public function destroy(Discount $discount)
    {
        $discount->discountables()->delete();

        $discount->forceDelete();
        
        return redirect()->route('admin.discounts.index')->with('success', __('Discount deleted successfully.'));
    }

    private function generateUniqueHandle($name)
    {
        $baseHandle = Str::slug($name);
        $handle = $baseHandle;
        $counter = 1;

        while (Discount::where('handle', $handle)->exists()) {
            $handle = $baseHandle . '-' . $counter;
            $counter++;
        }

        return $handle;
    }
}