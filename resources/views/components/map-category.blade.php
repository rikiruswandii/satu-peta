@props(['category_name', 'category_count', 'category_id'])
<div class="widget-desc">
    <div class="form-check mb-2">
        <input class="form-check-input regional-agency-checkbox" 
               id="customCheck{{ $category_id }}" 
               type="checkbox" 
               name="regional_agencies[]"
               value="{{ $category_id }}"
               {{ in_array($category_id, request('regional_agencies', [])) ? 'checked' : '' }}>
        <label class="form-check-label" for="customCheck{{ $category_id }}">
            {{ $category_name }}
            <span class="ms-2">({{ $category_count }})</span>
        </label>
    </div>
</div>