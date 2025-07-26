<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // or your model
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaUploadController extends Controller
{
    public function upload(Request $request)
    {
        $x = $request->get('image');
        
        $model = Product::findOrFail(12);
        
        $media = $model->addMediaFromRequest('image')
                      ->toMediaCollection('images');
                      
        return response()->json([
            'success' => true,
            'media_id' => $media->id,
            'url' => $media->getUrl()
        ]);
    }
    
    public function delete(Media $media)
    {
        $media->delete();
        
        return response()->json(['success' => true]);
    }
}