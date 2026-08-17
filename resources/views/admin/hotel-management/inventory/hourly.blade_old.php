@extends('admin.layout')
@section('section','Property')
@section('page','Hourly Inventory')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Hourly Inventory</h2><p>Set rates, capacity, timing and commission per date</p></div>
  <div class="page-hdr-actions">
    <button form="inventoryForm" type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save All</button>
  </div>
</div>

<div class="card mb-14">
  <div class="card-body" style="padding:12px 16px">
    <form action="{{ url()->current() }}" method="GET" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
      <div class="fg" style="min-width:200px">
        <label class="flabel">Hotel</label>
        <select name="hotel_id" class="fc" onchange="this.form.submit()">
          @foreach($hotels as $h)<option value="{{ $h->id }}" @selected($selected_hotel_id==$h->id)>{{ $h->title }}</option>@endforeach
        </select>
      </div>
      <div class="fg"><label class="flabel">From</label><input type="date" name="from_date" value="{{ $from_date }}" class="fc"></div>
      <div class="fg"><label class="flabel">To</label><input type="date" name="to_date" value="{{ $to_date }}" class="fc"></div>
      <div class="fg" style="align-self:flex-end"><button type="submit" class="btn btn-secondary"><i class="ti ti-filter"></i> Apply</button></div>
    </form>
  </div>
</div>

<div class="card">
  <form id="inventoryForm" action="{{ route('admin.hotel_management.inventory.update_inline') }}" method="POST">
    @csrf
    <input type="hidden" name="hotel_id" value="{{ $selected_hotel_id }}">
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th rowspan="2" style="vertical-align:middle">Date</th>
            <th colspan="3" style="text-align:center;border-bottom:1px solid var(--border)">Rate (₹, GST exclusive)</th>
            <th rowspan="2" style="vertical-align:middle">Rooms</th>
            <th rowspan="2" style="vertical-align:middle">Bookings</th>
            <th rowspan="2" style="vertical-align:middle">Status</th>
            <th colspan="2" style="text-align:center;border-bottom:1px solid var(--border)">Timing (check-in range)</th>
            <th colspan="3" style="text-align:center;border-bottom:1px solid var(--border)">Commission (%)</th>
                      </tr>
          <tr>
            <th>3hr</th><th>6hr</th><th>Full day</th>
            <th>3hr</th><th>6hr</th>
            <th>3hr</th><th>6hr</th><th>Full</th>
          </tr>
        </thead>
        <tbody>
          @foreach($datePeriod as $dateStr)
          @php $rec = $inventories->get($dateStr); $d = \Carbon\Carbon::parse($dateStr); @endphp
          <tr>
            <td style="white-space:nowrap;font-weight:600;color:{{ $d->isToday() ? 'var(--red2)' : 'var(--text)' }}">
              {{ $d->format('d M') }}<span class="td-sub" style="display:block">{{ $d->format('D') }}</span>
            </td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][rate_3hrs]" value="{{ $rec?->rate_3hrs ?? 1050 }}"></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][rate_6hrs]" value="{{ $rec?->rate_6hrs ?? 1250 }}"></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][rate_fullday]" value="{{ $rec?->rate_fullday ?? 2500 }}"></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][total_rooms]" value="{{ $rec?->total_rooms ?? 5 }}"></td>
            <td style="text-align:center;font-weight:600;color:var(--muted)">{{ $rec?->bookings_count ?? 0 }}</td>
            <td>
              <select class="inv-status-sel" name="inventory[{{ $dateStr }}][status]">
                <option value="available" {{ ($rec?->status??'available')=='available'?'selected':'' }}>Available</option>
                <option value="unavailable" {{ ($rec?->status??'')=='unavailable'?'selected':'' }}>Unavailable</option>
                <option value="holiday" {{ ($rec?->status??'')=='holiday'?'selected':'' }}>Holiday</option>
              </select>
            </td>
            <td><input type="text" class="inv-input" name="inventory[{{ $dateStr }}][timing_3hrs]" value="{{ $rec?->timing_3hrs ?? '06:00-18:00' }}" placeholder="06:00-18:00"></td>
            <td><input type="text" class="inv-input" name="inventory[{{ $dateStr }}][timing_6hrs]" value="{{ $rec?->timing_6hrs ?? '06:00-18:00' }}" placeholder="06:00-18:00"></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][commission_3hrs]" value="{{ $rec?->commission_3hrs ?? 10 }}" step="0.1"></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][commission_6hrs]" value="{{ $rec?->commission_6hrs ?? 10 }}" step="0.1"></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateStr }}][commission_fullday]" value="{{ $rec?->commission_fullday ?? 10 }}" step="0.1"></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div style="padding:14px 16px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px">
      <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save All Changes</button>
    </div>
  </form>
</div>
@endsection
