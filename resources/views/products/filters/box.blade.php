<div class="d-flex flex-wrap gap-2 mb-4" id="occasion-badges">
  @foreach($category->options as $option)
  <label class="position-relative filter-occasion">
    <input type="checkbox" name="{{ $category->slug }}[]" value="{{ $option->value }}"
      id="filter-{{ $category->slug }}-{{ $option->id }}" class="position-absolute opacity-0"
      {{ in_array($option->value, (array)request($category->slug, [])) ? 'checked' : '' }}>
    <span
      class="search-selector d-inline-flex align-items-center border border-1 rounded-pill px-3 py-1 fs-14 {{ in_array($option->value, (array)request($category->slug, [])) ? 'selection-active' : '' }}">
      @if(isset($option->icon))
      <i class="fa {{ $option->icon }} me-2"></i>
      @endif
      <span>{{ $option->name }}</span>
    </span>
  </label>
  @endforeach
</div>