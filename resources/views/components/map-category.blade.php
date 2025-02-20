@props(['category_name', 'category_count', 'category_id'])
<div class="widget-desc">
    <div class="form-check mb-2">
        <input class="form-check-input regional-agency-checkbox" id="customCheck{{ $category_id }}" type="checkbox"
            name="regional_agencies_checkbox[]" value="{{ $category_id }}"
            {{ in_array($category_id, (array) request('regional_agencies_checkbox', [])) ? 'checked' : '' }}>
        <label class="form-check-label text-success fw-semibold" for="customCheck{{ $category_id }}">
            {{ $category_name }}
            <span class="ms-2 text-warning">({{ $category_count }})</span>
        </label>
    </div>
</div>
