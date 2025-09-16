<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\OwnerScope;

class Article extends Model
{
    use HasFactory, SoftDeletes, OwnerScope;

    protected $fillable = [
        'attribute_data',
        'slug',
        'status',
        'published_at',
        'owner_id'
    ];

    protected $casts = [
        'attribute_data' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Get translated attribute from attribute_data
     */
    public function translate($key, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        return $this->attribute_data[$key][$locale] ??
               $this->attribute_data[$key][config('app.fallback_locale')] ??
               null;
    }

    /**
     * Get title in current or specified locale
     */
    public function getTitle($locale = null)
    {
        return $this->translate('title', $locale);
    }

    /**
     * Get content in current or specified locale
     */
    public function getContent($locale = null)
    {
        return $this->translate('content', $locale);
    }

    /**
     * Get meta description in current or specified locale
     */
    public function getMetaDescription($locale = null)
    {
        return $this->translate('meta_description', $locale);
    }

    /**
     * Set translated content
     */
    public function setTranslation($key, $locale, $value)
    {
        $attributeData = $this->attribute_data ?? [];
        $attributeData[$key][$locale] = $value;
        $this->attribute_data = $attributeData;
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for draft articles
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get route key name for URL binding
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Owner relationship
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Convert Greek letters to Latin equivalents
     */
    private function convertGreekToLatin($text)
    {
        $greekToLatin = [
            'α' => 'a', 'Α' => 'A',
            'β' => 'b', 'Β' => 'B',
            'γ' => 'g', 'Γ' => 'G',
            'δ' => 'd', 'Δ' => 'D',
            'ε' => 'e', 'Ε' => 'E',
            'ζ' => 'z', 'Ζ' => 'Z',
            'η' => 'i', 'Η' => 'I',
            'θ' => 'th', 'Θ' => 'TH',
            'ι' => 'i', 'Ι' => 'I',
            'κ' => 'k', 'Κ' => 'K',
            'λ' => 'l', 'Λ' => 'L',
            'μ' => 'm', 'Μ' => 'M',
            'ν' => 'n', 'Ν' => 'N',
            'ξ' => 'ks', 'Ξ' => 'KS',
            'ο' => 'o', 'Ο' => 'O',
            'π' => 'p', 'Π' => 'P',
            'ρ' => 'r', 'Ρ' => 'R',
            'σ' => 's', 'Σ' => 'S',
            'ς' => 's',
            'τ' => 't', 'Τ' => 'T',
            'υ' => 'y', 'Υ' => 'Y',
            'φ' => 'f', 'Φ' => 'F',
            'χ' => 'ch', 'Χ' => 'CH',
            'ψ' => 'ps', 'Ψ' => 'PS',
            'ω' => 'o', 'Ω' => 'O'
        ];

        return str_replace(array_keys($greekToLatin), array_values($greekToLatin), $text);
    }

    /**
     * Generate unique slug with Greek to Latin conversion and whitespace removal
     */
    public function generateSlug($title)
    {
        // Remove whitespaces and convert Greek to Latin
        $cleanTitle = trim(preg_replace('/\s+/', ' ', $title));
        $convertedTitle = $this->convertGreekToLatin($cleanTitle);

        $slug = \Str::slug($convertedTitle);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}