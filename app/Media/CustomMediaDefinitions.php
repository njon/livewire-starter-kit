<?php

namespace App\Media;

use Lunar\Base\MediaDefinitionsInterface;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\Format;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomMediaDefinitions implements MediaDefinitionsInterface
{
    public function registerMediaConversions(HasMedia $model, Media $media = null): void
    {
        $model->addMediaConversion('small')
            ->width(500)
            ->height(500)
            ->optimize() 
            ->quality(90)
            ->format('jpg');

        $model->addMediaConversion('medium')
            ->width(650)
            ->height(650)
            ->optimize() 
            ->quality(90)
            ->fit(Fit::Contain)
            ->format('jpg');

        $model->addMediaConversion('large')
            ->width(1280)
            ->height(720)
            ->optimize() 
            ->quality(90)
            ->fit(Fit::Max)
            ->format('jpg');
    }

    public function registerMediaCollections(HasMedia $model): void
    {
        $model->addMediaCollection('images')
            ->useFallbackUrl('/fallback.jpg')
            ->useFallbackPath('fallback.jpg')
            ->registerMediaConversions(function (Media $media) use ($model) {
                $this->registerDefaultConversions($model);
            });
    }

    protected function registerDefaultConversions(HasMedia $model): void
    {
       $model->addMediaConversion('zoom')
            ->width(500)
            ->height(500)
            ->fit(Fit::Fill)
            ->sharpen(10)
            ->quality(80)
            ->nonQueued();

        $model->addMediaConversion('large')
            ->width(800)
            ->height(800)
            ->fit(Fit::Max)
            ->quality(85);

        $model->addMediaConversion('medium')
            ->width(500)
            ->height(500)
            ->fit(Fit::Contain);
    }

    public function getMediaCollectionTitles(): array
    {
        return ['images' => 'Product Images'];
    }

    public function getMediaCollectionDescriptions(): array
    {
        return ['images' => 'Main product images'];
    }
}
