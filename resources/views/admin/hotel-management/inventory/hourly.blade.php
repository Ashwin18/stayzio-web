@extends('admin.layout')
@section('section','Hotel Management')
@section('page','Hourly Inventory')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Hourly Inventory</h2>
    <p>Set date-wise rates, availability and commission per slot</p>
  </div>
</div>

{{-- Filter Form --}}
<div class="card" style="margin-bottom:16px">
  <div class="card-body">
    <form method="GET" action="{{ route('admin.hotel_management.hourly_inventory.index') }}">
      <div style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;align-items:flex-end">
        <div class="fg">
          <label class="flabel">Hotel</label>
          <select name="hotel_id" class="fc" onchange="this.form.submit()">
            @foreach($hotels as $hotel)
            <option value="{{ $hotel->id }}" {{ $selected_hotel_id == $hotel->id ? 'selected' : '' }}>
              {{ $hotel->title }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="fg">
          <label class="flabel">From Date</label>
          <input type="date" name="from_date" class="fc" value="{{ $from_date }}" min="{{ date('Y-m-d') }}">
        </div>
        <div class="fg">
          <label class="flabel">To Date</label>
          <input type="date" name="to_date" class="fc" value="{{ $to_date }}" min="{{ $from_date }}">
        </div>
        <button type="submit" class="btn btn-primary" style="height:40px">
          <i class="ti ti-search"></i> Load
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Info Banner --}}
<div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:12px;color:#5d4037;display:flex;align-items:flex-start;gap:10px">
  <i class="ti ti-info-circle" style="font-size:18px;color:#f59e0b;flex-shrink:0;margin-top:1px"></i>
  <div>
    <strong>How inventory pricing works:</strong>
    Date-level rates override the room's default prices. Commission is the platform's cut (%).
    If no inventory is set for a date, the room's default price is used.
    Rooms Sold is auto-updated when bookings are confirmed via Razorpay.
  </div>
</div>

@if(Session::has('success'))
<div class="alert alert-success" style="margin-bottom:14px">{{ Session::get('success') }}</div>
@endif

{{-- Inventory Grid --}}
<form method="POST" action="{{ route('admin.hotel_management.inventory.update_inline') }}">
  @csrf
  <input type="hidden" name="hotel_id" value="{{ $selected_hotel_id }}">

  <div class="card">
    <div class="card-hdr" style="flex-wrap:wrap;gap:10px">
      <div class="card-title">
        Inventory: {{ \Carbon\Carbon::parse($from_date)->format('d M') }} — {{ \Carbon\Carbon::parse($to_date)->format('d M Y') }}
        <span style="font-size:11px;color:var(--muted);font-weight:400;margin-left:8px">{{ count($datePeriod) }} days</span>
      </div>
      <button type="submit" class="btn btn-primary btn-sm">
        <i class="ti ti-device-floppy"></i> Save All Changes
      </button>
    </div>

    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;font-size:12px">
        <thead>
          <tr style="background:var(--navy3);border-bottom:2px solid var(--border)">
            <th style="padding:10px 14px;text-align:left;font-weight:700;color:var(--muted);white-space:nowrap;min-width:100px">Date</th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap" colspan="2">
              <span style="color:#3b82f6">3 Hours</span>
            </th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap" colspan="2">
              <span style="color:#8b5cf6">6 Hours</span>
            </th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap" colspan="2">
              <span style="color:#059669">Full Day</span>
            </th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap">Rooms</th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap">Sold</th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap">Status</th>
            <th style="padding:10px 8px;text-align:center;font-weight:700;color:var(--muted);white-space:nowrap">Refund</th>
          </tr>
          <tr style="background:var(--navy3);border-bottom:1px solid var(--border)">
            <th style="padding:4px 14px;font-size:10px;font-weight:600;color:var(--muted)"></th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:#3b82f6;text-align:center">Rate (₹)</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:#3b82f6;text-align:center">Comm %</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:#8b5cf6;text-align:center">Rate (₹)</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:#8b5cf6;text-align:center">Comm %</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:#059669;text-align:center">Rate (₹)</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:#059669;text-align:center">Comm %</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:var(--muted);text-align:center">Total</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:var(--muted);text-align:center">Count</th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:var(--muted);text-align:center"></th>
            <th style="padding:4px 8px;font-size:10px;font-weight:600;color:var(--muted);text-align:center">Policy</th>
          </tr>
        </thead>
        <tbody>
          @foreach($datePeriod as $dateStr)
          @php
            $rec = $inventories[$dateStr] ?? null;
            $isToday = $dateStr === now()->format('Y-m-d');
            $isPast  = $dateStr < now()->format('Y-m-d');
            $dayName = \Carbon\Carbon::parse($dateStr)->format('D');
            $isWeekend = in_array(\Carbon\Carbon::parse($dateStr)->dayOfWeek, [0,6]);
            $soldOut = $rec && $rec->status === 'Sold Out';
            $roomsLeft = $rec ? max(0, $rec->total_rooms - $rec->rooms_sold) : null;
          @endphp
          <tr style="border-bottom:1px solid var(--border);{{ $isPast ? 'opacity:.5' : '' }}{{ $isToday ? 'background:rgba(227,30,36,.04)' : '' }}">

            {{-- Date --}}
            <td style="padding:8px 14px;white-space:nowrap">
              <div style="font-size:13px;font-weight:800;color:{{ $isToday ? 'var(--red)' : 'var(--text)' }}">
                {{ \Carbon\Carbon::parse($dateStr)->format('d M') }}
              </div>
              <div style="font-size:10px;color:{{ $isWeekend ? '#e31e24' : 'var(--muted)' }};font-weight:600">
                {{ $dayName }}{{ $isToday ? ' · Today' : '' }}
              </div>
            </td>

            {{-- 3 Hours Rate + Commission --}}
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp" name="inventory[{{ $dateStr }}][rate_3hrs]"
                value="{{ $rec->rate_3hrs ?? '' }}" placeholder="₹ Rate" min="0" {{ $isPast ? 'disabled' : '' }}>
            </td>
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp comm-inp" name="inventory[{{ $dateStr }}][commission_3hrs]"
                value="{{ $rec->commission_3hrs ?? 10 }}" placeholder="%" min="0" max="100" step="0.5" {{ $isPast ? 'disabled' : '' }}>
            </td>

            {{-- 6 Hours Rate + Commission --}}
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp" name="inventory[{{ $dateStr }}][rate_6hrs]"
                value="{{ $rec->rate_6hrs ?? '' }}" placeholder="₹ Rate" min="0" {{ $isPast ? 'disabled' : '' }}>
            </td>
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp comm-inp" name="inventory[{{ $dateStr }}][commission_6hrs]"
                value="{{ $rec->commission_6hrs ?? 10 }}" placeholder="%" min="0" max="100" step="0.5" {{ $isPast ? 'disabled' : '' }}>
            </td>

            {{-- Full Day Rate + Commission --}}
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp" name="inventory[{{ $dateStr }}][rate_fullday]"
                value="{{ $rec->rate_fullday ?? '' }}" placeholder="₹ Rate" min="0" {{ $isPast ? 'disabled' : '' }}>
            </td>
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp comm-inp" name="inventory[{{ $dateStr }}][commission_fullday]"
                value="{{ $rec->commission_fullday ?? 10 }}" placeholder="%" min="0" max="100" step="0.5" {{ $isPast ? 'disabled' : '' }}>
            </td>

            {{-- Total Rooms --}}
            <td style="padding:6px 4px">
              <input type="number" class="inv-inp" name="inventory[{{ $dateStr }}][total_rooms]"
                value="{{ $rec->total_rooms ?? 5 }}" placeholder="5" min="1" {{ $isPast ? 'disabled' : '' }}>
            </td>

            {{-- Rooms Sold (readonly) --}}
            <td style="padding:6px 8px;text-align:center">
              @if($rec)
                <span style="font-size:13px;font-weight:800;color:{{ $soldOut ? '#dc2626' : ($roomsLeft == 0 ? '#dc2626' : '#374151') }}">
                  {{ $rec->rooms_sold }}/{{ $rec->total_rooms }}
                </span>
                @if($roomsLeft !== null)
                <div style="font-size:10px;color:{{ $roomsLeft > 0 ? '#059669' : '#dc2626' }}">{{ $roomsLeft }} left</div>
                @endif
              @else
                <span style="color:var(--muted);font-size:11px">—</span>
              @endif
              <input type="hidden" name="inventory[{{ $dateStr }}][status]"
                value="{{ $rec ? $rec->status : 'Available' }}">
            </td>

            {{-- Status --}}
            <td style="padding:6px 4px">
              @if(!$isPast)
              <select class="inv-inp" name="inventory[{{ $dateStr }}][status]" style="min-width:90px">
                <option value="Available"     {{ (!$rec || $rec->status=='Available')    ? 'selected' : '' }}>Available</option>
                <option value="Blocked"       {{ ($rec && $rec->status=='Blocked')       ? 'selected' : '' }}>Blocked</option>
                <option value="Sold Out"      {{ ($rec && $rec->status=='Sold Out')      ? 'selected' : '' }}>Sold Out</option>
              </select>
              @else
              <span style="font-size:11px;padding:3px 8px;border-radius:20px;background:{{ $soldOut ? '#fee2e2' : '#f3f4f6' }};color:{{ $soldOut ? '#dc2626' : 'var(--muted)' }};font-weight:700">
                {{ $rec ? $rec->status : '—' }}
              </span>
              @endif
            </td>

            {{-- Refund Policy --}}
            <td style="padding:6px 4px">
              @if(!$isPast)
              <select class="inv-inp" name="inventory[{{ $dateStr }}][refund_policy]" style="min-width:110px">
                <option value="flexible"       {{ (!$rec || $rec->refund_policy=='flexible')       ? 'selected' : '' }}>Flexible</option>
                <option value="non-refundable" {{ ($rec && $rec->refund_policy=='non-refundable') ? 'selected' : '' }}>Non-Refundable</option>
              </select>
              @else
              <span style="font-size:11px;color:var(--muted)">{{ $rec ? ucfirst($rec->refund_policy) : '—' }}</span>
              @endif
            </td>

          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div style="padding:14px 18px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
      <div style="font-size:12px;color:var(--muted)">
        <i class="ti ti-info-circle"></i>
        Past dates are read-only. Comm % = platform commission deducted from rate.
      </div>
      <button type="submit" class="btn btn-primary">
        <i class="ti ti-device-floppy"></i> Save All Changes
      </button>
    </div>
  </div>
</form>

{{-- Bulk Fill Tool --}}
<div class="card" style="margin-top:16px">
  <div class="card-hdr">
    <div class="card-title"><i class="ti ti-fill" style="color:var(--blue)"></i> Quick Fill</div>
    <div class="card-sub">Apply same rates to all dates in the range at once</div>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(7,1fr) auto;gap:10px;align-items:flex-end">
      <div class="fg"><label class="flabel">3 Hr Rate</label><input type="number" id="fill3r" class="fc" placeholder="₹1050" min="0"></div>
      <div class="fg"><label class="flabel">3 Hr Comm %</label><input type="number" id="fill3c" class="fc" placeholder="10" min="0" max="100"></div>
      <div class="fg"><label class="flabel">6 Hr Rate</label><input type="number" id="fill6r" class="fc" placeholder="₹1250" min="0"></div>
      <div class="fg"><label class="flabel">6 Hr Comm %</label><input type="number" id="fill6c" class="fc" placeholder="10" min="0" max="100"></div>
      <div class="fg"><label class="flabel">Full Day Rate</label><input type="number" id="fillfdr" class="fc" placeholder="₹2500" min="0"></div>
      <div class="fg"><label class="flabel">Full Day Comm %</label><input type="number" id="fillfdc" class="fc" placeholder="10" min="0" max="100"></div>
      <div class="fg"><label class="flabel">Total Rooms</label><input type="number" id="fillrms" class="fc" placeholder="5" min="1"></div>
      <button type="button" class="btn btn-secondary" onclick="bulkFill()" style="height:40px">
        <i class="ti ti-fill"></i> Fill All
      </button>
    </div>
  </div>
</div>

@endsection

@section('script')
<style>
.inv-inp{
  width:80px;padding:5px 6px;border:1px solid var(--border);border-radius:6px;
  font-size:11px;font-weight:600;color:var(--text);background:var(--navy3);
  text-align:center;outline:none;transition:.15s;font-family:'Poppins',sans-serif
}
.inv-inp:focus{border-color:var(--red);background:#fff}
.inv-inp:disabled{opacity:.5;cursor:not-allowed}
.comm-inp{background:#f0f4ff;border-color:#c7d7ff;color:#2563eb}
.comm-inp:focus{border-color:#2563eb;background:#fff}
</style>
<script>
function bulkFill() {
  var vals = {
    rate_3hrs:       document.getElementById('fill3r').value,
    commission_3hrs: document.getElementById('fill3c').value,
    rate_6hrs:       document.getElementById('fill6r').value,
    commission_6hrs: document.getElementById('fill6c').value,
    rate_fullday:    document.getElementById('fillfdr').value,
    commission_fullday: document.getElementById('fillfdc').value,
    total_rooms:     document.getElementById('fillrms').value,
  };
  Object.entries(vals).forEach(function([key, val]) {
    if (!val) return;
    document.querySelectorAll('[name*="[' + key + ']"]').forEach(function(inp) {
      if (!inp.disabled) inp.value = val;
    });
  });
}
</script>
@endsection
