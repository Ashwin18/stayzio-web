@extends('admin.layout')
@section('section','Bookings')
@section('page','Pricing Setup')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Pricing Setup</h2><p>Configure booking hour slots</p></div>
  <div class="page-hdr-actions">
    <button onclick="document.getElementById('addHourModal').style.display='flex'" class="btn btn-primary">
      <i class="ti ti-plus"></i> Add Booking Hour
    </button>
  </div>
</div>

<div class="two-col-eq">
  <div class="card">
    <div class="card-hdr"><div class="card-title"><i class="ti ti-clock"></i>  Booking Hour Slots</div></div>
    <div class="tbl-wrap">
      <table>
        <thead><tr><th>#</th><th>Hours</th><th>Serial</th><th>Actions</th></tr></thead>
        <tbody>
          @forelse($hours as $bh)
          <tr>
            <td class="td-muted">{{ $bh->id }}</td>
            <td>
              @if($bh->hour <= 3)<span class="slot s3">{{ $bh->hour }}hr</span>
              @elseif($bh->hour <= 6)<span class="slot s6">{{ $bh->hour }}hr</span>
              @elseif($bh->hour <= 12)<span class="slot s12">{{ $bh->hour }}hr</span>
              @else<span class="slot sf">{{ $bh->hour }}hr</span>@endif
            </td>
            <td class="td-muted">{{ $bh->serial_number }}</td>
            <td>
              <div style="display:flex;gap:5px">
                <button onclick="openBhEdit(this)"
                   data-id="{{ $bh->id }}" data-hour="{{ $bh->hour }}" data-serial="{{ $bh->serial_number }}"
                   class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></button>
                <form action="{{ route('admin.room_management.delete_booking_hour', $bh->id) }}" method="POST"
                      style="display:inline" onsubmit="return confirm('Delete?')">
                  @csrf
                  <button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--muted)">No hour slots configured</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-hdr"><div class="card-title"><i class="ti ti-info-circle"></i>  Slot Guide</div></div>
    <div class="card-body">
      <div style="background:var(--navy3);border-radius:8px;padding:12px;margin-bottom:10px">
        <div class="fw6 fs12" style="margin-bottom:6px">How booking hours work</div>
        <div class="fs11 text-muted" style="line-height:1.7">
          Each slot here becomes a selectable duration when a customer books a room.<br>
          e.g. Add <strong>3</strong> for a 3-hour slot, <strong>6</strong> for 6 hours, <strong>24</strong> for full day.<br>
          Pricing per slot is set in <strong>Hourly Inventory</strong> per hotel.
        </div>
      </div>
      <div class="stat-row"><span class="stat-lbl">Total slots</span><span class="stat-val">{{ count($hours) }}</span></div>
      <div class="stat-row">
        <span class="stat-lbl">Active slots</span>
        <span class="stat-val text-green">
          @foreach($hours as $bh)<span class="slot {{ $bh->hour<=3?'s3':($bh->hour<=6?'s6':($bh->hour<=12?'s12':'sf')) }}" style="margin-right:4px">{{ $bh->hour }}hr</span>@endforeach
        </span>
      </div>
      <div style="margin-top:12px">
        <a href="{{ route('admin.room_management.tax_amount', ['language' => $defaultLang->code]) }}"
           class="btn btn-secondary btn-sm btn-block">
          <i class="ti ti-percentage"></i> Configure Tax & Commission →
        </a>
      </div>
    </div>
  </div>
</div>

{{-- Add Hour Modal --}}
<div id="addHourModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:380px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-weight:600">Add Booking Hour Slot</span>
      <button onclick="document.getElementById('addHourModal').style.display='none'"
              style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('admin.room_management.store_booking_hour') }}" method="POST">
      @csrf
      <div style="padding:16px">
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Hours *</label>
          <input type="number" name="hour" class="fc" placeholder="e.g. 3" min="1" required>
          <div class="fc-hint">Enter the number of hours for this slot (e.g. 3, 6, 12, 24)</div>
        </div>
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Serial Number</label>
          <input type="number" name="serial_number" class="fc" value="{{ count($hours) + 1 }}" min="1">
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Save Slot</button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Hour Modal --}}
<div id="editHourModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:380px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Booking Hour</span>
      <button onclick="document.getElementById('editHourModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.room_management.update_booking_hour') }}" method="POST">
      @csrf
      <div style="padding:16px">
        <input type="hidden" name="id" id="bh_edit_id">
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Hour (1–24) *</label>
          <input type="number" name="hour" id="bh_edit_hour" class="fc" min="1" max="24" required>
        </div>
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Serial Number *</label>
          <input type="number" name="serial_number" id="bh_edit_serial" class="fc" required>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Update</button>
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('editHourModal').style.display='none'">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
(function() {
  document.addEventListener("submit", function(e) {
    var form = e.target;
    if (!form.closest || !form.closest("[id$=Modal]")) return;
    e.preventDefault();
    var btn = form.querySelector("button[type=submit]");
    var origHtml = btn ? btn.innerHTML : "";
    if (btn) { btn.disabled = true; btn.innerHTML = "Saving..."; }
    fetch(form.action, {
      method: "POST",
      body: new FormData(form),
      headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.status === "success") {
        window.location.reload();
      } else {
        var errors = data.errors || {};
        var msg = Object.keys(errors).map(function(k) {
          return Array.isArray(errors[k]) ? errors[k][0] : errors[k];
        }).join(", ");
        alert(msg || "An error occurred");
        if (btn) { btn.disabled = false; btn.innerHTML = origHtml; }
      }
    })
    .catch(function() {
      if (btn) { btn.disabled = false; btn.innerHTML = origHtml; }
    });
  }, true);
})();
</script>
@endsection