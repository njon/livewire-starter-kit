@foreach($category->options as $option)
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="{{ $category->slug }}[]" value="{{ $option->value }}"
        id="filter-{{ $category->slug }}-{{ $option->id }}"
        {{ in_array($option->value, (array)request($category->slug, [])) ? 'checked' : '' }}>
    <label class="form-check-label fs-14" for="filter-{{ $category->slug }}-{{ $option->id }}">
        {{ __($option->name) }}
    </label>
</div>
@endforeach