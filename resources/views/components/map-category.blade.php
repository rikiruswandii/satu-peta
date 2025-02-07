@props(['category_name', 'category_count'])
<div class="widget-desc">
    <!-- Single Checkbox-->
    <div class="form-check mb-2">
        <input class="form-check-input" id="customCheck1" type="checkbox" value="" checked>
        <label class="form-check-label" for="customCheck1">
            {{ $category_name }}
            <span class="ms-2">({{ $category_count }})</span>
        </label>
    </div>
</div>
