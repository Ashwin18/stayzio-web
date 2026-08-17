@php
  $filterCategories = \Illuminate\Support\Facades\DB::table("room_categories")
    ->where("status", 1)
    ->where("language_id", \Illuminate\Support\Facades\DB::table("languages")->where("is_default", 1)->value("id") ?? 20)
    ->orderBy("serial_number")
    ->get();
  $selCats = request("filter_category", []);
  if (!is_array($selCats)) $selCats = [$selCats];
  $selCats = array_map('intval', $selCats);
@endphp

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-sliders-h" style="color:#e31e24;margin-right:.4rem;font-size:.9rem;"></i>
            {{ __('Filters') }}</h3>
        <button class="clear-all" type="button" onclick="window.location.href='{{ route('frontend.rooms') }}'">{{ __('Clear All') }}</button>
    </div>

    <form method="GET" action="{{ route('frontend.rooms') }}" id="sidebarForm">

    {{-- Stay Duration --}}
    <div class="filter-section">
        <div class="filter-title" onclick="toggleFilter(this)">{{ __('Stay Duration') }} <i class="fas fa-chevron-down"></i></div>
        <div class="chip-group">
            <span class="chip {{ request('hour') == '' ? 'selected' : '' }}" onclick="selectChip(this, 'filter_hour', '')">{{ __('All') }}</span>
            <span class="chip {{ request('hour') == '3' ? 'selected' : '' }}" onclick="selectChip(this, 'filter_hour', '3')">3 {{ __('Hours') }}</span>
            <span class="chip {{ request('hour') == '6' ? 'selected' : '' }}" onclick="selectChip(this, 'filter_hour', '6')">6 {{ __('Hours') }}</span>
            <span class="chip {{ request('hour') == '24' ? 'selected' : '' }}" onclick="selectChip(this, 'filter_hour', '24')">{{ __('Full Day') }}</span>
        </div>
        <input type="hidden" name="hour" id="filter_hour" value="{{ request('hour') }}">
    </div>

    {{-- Area / Locality --}}
    <div class="filter-section">
        <div class="filter-title" onclick="toggleFilter(this)">{{ __('Area / Locality') }} <i class="fas fa-chevron-down"></i></div>
        <div class="chip-group">
            <span class="chip {{ request('filter_city') == '' ? 'selected' : '' }}" onclick="selectChip(this, 'filter_city_val', '')">{{ __('All') }}</span>
            @foreach($locations ?? [] as $location)
            <span class="chip {{ request('filter_city') == $location->id ? 'selected' : '' }}" onclick="selectChip(this, 'filter_city_val', '{{ $location->id }}')">{{ $location->name }}</span>
            @endforeach
        </div>
        <input type="hidden" name="filter_city" id="filter_city_val" value="{{ request('filter_city') }}">
    </div>

    {{-- Price Range --}}
    <div class="filter-section">
        <div class="filter-title" onclick="toggleFilter(this)">{{ __('Price Range') }} <i class="fas fa-chevron-down"></i></div>
        <div class="price-range-wrap">
            <div class="price-labels">
                <span>₹<span id="priceMin">{{ request('min_price', 500) }}</span></span>
                <span>₹<span id="priceMax">{{ number_format(request('max_price', 5000)) }}</span></span>
            </div>
            <input type="hidden" name="min_price" value="{{ request('min_price', 500) }}">
            <input type="range" name="max_price" min="500" max="10000" value="{{ request('max_price', 5000) }}" step="100"
                oninput="document.getElementById('priceMax').textContent = Number(this.value).toLocaleString('en-IN')"
                style="width:100%; margin-bottom:.4rem;" onchange="document.getElementById('sidebarForm').submit()">
        </div>
    </div>

    {{-- Star Rating --}}
    <div class="filter-section">
        <div class="filter-title" onclick="toggleFilter(this)">{{ __('Star Rating') }} <i class="fas fa-chevron-down"></i></div>
        <div class="chip-group">
            <span class="chip {{ request('ratings') == '' ? 'selected' : '' }}" onclick="selectChip(this, 'filter_rating', '')">{{ __('Any') }}</span>
            @for($i = 3; $i <= 5; $i++)
            <span class="chip {{ request('ratings') == $i ? 'selected' : '' }}" onclick="selectChip(this, 'filter_rating', '{{ $i }}')">
                <span style="color:#f59e0b;">{!! str_repeat('★', $i) !!}</span> {{ $i }}
            </span>
            @endfor
        </div>
        <input type="hidden" name="ratings" id="filter_rating" value="{{ request('ratings') }}">
    </div>

    {{-- Guest Type (multi-select checkboxes) --}}
    <div class="filter-section">
        <div class="filter-title" onclick="toggleFilter(this)">{{ __('Guest Type') }} <i class="fas fa-chevron-down"></i></div>
        <div class="check-list">
            @foreach($filterCategories as $cat)
            <label class="check-item">
                <input type="checkbox" name="filter_category[]" value="{{ $cat->id }}" {{ in_array($cat->id, $selCats) ? 'checked' : '' }}>
                <span>{{ $cat->name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Couple Friendly --}}
    <div class="filter-section">
        <div class="filter-title" onclick="toggleFilter(this)">{{ __('Preferences') }} <i class="fas fa-chevron-down"></i></div>
        <div class="check-list">
            <label class="check-item">
                <input type="checkbox" name="couple_friendly" value="1" {{ request('couple_friendly') == '1' ? 'checked' : '' }}>
                <span>{{ __('Couple Friendly') }}</span>
            </label>
        </div>
    </div>

    {{-- Apply button removed - filters auto-apply --}}

    </form>
</aside>

<style>
.sidebar{background:#fff;border-radius:14px;border:1px solid #e8e2d9;padding:0;overflow:hidden;position:sticky;top:80px;width:260px;flex-shrink:0}
.sidebar-header{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #f0ebe4}
.sidebar-header h3{font-size:14px;font-weight:800;color:#1a1612;margin:0}
.clear-all{background:none;border:none;color:#e31e24;font-size:11px;font-weight:700;cursor:pointer}
.filter-section{padding:12px 16px;border-bottom:1px solid #f0ebe4}
.filter-section:last-of-type{border-bottom:none}
.filter-title{font-size:12px;font-weight:700;color:#1a1612;cursor:pointer;display:flex;align-items:center;justify-content:space-between;margin-bottom:8px}
.filter-title i{font-size:10px;color:#6b6560;transition:transform .2s}
.filter-title.collapsed i{transform:rotate(-90deg)}
.chip-group{display:flex;flex-wrap:wrap;gap:6px}
.chip{padding:5px 12px;border:1.5px solid #e8e2d9;border-radius:20px;font-size:11px;font-weight:600;color:#6b6560;cursor:pointer;background:#f8f6f1;transition:.15s;user-select:none}
.chip:hover,.chip.selected{border-color:#e31e24;color:#e31e24;background:rgba(227,30,36,.05)}
.price-range-wrap{padding:4px 0}
.price-labels{display:flex;justify-content:space-between;font-size:12px;font-weight:700;color:#1a1612;margin-bottom:6px}
input[type="range"]{-webkit-appearance:none;height:4px;background:#e8e2d9;border-radius:4px;outline:none}
input[type="range"]::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;background:#e31e24;border-radius:50%;cursor:pointer}
.check-list{display:flex;flex-direction:column;gap:2px}
.check-item{display:flex;align-items:center;gap:8px;padding:5px 0;font-size:12px;color:#1a1612;cursor:pointer}
.check-item input{accent-color:#e31e24;width:15px;height:15px}

.main-layout{display:flex;gap:20px;max-width:1360px;margin:0 auto;padding:20px 1rem}
.results-panel{flex:1;min-width:0}
@media(max-width:992px){
  .sidebar{position:fixed;top:0;left:-300px;width:280px;height:100vh;z-index:1000;border-radius:0;overflow-y:auto;transition:left .3s;box-shadow:4px 0 20px rgba(0,0,0,.15)}
  .sidebar.open{left:0}
  .main-layout{flex-direction:column}
}
</style>

<script>
function toggleFilter(el) {
    el.classList.toggle('collapsed');
    var content = el.nextElementSibling;
    if (content) content.style.display = content.style.display === 'none' ? '' : 'none';
}

function selectChip(el, hiddenId, value) {
    var group = el.closest('.chip-group');
    group.querySelectorAll('.chip').forEach(function(c) { c.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById(hiddenId).value = value;
    document.getElementById('sidebarForm').submit();
}
// Auto-submit on checkbox change
document.querySelectorAll('#sidebarForm input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        document.getElementById('sidebarForm').submit();
    });
});
// Auto-submit on checkbox change
document.querySelectorAll('#sidebarForm input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        document.getElementById('sidebarForm').submit();
    });
});
// Auto-submit on checkbox change
document.querySelectorAll('#sidebarForm input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        document.getElementById('sidebarForm').submit();
    });
});
// Auto-submit on checkbox change
document.querySelectorAll('#sidebarForm input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        document.getElementById('sidebarForm').submit();
    });
});
</script>