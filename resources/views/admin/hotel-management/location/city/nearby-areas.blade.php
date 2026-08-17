@extends('admin.layout')
@section('section','Property / Locations')
@section('page','Nearby Areas')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Manage Nearby Areas</h2><p>Link areas that are close to each other, so filtering by one area also surfaces hotels from its linked nearby areas (selected area's hotels always rank first).</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.location.city',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back to Cities</a>
  </div>
</div>

<div class="card" style="padding:20px">
  <div style="margin-bottom:18px">
    <label style="font-size:12px;font-weight:700;color:var(--sz-muted);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:6px">Select Area</label>
    <select id="szCitySelect" class="fc" style="max-width:320px" onchange="window.location.href='{{ route('admin.hotel_management.location.nearby_areas') }}?language={{ $defaultLang->code }}&city_id=' + this.value">
      @foreach($cities as $c)
        <option value="{{ $c->id }}" {{ $c->id == $selectedCityId ? 'selected' : '' }}>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  @if($selectedCityId)
    <div id="szSaveStatus" style="font-size:12px;color:var(--sz-muted);margin-bottom:10px;display:none"></div>
    <label style="font-size:12px;font-weight:700;color:var(--sz-muted);text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:10px">
      Areas nearby to {{ optional($cities->firstWhere('id', $selectedCityId))->name }}
    </label>
    <div style="display:flex;flex-wrap:wrap;gap:10px" id="szNearbyList">
      @foreach($cities as $c)
        @if($c->id != $selectedCityId)
          <label style="display:flex;align-items:center;gap:8px;padding:8px 14px;border:1.5px solid #e8e2d9;border-radius:20px;font-size:13px;cursor:pointer;user-select:none" class="sz-nearby-chip">
            <input type="checkbox" value="{{ $c->id }}" {{ in_array($c->id, $linkedIds) ? 'checked' : '' }} style="accent-color:#e31e24">
            {{ $c->name }}
          </label>
        @endif
      @endforeach
    </div>
    <button type="button" class="btn btn-primary" style="margin-top:20px" onclick="szSaveNearby()">
      <i class="ti ti-device-floppy"></i> Save Nearby Areas
    </button>
  @else
    <p style="color:var(--sz-muted)">No areas available yet. Add cities first.</p>
  @endif
</div>

<script>
function szSaveNearby() {
  var checked = document.querySelectorAll('#szNearbyList input[type=checkbox]:checked');
  var ids = Array.from(checked).map(function(cb) { return cb.value; });
  var status = document.getElementById('szSaveStatus');
  status.style.display = 'block';
  status.textContent = 'Saving...';

  fetch("{{ route('admin.hotel_management.location.nearby_areas.save') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ city_id: {{ $selectedCityId }}, nearby_city_ids: ids })
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    status.textContent = data.status === 'success' ? 'Saved ✓' : 'Save failed';
    setTimeout(function() { status.style.display = 'none'; }, 2000);
  })
  .catch(function() {
    status.textContent = 'Save failed — check your connection';
  });
}
</script>
@endsection