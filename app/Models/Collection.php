<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection as BaseCollection;
use Lunar\Models\Collection as LunarCollection;

class Collection extends LunarCollection
{
    /**
     * Eager loading for menu display
     */
    protected static array $menuWith = [
        'defaultUrl',
        'children.defaultUrl',
        'parent.defaultUrl'
    ];

    /**
     * Scope for root collections (no parent)
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for active/visible collections
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Parent relationship
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'parent_id');
    }

    /**
     * Children relationship
     */
    public function children(): HasMany
    {
        return $this->hasMany(Collection::class, 'parent_id')
            ->orderBy('position');
    }

    /**
     * Get all collections formatted for menu display
     */
    public static function getMenuCollections(): BaseCollection
    {
        return static::with(static::$menuWith)
            ->active()
            ->root()
            ->orderBy('position')
            ->get();
    }

    /**
     * Get specific collections by IDs for menu
     */
    public static function getSelectedForMenu(array $ids): BaseCollection
    {
        return static::with(static::$menuWith)
            ->active()
            ->whereIn('id', $ids)
            ->orderBy('position')
            ->get();
    }

    /**
     * Get the full URL path including parent collections
     */
    public function getNestedUrl(): string
    {
        $slugs = $this->getSlugPath();

        return route('collections.show', implode('/', $slugs));
    }

    /**
     * Get array of slugs from parent to current collection
     */
    protected function getSlugPath(): array
    {
        $slugs = [];
        $collection = $this;

        while ($collection && $collection->defaultUrl) {
            $slugs[] = $collection->defaultUrl->slug;
            $collection = $collection->parent;
        }

        return array_reverse($slugs);
    }

    /**
     * Check if collection has children
     */
    public function hasChildren(): bool
    {
        return $this->children->isNotEmpty();
    }

    /**
     * Get breadcrumbs for collection
     */
    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $collection = $this;

        while ($collection) {
            $breadcrumbs[] = [
                'name' => $collection->translateAttribute('name'),
                'url' => $collection->getNestedUrl()
            ];
            $collection = $collection->parent;
        }

        return array_reverse($breadcrumbs);
    }

    /**
     * Render the collection and its children as a nested menu
     */
    public static function renderMenu(array $ids = null): string
    {
        $collections = $ids 
            ? static::getSelectedForMenu($ids)
            : static::getMenuCollections();

        return static::buildMenuHtml($collections);
    }

    /**
     * Build HTML for the menu recursively
     */
    protected static function buildMenuHtml(BaseCollection $collections, int $depth = 0): string
    {
        if ($collections->isEmpty()) {
            return '';
        }

        $ulClass = $depth === 0 ? 'menu' : 'submenu';
        $html = "<ul class=\"{$ulClass}\">";

        foreach ($collections as $collection) {
            $hasChildren = $collection->hasChildren();
            $liClass = $hasChildren ? 'has-children' : '';
            $html .= "<li class=\"{$liClass}\">";
            $html .= '<a href="' . e($collection->getNestedUrl()) . '">';
            $html .= e($collection->translateAttribute('name'));
            $html .= '</a>';
            
            if ($hasChildren) {
                $html .= static::buildMenuHtml($collection->children, $depth + 1);
            }
            
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Get menu data as array (for JSON APIs)
     */
    public static function getMenuArray(array $ids = null): array
    {
        $collections = $ids 
            ? static::getSelectedForMenu($ids)
            : static::getMenuCollections();

        return static::buildMenuArray($collections);
    }

    /**
     * Build menu array recursively
     */
    protected static function buildMenuArray(BaseCollection $collections): array
    {
        return $collections->map(function ($collection) {
            return [
                'id' => $collection->id,
                'name' => $collection->translateAttribute('name'),
                'url' => $collection->getNestedUrl(),
                'children' => $collection->hasChildren() 
                    ? static::buildMenuArray($collection->children) 
                    : []
            ];
        })->toArray();
    }

    /**
     * Check if current collection is active
     */
    public function isActive(): bool
    {
        $currentPath = trim(parse_url(url()->current(), PHP_URL_PATH), '/');
        $collectionPath = trim(parse_url($this->getNestedUrl(), PHP_URL_PATH), '/');
        
        return $currentPath === $collectionPath || str_starts_with($currentPath, $collectionPath.'/');
    }

    /**
     * Get menu item CSS classes
     */
    public function getMenuClasses(): string
    {
        $classes = [];
        
        if ($this->isActive()) {
            $classes[] = 'active';
        }
        
        if ($this->hasChildren()) {
            $classes[] = 'has-children';
        }
        
        return implode(' ', $classes);
    }
}