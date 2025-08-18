<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Language;
use App\Models\Channel as Channel;
use App\Http\Requests\ChannelValidator;
use Illuminate\Support\Facades\DB;

class AdminStoreController extends Controller
{
    public function index()
    {
        $stores = Channel::where('owner_id', auth()->user()->owner_id)->get();
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
        $filteredData = collect($validated + [
            'name' => 'asd', 'handle' => uniqid(), 'owner_id' => auth()->user()->owner_id
            ])->except(['attribute_data'])->toArray();

        $channel = Channel::create($filteredData);
        $channel->attribute_data = attributes_data($validated['attribute_data']);
        $channel->save();

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
        $this->authorize('delete', $store);

        DB::transaction(function () use ($store) {
            $store->products()->detach();
            $store->delete();
        });

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully.');
    }
}