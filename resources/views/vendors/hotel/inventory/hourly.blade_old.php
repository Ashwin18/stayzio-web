@extends('vendors.layout')
@section('section','Hotels')
@section('page','Hourly Inventory')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Hourly Inventory</h2>
    <p>Set rates, availability, timing and refund policy per date</p>
  </div>
  <div class="page-hdr-actions">
    <button form="inventoryForm" type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save All Changes</button>
  </div>
</div>

{{-- Hotel + Date selector --}}
<div class="card" style="margin-bottom:14px">
  <div class="card-body" style="padding:12px 16px">
    <form action="{{ url()->current() }}" method="GET" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap">
      <div class="fg" style="min-width:220px;flex:1">
        <label class="flabel">Hotel</label>
        <select name="hotel_id" class="fc" onchange="this.form.submit()">
          @foreach($hotels as $hotel)
            <option value="{{ $hotel->id }}" @selected($selected_hotel_id==$hotel->id)>{{ $hotel->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="fg">
        <label class="flabel">From</label>
        <input type="date" name="from_date" class="fc" value="{{ $from_date }}">
      </div>
      <div class="fg">
        <label class="flabel">To</label>
        <input type="date" name="to_date" class="fc" value="{{ $to_date }}">
      </div>
      <div class="fg" style="align-self:flex-end">
        <button type="submit" class="btn btn-secondary"><i class="ti ti-filter"></i> Apply</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <form id="inventoryForm" action="{{ route('vendor.hotel.inventory.update_inline') }}" method="POST">
    @csrf
    <input type="hidden" name="hotel_id" value="{{ $selected_hotel_id }}">
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th rowspan="2" style="vertical-align:middle;min-width:90px">Date</th>
            <th colspan="3" style="text-align:center;border-bottom:1px solid var(--border)">Rate ({{ $settings->base_currency_symbol ?? '₹' }}, GST Exclusive)</th>
            <th rowspan="2" style="vertical-align:middle;width:80px">Rooms</th>
            <th rowspan="2" style="vertical-align:middle;width:70px">Booked</th>
            <th rowspan="2" style="vertical-align:middle;min-width:120px">Status</th>
            <th colspan="2" style="text-align:center;border-bottom:1px solid var(--border)">Check-in Timing</th>
            <th rowspan="2" style="vertical-align:middle;min-width:120px">Refund Policy</th>
          </tr>
          <tr>
            <th>3 hrs</th><th>6 hrs</th><th>Full day</th>
            <th>3 hrs</th><th>6 hrs</th>
          </tr>
        </thead>
        <tbody>
          @foreach($datePeriod as $dateString)
          @php
            $rec = $inventories->get($dateString);
            $d   = \Carbon\Carbon::parse($dateString);
            $isToday = $d->isToday();
            $isPast  = $d->isPast() && !$isToday;
          @endphp
          <tr style="{{ $isPast?'opacity:.5':'' }}">
            <td>
              <div style="font-weight:700;color:{{ $isToday?'var(--red2)':'var(--text)' }};font-size:13px">{{ $d->format('d M') }}</div>
              <div style="font-size:10px;color:var(--muted)">{{ $d->format('D') }}{{ $isToday?' · Today':'' }}</div>
            </td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateString }}][rate_3hrs]" value="{{ $rec?->rate_3hrs ?? 1050 }}" {{ $isPast?'disabled':'' }}></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateString }}][rate_6hrs]" value="{{ $rec?->rate_6hrs ?? 1250 }}" {{ $isPast?'disabled':'' }}></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateString }}][rate_fullday]" value="{{ $rec?->rate_fullday ?? 1800 }}" {{ $isPast?'disabled':'' }}></td>
            <td><input type="number" class="inv-input" name="inventory[{{ $dateString }}][total_rooms]" value="{{ $rec?->total_rooms ?? 8 }}" {{ $isPast?'disabled':'' }}></td>
            <td style="text-align:center;font-weight:700;color:{{ ($rec?->bookings_count??0)>0?'var(--amber)':'var(--muted)' }}">{{ $rec?->bookings_count ?? 0 }}</td>
            <td>
              @php $st = $rec?->status ?? 'Available'; @endphp
              <select name="inventory[{{ $dateString }}][status]" class="inv-input" style="min-width:110px;color:{{ $st=='Available'?'var(--green)':($st=='Sold Out'?'var(--red2)':'var(--amber)') }}" {{ $isPast?'disabled':'' }}>
                <option value="Available" @selected($st=='Available')>Available</option>
                <option value="Sold Out" @selected($st=='Sold Out')>Sold Out</option>
                <option value="Blocked" @selected($st=='Blocked')>Blocked</option>
              </select>
            </td>
            <td><input type="text" class="inv-input" name="inventory[{{ $dateString }}][timing_3hrs]" value="{{ $rec?->timing_3hrs ?? '12AM-11PM' }}" {{ $isPast?'disabled':'' }}></td>
            <td><input type="text" class="inv-input" name="inventory[{{ $dateString }}][timing_6hrs]" value="{{ $rec?->timing_6hrs ?? '12AM-11PM' }}" {{ $isPast?'disabled':'' }}></td>
            <td>
              <select name="inventory[{{ $dateString }}][refund_policy]" class="inv-input" {{ $isPast?'disabled':'' }}>
                <option value="flexible" @selected(($rec?->refund_policy??'flexible')=='flexible')>Flexible</option>
                <option value="non-refundable" @selected(($rec?->refund_policy??'')=='non-refundable')>Non-Refundable</option>
              </select>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div style="padding:14px 16px;border-top:1px solid var(--border);text-align:right">
      <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Inventory Rules</button>
    </div>
  </form>
</div>
@endsection
