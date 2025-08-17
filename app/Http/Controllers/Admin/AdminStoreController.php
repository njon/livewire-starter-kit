<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Language;
use App\Models\Channel as Channel;
use App\Http\Requests\ChannelValidator;

class AdminStoreController extends Controller
{
    public function index()
    {
        $stores = Channel::all();
        $languages = Language::all();

        return view('admin.stores.index', compact('stores', 'languages'));
    }

    public function create()
    {
        $languages = Language::all();

        return view('admin.stores.create', compact('languages'));
    }

    public function store(ChannelValidator $request)
    {   
        $validated = $request->validated();

        Channel::create(array_merge($validated,
            ['attribute_data' => attribute_data($validated['attribute_data'])]
        ));

        return redirect()->route('stores.index')
            ->with('success', 'Store created successfully.');
    }

    public function edit(Channel $store)
    {
        $this->authorize('update', $store);
        
        $languages = Language::all();

        return view('admin.stores.edit', compact('store', 'languages'));
    }

    public function update(ChannelValidator $request, Channel $store)
    {
        $validated = $request->validated();

        $store->update(array_merge($validated,
            ['attribute_data' => attribute_data($validated['attribute_data'])]
        ));

        return redirect()->route('stores.edit', $store->id)
            ->with('success', 'Store updated successfully.');
    }

    public function destroy(Channel $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully.');
    }
}