<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\FieldTypes\TranslatedText;
use Lunar\FieldTypes\Text;
use Illuminate\Support\Facades\Validator;
use Lunar\Models\Language;
use App\Models\Channel as Channel;

class AdminStoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    public function index()
    {
        $stores = Channel::all();
        $languages = Language::all();

        return view('admin.stores.index', compact('stores', 'languages'));
    }
    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        $languages = Language::all();

        return view('admin.stores.create', compact('languages'));
    }

    /**
     * Store a newly created store in storage.
     */
    public function store(Request $request)
    {   
        $attribute_data = $request->validate([
            'attribute_data.name.en' => 'required|string|max:255',
            'attribute_data.name.gr' => 'required|string|max:255',
            'attribute_data.description.en' => 'required|string|max:500',
            'attribute_data.description.gr' => 'required|string|max:500',
            'attribute_data.url.en' => 'required|string|max:255',
            'attribute_data.url.gr' => 'required|string|max:255',
        ]);

        $attributes = $attribute_data['attribute_data'];

        $validated = $request->validate([
            'handle' => 'sometimes|array',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|max:255',
            'website' => 'string|max:255',
            'address' => 'nullable|string|max:500',
            'map_location' => 'nullable|string', // Could be JSON or coordinates
            // 'images' => 'sometimes|array',
            // 'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            // Working hours (stored as JSON)
            'working_hours' => 'nullable|json',
            // Status (assuming you have this field)
            'status' => 'sometimes|in:active,inactive',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        
        ]);

        $validated['name'] = $attributes['name']['en'];
        $validated['handle'] = $attributes['url']['en'];
        $validated['owner_id'] = auth()->user()->owner_id;

        $channel = Channel::create($validated);

        $channel->attribute_data = [
            'name' => new TranslatedText([
                'en' => new Text($attributes['name']['en']),
                'gr' => new Text($attributes['name']['gr']),
            ]),
            'description' => new TranslatedText([
                'en' => new Text($attributes['description']['en']),
                'gr' => new Text($attributes['description']['gr']),
            ]),
            'url' => new TranslatedText([
                'en' => new Text($attributes['description']['en']),
                'gr' => new Text($attributes['description']['gr']),
            ]),
        ];

        $channel->save();

        return redirect()->route('stores.index')
            ->with('success', 'Store created successfully.');
    }

    /**
     * Show the form for editing the specified store.
     */
    public function edit(Channel $store)
    {
        $languages = Language::all();
        return view('admin.stores.edit', compact('store', 'languages'));
    }

    /**
     * Update the specified store in storage.
     */
    public function update(Request $request, Channel $store)
    {
        $attribute_data = $request->validate([
            'attribute_data.name.en' => 'required|string|max:255',
            'attribute_data.name.gr' => 'required|string|max:255',
            'attribute_data.description.en' => 'required|string|max:500',
            'attribute_data.description.gr' => 'required|string|max:500',
            'attribute_data.url.en' => 'required|string|max:255',
            'attribute_data.url.gr' => 'required|string|max:255',
        ]);
        $attributes = $attribute_data['attribute_data'];

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|max:255',
            'website' => 'string|max:255',
            'address' => 'nullable|string|max:500',
            'map_location' => 'nullable|string',
            'working_hours' => 'nullable|json',
            // 'status' => 'sometimes|in:active,inactive',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $store->name = $attributes['name']['en'];
        $store->handle = $attributes['url']['en'];
        $store->phone = $validated['phone'] ?? null;
        $store->email = $validated['email'] ?? null;
        $store->website = $validated['website'] ?? null;
        $store->address = $validated['address'] ?? null;
        $store->map_location = $validated['map_location'] ?? null;
        $store->working_hours = $validated['working_hours'] ?? null;
        // $store->status = $validated['status'] ?? $store->status;

        $store->attribute_data = [
            'name' => new TranslatedText([
                'en' => new Text($attributes['name']['en']),
                'gr' => new Text($attributes['name']['gr']),
            ]),
            'description' => new TranslatedText([
                'en' => new Text($attributes['description']['en']),
                'gr' => new Text($attributes['description']['gr']),
            ]),
            'url' => new TranslatedText([
                'en' => new Text($attributes['url']['en']),
                'gr' => new Text($attributes['url']['gr']),
            ]),
        ];

        $store->save();

        return redirect()->route('stores.edit', $store->id)
            ->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Channel $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully.');
    }
}