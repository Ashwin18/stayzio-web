@extends('vendors.layout')
@section('section','Hotels')
@section('page','Hourly Inventory')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Hourly Inventory</h2>
    <p>Set rates, availability, timing and refund policy per date</p>
  </div>
  <div style="padding:8px 12px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:8px;font-size:12px;color:var(--amber);display:flex;align-items:center;gap:8px">
    <i class="ti ti-info-circle"></i> <span>Commission % and Net Amount are set by admin. Your payout = Rate − Commission.</span>
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

<div class="card" style="margin-bottom:14px;background:rgba(59,130,246,.04);border-color:rgba(59,130,246,.2)">
  <div class="card-body" style="padding:14px 16px">
    <div style="font-weight:700;font-size:13px;margin-bottom:10px;display:flex;align-items:center;gap:6px"><i class="ti ti-wand"></i> Bulk Fill</div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
      <div class="fg">
        <label class="flabel">Field</label>
        <select id="bulkField" class="fc" onchange="updateBulkValueInput()">
          <option value="rate_3hrs">Rate - 3 hrs</option>
          <option value="rate_6hrs">Rate - 6 hrs</option>
          <option value="rate_fullday">Rate - Full day</option>
          <option value="total_rooms">Total rooms</option>
          <option value="status">Block day status</option>
          <option value="manual_status_3hrs">Duration override - 3 hrs</option>
          <option value="manual_status_6hrs">Duration override - 6 hrs</option>
          <option value="manual_status_fullday">Duration override - Full day</option>
          <option value="timing_3hrs">Check-in timing - 3 hrs</option>
          <option value="timing_6hrs">Check-in timing - 6 hrs</option>
        </select>
      </div>
      <div class="fg" id="bulkValueWrap" style="min-width:160px">
        <label class="flabel">Value</label>
        <input type="number" id="bulkValue" class="fc" placeholder="Enter value">
      </div>
      <div class="fg">
        <label class="flabel">Apply to</label>
        <select id="bulkDays" class="fc">
          <option value="all">All days in range</option>
          <option value="weekday">Weekdays only (Mon-Fri)</option>
          <option value="weekend">Weekends only (Sat-Sun)</option>
        </select>
      </div>
      <div class="fg">
        <button type="button" class="btn btn-secondary" onclick="applyBulkFill()"><i class="ti ti-wand"></i> Apply to table</button>
      </div>
    </div>
    <div style="font-size:11px;color:var(--muted);margin-top:8px">This fills in the fields below - nothing saves until you click "Save Inventory Rules".</div>
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
            <th rowspan="2" style="vertical-align:middle;min-width:120px">Block Day</th>
            <th colspan="3" style="text-align:center;border-bottom:1px solid var(--border)">Duration Override <span style="font-weight:400;font-size:9px">(blank=auto)</span></th>
            <th colspan="2" style="text-align:center;border-bottom:1px solid var(--border)">Check-in Timing</th>
                        <th style="text-align:center;background:rgba(245,158,11,.06);color:var(--amber);font-size:10px;white-space:nowrap">3hr %</th>
            <th style="text-align:center;background:rgba(245,158,11,.06);color:var(--amber);font-size:10px;white-space:nowrap">6hr %</th>
            <th style="text-align:center;background:rgba(245,158,11,.06);color:var(--amber);font-size:10px;white-space:nowrap">Full %</th>
            <th style="text-align:center;background:rgba(16,185,129,.06);color:var(--green);font-size:10px;white-space:nowrap">Net 3hr</th>
            <th style="text-align:center;background:rgba(16,185,129,.06);color:var(--green);font-size:10px;white-space:nowrap">Net 6hr</th>
            <th style="text-align:center;background:rgba(16,185,129,.06);color:var(--green);font-size:10px;white-space:nowrap">Net Full</th>
          </tr>
          <tr>
            <th>3 hrs</th><th>6 hrs</th><th>Full day</th>
            <th>3 hrs</th><th>6 hrs</th><th>Full day</th>
            <th>3 hrs</th><th>6 hrs</th>
            <th colspan="3" style="text-align:center;background:rgba(245,158,11,.08);color:var(--amber);font-size:10px">Admin Commission %</th>
            <th colspan="3" style="text-align:center;background:rgba(16,185,129,.08);color:var(--green);font-size:10px">Net You Receive</th>
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
          <tr style="{{ $isPast?'opacity:.5':'' }}" data-date="{{ $dateString }}" data-dow="{{ $d->dayOfWeek }}" data-past="{{ $isPast?1:0 }}">
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
              <select name="inventory[{{ $dateString }}][status]" class="inv-input" style="min-width:110px;color:{{ $st=='Available'?'var(--green)':'var(--amber)' }}" {{ $isPast?'disabled':'' }}>
                <option value="Available" @selected($st=='Available')>Available</option>
                <option value="Blocked" @selected($st=='Blocked')>Blocked (Holiday)</option>
              </select>
            </td>
            @php
              $ms3 = $rec?->manual_status_3hrs ?? '';
              $ms6 = $rec?->manual_status_6hrs ?? '';
              $msf = $rec?->manual_status_fullday ?? '';
              $msColor = fn($v) => $v=='Sold Out' ? 'var(--red2)' : ($v=='Available' ? 'var(--green)' : 'var(--muted)');
            @endphp
            <td>
              <select name="inventory[{{ $dateString }}][manual_status_3hrs]" class="inv-input" style="min-width:88px;color:{{ $msColor($ms3) }}" {{ $isPast?'disabled':'' }}>
                <option value="" @selected($ms3=='')>Auto</option>
                <option value="Available" @selected($ms3=='Available')>Available</option>
                <option value="Sold Out" @selected($ms3=='Sold Out')>Sold Out</option>
              </select>
            </td>
            <td>
              <select name="inventory[{{ $dateString }}][manual_status_6hrs]" class="inv-input" style="min-width:88px;color:{{ $msColor($ms6) }}" {{ $isPast?'disabled':'' }}>
                <option value="" @selected($ms6=='')>Auto</option>
                <option value="Available" @selected($ms6=='Available')>Available</option>
                <option value="Sold Out" @selected($ms6=='Sold Out')>Sold Out</option>
              </select>
            </td>
            <td>
              <select name="inventory[{{ $dateString }}][manual_status_fullday]" class="inv-input" style="min-width:88px;color:{{ $msColor($msf) }}" {{ $isPast?'disabled':'' }}>
                <option value="" @selected($msf=='')>Auto</option>
                <option value="Available" @selected($msf=='Available')>Available</option>
                <option value="Sold Out" @selected($msf=='Sold Out')>Sold Out</option>
              </select>
            </td>
            <td><input type="text" class="inv-input" name="inventory[{{ $dateString }}][timing_3hrs]" value="{{ $rec?->timing_3hrs ?? '12AM-11PM' }}" {{ $isPast?'disabled':'' }}></td>
            <td><input type="text" class="inv-input" name="inventory[{{ $dateString }}][timing_6hrs]" value="{{ $rec?->timing_6hrs ?? '12AM-11PM' }}" {{ $isPast?'disabled':'' }}></td>

            @php
              $sym = $settings->base_currency_symbol ?? '₹';
              $c3 = $rec?->commission_3hrs ?? null;
              $c6 = $rec?->commission_6hrs ?? null;
              $cf = $rec?->commission_fullday ?? null;
              $r3 = $rec?->rate_3hrs ?? null;
              $r6 = $rec?->rate_6hrs ?? null;
              $rf = $rec?->rate_fullday ?? null;
            @endphp
            <td style="text-align:center;background:rgba(245,158,11,.04);color:var(--amber);font-size:12px;font-weight:700">{{ $c3 !== null ? $c3.'%' : '—' }}</td>
            <td style="text-align:center;background:rgba(245,158,11,.04);color:var(--amber);font-size:12px;font-weight:700">{{ $c6 !== null ? $c6.'%' : '—' }}</td>
            <td style="text-align:center;background:rgba(245,158,11,.04);color:var(--amber);font-size:12px;font-weight:700">{{ $cf !== null ? $cf.'%' : '—' }}</td>
            <td style="text-align:center;background:rgba(16,185,129,.04);color:var(--green);font-size:12px;font-weight:700">{{ ($r3 !== null && $c3 !== null) ? $sym.number_format($r3*(1-$c3/100),0) : '—' }}</td>
            <td style="text-align:center;background:rgba(16,185,129,.04);color:var(--green);font-size:12px;font-weight:700">{{ ($r6 !== null && $c6 !== null) ? $sym.number_format($r6*(1-$c6/100),0) : '—' }}</td>
            <td style="text-align:center;background:rgba(16,185,129,.04);color:var(--green);font-size:12px;font-weight:700">{{ ($rf !== null && $cf !== null) ? $sym.number_format($rf*(1-$cf/100),0) : '—' }}</td>
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
<script>
function updateBulkValueInput() {
  var field = document.getElementById('bulkField').value;
  var wrap = document.getElementById('bulkValueWrap');
  var statusFields = { status: [['Available','Available'],['Blocked','Blocked (Holiday)']],
    manual_status_3hrs: [['','Auto'],['Available','Available'],['Sold Out','Sold Out']],
    manual_status_6hrs: [['','Auto'],['Available','Available'],['Sold Out','Sold Out']],
    manual_status_fullday: [['','Auto'],['Available','Available'],['Sold Out','Sold Out']] };

  if (statusFields[field]) {
    var opts = statusFields[field].map(function(o){ return '<option value="'+o[0]+'">'+o[1]+'</option>'; }).join('');
    wrap.innerHTML = '<label class="flabel">Value</label><select id="bulkValue" class="fc">' + opts + '</select>';
  } else if (field.indexOf('timing') === 0) {
    wrap.innerHTML = '<label class="flabel">Value</label><input type="text" id="bulkValue" class="fc" placeholder="e.g. 12AM-11PM">';
  } else {
    wrap.innerHTML = '<label class="flabel">Value</label><input type="number" id="bulkValue" class="fc" placeholder="Enter value">';
  }
}

function applyBulkFill() {
  var field = document.getElementById('bulkField').value;
  var value = document.getElementById('bulkValue').value;
  var dayFilter = document.getElementById('bulkDays').value;

  if (value === '' || value === null) {
    alert('Enter a value to apply first.');
    return;
  }

  var rows = document.querySelectorAll('#inventoryForm tbody tr');
  var count = 0;
  rows.forEach(function(row) {
    if (row.dataset.past === '1') return;
    var dow = parseInt(row.dataset.dow, 10);
    var isWeekend = (dow === 0 || dow === 6);
    if (dayFilter === 'weekday' && isWeekend) return;
    if (dayFilter === 'weekend' && !isWeekend) return;

    var input = row.querySelector('[name$="[' + field + ']"]');
    if (input) {
      input.value = value;
      count++;
    }
  });

  alert('Applied to ' + count + ' day(s). Review the table, then click "Save Inventory Rules" to confirm.');
}
</script>
@endsection
