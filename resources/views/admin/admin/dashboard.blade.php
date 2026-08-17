@extends('admin.layout')
@section('section','Dashboard')

@section('content')
@php
  $sym = $settings->base_currency_symbol ?? '₹';
  $pos = $settings->base_currency_symbol_position ?? 'left';
  $fmt = fn($v) => $pos==='left' ? $sym.number_format($v,0) : number_format($v,0).$sym;

  // Slot totals
  $slot3    = $slot3hrs   ?? 0;
  $slot6    = $slot6hrs   ?? 0;
  $slot12   = $slot12hrs  ?? 0;
  $slotF    = $slotFull   ?? 0;
  $slotTot  = max($slot3+$slot6+$slot12+$slotF, 1);

  // Monthly chart
  $monthly  = $monthlyBookings ?? array_fill(0, 12, 0);
  $maxMo    = max(array_merge($monthly, [1]));
  $months   = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  $now      = \Carbon\Carbon::now();

  // Pending counts
  $pendV    = $pendingVendors ?? 0;
  $pendH    = $pendingHotels  ?? 0;
@endphp

{{-- Action alerts --}}
@if($pendV > 0 || $pendH > 0)
<div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap">
  @if($pendV > 0)
  <a href="{{ route('admin.vendor_management.registered_vendor',['language'=>$defaultLang->code??'en']) }}"
     style="flex:1;min-width:220px;display:flex;align-items:center;gap:12px;padding:14px 18px;background:rgba(217,119,6,.07);border:1px solid rgba(217,119,6,.25);border-radius:10px;text-decoration:none;color:#d97706">
    <i class="ti ti-user-exclamation" style="font-size:22px;flex-shrink:0"></i>
    <div>
      <div style="font-size:13px;font-weight:700">{{ $pendV }} Vendor{{ $pendV>1?'s':'' }} Awaiting Activation</div>
      <div style="font-size:11px;opacity:.75">Click to review and activate</div>
    </div>
    <i class="ti ti-arrow-right" style="margin-left:auto;font-size:16px"></i>
  </a>
  @endif
  @if($pendH > 0)
  <a href="{{ route('admin.hotel_management.pending_hotels',['language'=>$defaultLang->code??'en']) }}"
     style="flex:1;min-width:220px;display:flex;align-items:center;gap:12px;padding:14px 18px;background:rgba(37,99,235,.07);border:1px solid rgba(37,99,235,.2);border-radius:10px;text-decoration:none;color:#2563eb">
    <i class="ti ti-building-plus" style="font-size:22px;flex-shrink:0"></i>
    <div>
      <div style="font-size:13px;font-weight:700">{{ $pendH }} Hotel{{ $pendH>1?'s':'' }} Pending Approval</div>
      <div style="font-size:11px;opacity:.75">Click to approve or reject</div>
    </div>
    <i class="ti ti-arrow-right" style="margin-left:auto;font-size:16px"></i>
  </a>
  @endif
</div>
@endif

{{-- Page header --}}
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Good {{ $greeting ?? 'morning' }}, {{ $adminName ?? 'Admin' }} 👋</h2>
    <p>Platform overview — {{ date('d M Y') }}</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.select_vendor',['language'=>$defaultLang->code??'en']) }}" class="btn btn-primary">
      <i class="ti ti-plus"></i> Add Hotel
    </a>
  </div>
</div>

{{-- KPI Row 1: Core --}}
<div class="kpi-grid g4">
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-currency-rupee"></i></div><span class="delta up">All time</span></div>
    <div class="kpi-val">{{ $fmt($totalRevenue ?? 0) }}</div>
    <div class="kpi-lbl">Total Revenue</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon r"><i class="ti ti-calendar-check"></i></div><span class="delta neu">All time</span></div>
    <div class="kpi-val">
      <a href="{{ route('admin.room_bookings.all_bookings',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">
        {{ number_format($totalBookings ?? 0) }}
      </a>
    </div>
    <div class="kpi-lbl">Total Bookings <span style="color:var(--green);font-size:10px">{{ $activeBookings ?? 0 }} paid</span></div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-building"></i></div></div>
    <div class="kpi-val">{{ $approvedHotels ?? 0 }}</div>
    <div class="kpi-lbl">Active Hotels <span style="color:var(--amber);font-size:10px">{{ $pendH }} pending</span></div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon a"><i class="ti ti-users"></i></div></div>
    <div class="kpi-val">{{ $totalCustomers ?? 0 }}</div>
    <div class="kpi-lbl">Customers <span style="color:var(--green);font-size:10px">+{{ $newCustomersThisMonth ?? 0 }} this month</span></div>
  </div>
</div>

{{-- KPI Row 2: Today + Month --}}
<div class="kpi-grid g4">
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-calendar-due"></i></div><span class="delta up">Today</span></div>
    <div class="kpi-val">{{ $todayBookings ?? 0 }}</div>
    <div class="kpi-lbl">Bookings Today</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-coin"></i></div><span class="delta up">Today</span></div>
    <div class="kpi-val">{{ $fmt($todayRevenue ?? 0) }}</div>
    <div class="kpi-lbl">Revenue Today</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon p"><i class="ti ti-calendar-stats"></i></div><span class="delta neu">{{ $now->format('M') }}</span></div>
    <div class="kpi-val">{{ $monthBookings ?? 0 }}</div>
    <div class="kpi-lbl">Bookings This Month</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-currency-rupee"></i></div><span class="delta up">{{ $now->format('M') }}</span></div>
    <div class="kpi-val">{{ $fmt($monthRevenue ?? 0) }}</div>
    <div class="kpi-lbl">Revenue This Month</div>
  </div>
</div>

{{-- KPI Row 3: Pending + Vendors + Rooms --}}
<div class="kpi-grid g4">
  <div class="kpi" style="{{ $pendV>0 ? 'border-color:rgba(217,119,6,.4)' : '' }}">
    <div class="kpi-top"><div class="kpi-icon a"><i class="ti ti-building-store"></i></div>
      @if($pendV>0)<span class="delta dn">Action needed</span>@endif
    </div>
    <div class="kpi-val">
      <a href="{{ route('admin.vendor_management.registered_vendor',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $pendV }}</a>
    </div>
    <div class="kpi-lbl">Pending Vendors</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon r"><i class="ti ti-calendar-x"></i></div></div>
    <div class="kpi-val">{{ $cancelledBookings ?? 0 }}</div>
    <div class="kpi-lbl">Cancelled Bookings</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon c"><i class="ti ti-bed"></i></div></div>
    <div class="kpi-val">{{ $totalRooms ?? 0 }}</div>
    <div class="kpi-lbl">Total Rooms</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon p"><i class="ti ti-map-pin"></i></div></div>
    <div class="kpi-val">{{ $locations ?? 0 }}</div>
    <div class="kpi-lbl">Cities</div>
  </div>
</div>

{{-- Charts Row --}}
<div class="three-col" style="margin-bottom:16px">

  {{-- Monthly Bookings Chart --}}
  <div class="card" style="grid-column:span 2">
    <div class="card-hdr">
      <div>
        <div class="card-title">Monthly Bookings — {{ $now->year }}</div>
        <div class="card-sub">Total bookings per month</div>
      </div>
    </div>
    <div class="card-body">
      <div style="display:flex;align-items:flex-end;gap:8px;height:140px;padding-bottom:4px">
        @foreach($monthly as $i => $val)
        @php
          $pct = $maxMo > 0 ? max(round($val/$maxMo*100), ($val>0?4:0)) : 0;
          $isCur = ($i+1) == $now->month;
        @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;justify-content:flex-end">
          @if($val > 0)<div style="font-size:9px;color:var(--muted);font-weight:600">{{ $val }}</div>@endif
          <div style="width:100%;border-radius:4px 4px 0 0;background:{{ $isCur ? 'var(--red)' : 'rgba(227,30,36,.25)' }};height:{{ $pct }}%" title="{{ $months[$i] }}: {{ $val }}"></div>
          <div style="font-size:9px;color:{{ $isCur ? 'var(--red)' : 'var(--muted)' }};font-weight:{{ $isCur ? '700' : '400' }}">{{ $months[$i] }}</div>
        </div>
        @endforeach
      </div>
      <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);display:flex;justify-content:space-between;font-size:11px;color:var(--muted)">
        <span>YTD: <strong style="color:var(--text)">{{ number_format(array_sum($monthly)) }} bookings</strong></span>
        <span>Paid: <strong style="color:var(--green)">{{ number_format($activeBookings ?? 0) }}</strong></span>
        <span>Pending: <strong style="color:var(--amber)">{{ number_format($pendingBookings ?? 0) }}</strong></span>
        <span>Cancelled: <strong style="color:var(--red)">{{ number_format($cancelledBookings ?? 0) }}</strong></span>
      </div>
    </div>
  </div>

  {{-- Slot Breakdown --}}
  <div class="card">
    <div class="card-hdr"><div class="card-title">Booking Slots</div><div class="card-sub">Paid bookings by duration</div></div>
    <div class="card-body">
      @foreach([
        ['3 Hours',  $slot3,  '#3b82f6', 'ti-clock'],
        ['6 Hours',  $slot6,  '#8b5cf6', 'ti-clock-2'],
        ['12 Hours', $slot12, '#059669', 'ti-clock-hour-8'],
        ['Full Day', $slotF,  '#e31e24', 'ti-sun'],
      ] as [$lbl, $cnt, $col, $ico])
      @php $pct = round($cnt / $slotTot * 100); @endphp
      <div style="margin-bottom:14px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
          <div style="display:flex;align-items:center;gap:7px;font-size:12px;font-weight:600;color:var(--text)">
            <i class="ti {{ $ico }}" style="color:{{ $col }};font-size:13px"></i> {{ $lbl }}
          </div>
          <div style="font-size:12px;color:var(--muted)"><strong style="color:var(--text)">{{ $cnt }}</strong> ({{ $pct }}%)</div>
        </div>
        <div style="height:6px;background:var(--navy3);border-radius:3px;overflow:hidden;border:1px solid var(--border)">
          <div style="height:100%;width:{{ $pct }}%;background:{{ $col }};border-radius:3px"></div>
        </div>
      </div>
      @endforeach
      <div style="margin-top:16px;padding-top:12px;border-top:1px solid var(--border);text-align:center">
        <div style="font-size:22px;font-weight:800;color:var(--text)">{{ number_format($activeBookings ?? 0) }}</div>
        <div style="font-size:11px;color:var(--muted)">Total paid bookings</div>
      </div>
    </div>
  </div>
</div>

{{-- Bottom Row --}}
<div class="three-col">

  {{-- Revenue by City --}}
  <div class="card">
    <div class="card-hdr"><div><div class="card-title">Revenue by City</div><div class="card-sub">Top cities by booking revenue</div></div></div>
    <div class="card-body">
      @forelse($cityRevenue ?? [] as $city)
      <div class="bar-row">
        <div class="bar-lbl">{{ $city->city_name ?? 'Unknown' }}</div>
        <div class="bar-track"><div class="bar-fill" style="width:{{ $city->pct ?? 0 }}%"></div></div>
        <div class="bar-val">{{ $fmt($city->total) }}</div>
      </div>
      @empty
      <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
        <i class="ti ti-map-pin" style="font-size:28px;display:block;margin-bottom:8px;opacity:.4"></i>
        No city revenue data yet
      </div>
      @endforelse
    </div>
  </div>

  {{-- Top Hotels --}}
  <div class="card">
    <div class="card-hdr">
      <div><div class="card-title">Top Hotels</div><div class="card-sub">By revenue generated</div></div>
      <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code??'en']) }}" style="font-size:12px;color:var(--red);text-decoration:none">View all →</a>
    </div>
    <div class="card-body" style="padding:0">
      @forelse($topHotels ?? [] as $i => $hotel)
      <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--border)">
        <div style="width:26px;height:26px;border-radius:50%;background:{{ $i==0?'var(--red)':($i==1?'rgba(217,119,6,.15)':'var(--navy3)') }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:{{ $i==0?'#fff':($i==1?'var(--amber)':'var(--muted)') }};flex-shrink:0">{{ $i+1 }}</div>
        <div style="flex:1;min-width:0">
          <div style="font-size:12.5px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $hotel->hotel_name }}</div>
          <div style="font-size:11px;color:var(--muted)">{{ number_format($hotel->bookings) }} bookings</div>
        </div>
        <div style="font-size:12px;font-weight:700;color:var(--green);flex-shrink:0">{{ $fmt($hotel->revenue) }}</div>
      </div>
      @empty
      <div style="text-align:center;padding:24px;color:var(--muted);font-size:12px">No booking data yet</div>
      @endforelse
    </div>
  </div>

  {{-- Recent Bookings --}}
  <div class="card">
    <div class="card-hdr">
      <div><div class="card-title">Recent Bookings</div><div class="card-sub">Latest 8 bookings</div></div>
      <a href="{{ route('admin.room_bookings.all_bookings',['language'=>$defaultLang->code??'en']) }}" style="font-size:12px;color:var(--red);text-decoration:none">View all →</a>
    </div>
    <div class="card-body" style="padding:0">
      @forelse($recentBookings ?? [] as $b)
      @php
        $isPaid = ($b->payment_status == 1 || $b->payment_status === 'paid');
        $isCan  = ($b->order_status === 'cancelled');
        $statusColor = $isCan ? '#dc2626' : ($isPaid ? '#059669' : '#d97706');
        $statusLabel = $isCan ? 'Cancelled' : ($isPaid ? 'Paid' : 'Pending');
        $hrs = isset($b->hour) ? ($b->hour == 24 ? 'Full Day' : $b->hour.'hrs') : '—';
      @endphp
      <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border)">
        <div style="flex:1;min-width:0">
          <div style="font-size:12px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
            {{ $b->hotel_name ?? 'Hotel #'.$b->hotel_id }}
          </div>
          <div style="font-size:11px;color:var(--muted)">
            #{{ $b->order_number }} · {{ $hrs }}
            @if($b->booking_name) · {{ $b->booking_name }}@endif
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0">
          <div style="font-size:12px;font-weight:800;color:var(--text)">{{ $fmt($b->grand_total) }}</div>
          <div style="font-size:10px;font-weight:700;color:{{ $statusColor }}">{{ $statusLabel }}</div>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:24px;color:var(--muted);font-size:12px">No bookings yet</div>
      @endforelse
    </div>
  </div>
</div>

@endsection
