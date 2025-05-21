<div class="filter-tags-container mb-3">
    <!-- @todo Move to Service file or helper -->
    
    @php
        $activeFilters = [];
        $uniqueFilterKeys = [];
        
        // Price filter
        if (request()->has('min_price') || request()->has('max_price')) {
            $min = request('min_price', 0);
            $max = request('max_price', 300);
            $key = 'price_' . $min . '_' . $max;
            if (!in_array($key, $uniqueFilterKeys)) {
                $activeFilters[] = [
                    'label' => "Price: €{$min} - €{$max}",
                    'keys' => ['min_price', 'max_price'],
                    'unique_key' => $key
                ];
                $uniqueFilterKeys[] = $key;
            }
        }
        
        // Rating filter
        if (request('rating', 0) > 0) {
            $key = 'rating_' . request('rating');
            if (!in_array($key, $uniqueFilterKeys)) {
                $activeFilters[] = [
                    'label' => "Rating: ".request('rating')."+ stars",
                    'keys' => ['rating'],
                    'unique_key' => $key
                ];
                $uniqueFilterKeys[] = $key;
            }
        }
        
        // Category filters
        if(isset($filterCategories)) {
            foreach($filterCategories as $category) {
                if(request()->has($category->slug)) {
                    $values = array_unique((array)request($category->slug)); // Remove duplicate values
                    foreach($values as $value) {
                        $option = $category->options->firstWhere('value', $value);
                        if($option) {
                            $key = $category->slug . '_' . $value;
                            if (!in_array($key, $uniqueFilterKeys)) {
                                $activeFilters[] = [
                                    'label' => "{$category->name}: {$option->name}",
                                    'keys' => ["{$category->slug}[]"],
                                    'value' => $value,
                                    'unique_key' => $key
                                ];
                                $uniqueFilterKeys[] = $key;
                            }
                        }
                    }
                }
            }
        }
    @endphp
    
    @foreach($activeFilters as $filter)
        <div class="filter-tag" data-unique-key="{{ $filter['unique_key'] }}">
            <span>{{ $filter['label'] }}</span>
            <button type="button" 
                    class="filter-tag-remove" 
                    data-keys="{{ implode(',', $filter['keys']) }}"
                    @isset($filter['value']) data-value="{{ $filter['value'] }}" @endisset>
                &times;
            </button>
        </div>
    @endforeach
</div>