@extends('vendors.layout')
@section('section','Hotels')
@section('page','Holidays')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Holidays</h2>
    <p>Blocked dates — no bookings accepted on these days</p>
  </div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('vendor.global.holiday.bluk-destroy') }}">
      <i class="ti ti-trash"></i> Delete Selected
    </button>
    <button onclick="document.getElementById('addHolidayModal').style.display='flex'" class="btn btn-primary">
      <i class="ti ti-plus"></i> Add Holiday
    </button>
  </div>
</div>

<div class="card" style="margin-bottom:14px">
  <form action="{{ route('vendor.hotel_management.hotel.holiday') }}" method="GET">
    <div class="filters">
      <select name="hotel_id" class="fc" onchange="this.form.submit()">
        <option value="ALL">All Hotels</option>
        @foreach($hotels as $hotel)
          <option value="{{ $hotel->id }}" @selected(request('hotel_id') == $hotel->id)>{{ $hotel->title }}</option>
        @endforeach
      </select>
      <input type="hidden" name="language" value="{{ request('language') }}">
      <div class="filter-spacer"></div>
      <a href="{{ route('vendor.hotel_management.hotel.holiday') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
        <th>Hotel</th><th>Date</th><th>Day</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($globalHoliday as $holiday)
        <tr>
          <td><input type="checkbox" class="bulk-check" data-val="{{ $holiday->id }}"></td>
          <td>
            <a href="{{ route('frontend.hotel.details',['slug'=>$holiday->slug,'id'=>$holiday->hotel_id]) }}" target="_blank" style="color:var(--blue);text-decoration:none;font-weight:500">
              {{ Str::limit($holiday->title ?? '—', 50) }}
            </a>
          </td>
          <td class="fw6">{{ \Carbon\Carbon::parse($holiday->date)->format('d M Y') }}</td>
          <td class="td-muted">{{ \Carbon\Carbon::parse($holiday->date)->format('l') }}</td>
          <td>
            <form action="{{ route('vendor.hotel_management.hotel.holiday.delete',['id'=>$holiday->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Remove this holiday?')">
              @csrf
              <button class="btn btn-danger btn-xs" title="Delete"><i class="ti ti-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:48px;color:var(--muted)">
          <i class="ti ti-calendar-off" style="font-size:36px;display:block;margin-bottom:10px"></i>
          No holidays set. Add blocked dates for your hotels.
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add Holiday Modal --}}
<div id="addHolidayModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:440px;max-width:95vw">
    <div style="padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-size:14px;font-weight:700;color:var(--text)">Add Holiday</span>
      <button onclick="document.getElementById('addHolidayModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px;line-height:1">×</button>
    </div>
    <form action="{{ route('vendor.hotel_management.hotel.holiday.store') }}" method="POST">
      @csrf
      <div style="padding:18px">
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Hotel *</label>
          <select name="hotel_id" class="fc" required>
            <option value="">Select Hotel</option>
            @foreach($hotels as $hotel)
              <option value="{{ $hotel->id }}">{{ $hotel->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="fg" style="margin-bottom:18px">
          <label class="flabel">Date *</label>
          <input type="date" name="date" class="fc" required min="{{ date('Y-m-d') }}">
          <div class="fc-hint">Customers cannot book rooms on this date</div>
        </div>
        <input type="hidden" name="language" value="{{ $defaultLang->code }}">
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Save Holiday</button>
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('addHolidayModal').style.display='none'">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
