@extends('admin.layout')
@section('section','Property')
@section('page','Popularity Order')
@section('content')

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Manage Popularity Order</h2>
    <p>Drag hotels to set the order they appear in on the public rooms listing. All {{ $hotels->count() }} hotels are shown on this single screen — no pagination, so ordering is always accurate.</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.hotels') }}" class="btn btn-secondary btn-sm">
      <i class="ti ti-arrow-left"></i> Back to Hotel List
    </a>
  </div>
</div>

<div class="card" style="padding:0">
  <div id="szSaveStatus" style="padding:10px 20px;font-size:12px;color:var(--sz-muted);border-bottom:1px solid #eee;display:none"></div>
  <ul id="szPopularityList" style="list-style:none;margin:0;padding:0">
    @forelse($hotels as $hotel)
      @php
        $hc = $hotel->hotel_contents->first();
      @endphp
      <li data-hotel-id="{{ $hotel->id }}" style="display:flex;align-items:center;gap:14px;padding:12px 20px;border-bottom:1px solid #f0f0f0;background:#fff;cursor:default">
        <i class="ti ti-grip-vertical sz-drag-handle" style="cursor:grab;color:#94a3b8;font-size:18px"></i>
        <span class="sz-pop-position" style="width:28px;height:28px;border-radius:8px;background:#f1f5f9;color:#475569;font-weight:700;font-size:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">{{ $loop->iteration }}</span>
        <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:700;color:#94a3b8">
          @if($hotel->logo)
            <img src="{{ asset('assets/img/hotel/logo/'.$hotel->logo) }}" style="width:100%;height:100%;object-fit:cover">
          @else
            {{ strtoupper(substr($hc->title ?? 'H', 0, 2)) }}
          @endif
        </div>
        <div style="flex:1;min-width:0">
          <div style="font-weight:700;font-size:13px;color:#1a1612">{{ $hc->title ?? '—' }}</div>
          <div style="font-size:11px;color:var(--sz-muted)">{{ \Illuminate\Support\Str::limit($hc->address ?? '', 50) }}</div>
        </div>
        <div style="font-size:11px;color:var(--sz-muted)">
          {{ $hotel->vendor_id == 0 ? 'Admin' : (optional($hotel->vendor)->username ?? '—') }}
        </div>
      </li>
    @empty
      <li style="padding:40px;text-align:center;color:var(--sz-muted)">No hotels found.</li>
    @endforelse
  </ul>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
(function() {
  var list = document.getElementById('szPopularityList');
  var status = document.getElementById('szSaveStatus');
  if (!list) return;

  function renumber() {
    list.querySelectorAll('li[data-hotel-id]').forEach(function(li, idx) {
      var badge = li.querySelector('.sz-pop-position');
      if (badge) badge.textContent = idx + 1;
    });
  }

  new Sortable(list, {
    handle: '.sz-drag-handle',
    animation: 150,
    onEnd: function() {
      renumber();
      var items = list.querySelectorAll('li[data-hotel-id]');
      var ids = [];
      var serials = [];
      items.forEach(function(li, idx) {
        ids.push(li.dataset.hotelId);
        serials.push(idx + 1);
      });

      status.style.display = 'block';
      status.textContent = 'Saving order...';

      fetch("{{ route('admin.hotel_management.bulk_sort.hotel') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ids: ids, serial_numbers: serials})
      })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        status.textContent = data.success ? 'Order saved ✓' : 'Save failed — please retry';
        setTimeout(function() { status.style.display = 'none'; }, 2000);
      })
      .catch(function() {
        status.textContent = 'Save failed — please check your connection and retry';
      });
    }
  });
})();
</script>
@endsection