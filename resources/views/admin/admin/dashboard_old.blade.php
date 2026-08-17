@extends('admin.layout')
@section('section','Dashboard')

@section('content')
@php
  $rp = (!isset($roleInfo) || is_null($roleInfo ?? null))
        ? null
        : json_decode($roleInfo->permissions ?? '[]');
  $sym = $settings->base_currency_symbol ?? '₹';
  $pos = $settings->base_currency_symbol_position ?? 'left';
  $fmt = fn($v) => $pos=='left' ? $sym.number_format($v,0) : number_format($v,0).$sym;
  $totalEarning = optional($earning)->total_earning ?? 0;

  // Live pending counts
  $pendingVendors = \App\Models\Vendor::where('status', 0)->count();
  $pendingHotels  = \App\Models\Hotel::where('approval_status', 0)->count();

  // Booking analytics
  $now = \Carbon\Carbon::now();
  $todayBookings   = \App\Models\Booking::whereDate('created_at', $now->toDateString())->count();
  $todayRevenue    = \App\Models\Booking::whereDate('created_at', $now->toDateString())->where('payment_status','paid')->sum('grand_total');
  $monthBookings   = \App\Models\Booking::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
  $monthRevenue    = \App\Models\Booking::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->where('payment_status','paid')->sum('grand_total');
  $totalBookings   = \App\Models\Booking::count();
  $paidBookings    = \App\Models\Booking::where('payment_status','paid')->count();

  // Booking slot breakdown
  $slot3  = \App\Models\Booking::where('payment_status','paid')->where('hour','<=',3)->count();
  $slot6  = \App\Models\Booking::where('payment_status','paid')->whereBetween('hour',[4,6])->count();
  $slot12 = \App\Models\Booking::where('payment_status','paid')->whereBetween('hour',[7,12])->count();
  $slotFull = \App\Models\Booking::where('payment_status','paid')->where('hour','>',12)->count();
  $slotTotal = max($slot3+$slot6+$slot12+$slotFull, 1);

  // Revenue by city (top 6)
  $cityRevenue = \Illuminate\Support\Facades\DB::table('bookings')
    ->join('hotel_contents','bookings.hotel_id','=','hotel_contents.hotel_id')
    ->join('cities','hotel_contents.city_id','=','cities.id')
    ->join('languages','hotel_contents.language_id','=','languages.id')
    ->where('bookings.payment_status','paid')
    ->where('languages.is_default',1)
    ->selectRaw('cities.name as city_name, SUM(bookings.grand_total) as total')
    ->groupBy('cities.id','cities.name')
    ->orderByDesc('total')->limit(6)->get();
  $maxCity = $cityRevenue->max('total') ?: 1;

  // Top performing hotels
  $topHotels = \Illuminate\Support\Facades\DB::table('bookings')
    ->join('hotel_contents','bookings.hotel_id','=','hotel_contents.hotel_id')
    ->join('languages','hotel_contents.language_id','=','languages.id')
    ->where('bookings.payment_status','paid')
    ->where('languages.is_default',1)
    ->selectRaw('hotel_contents.title as hotel_name, COUNT(bookings.id) as bookings, SUM(bookings.grand_total) as revenue')
    ->groupBy('bookings.hotel_id','hotel_contents.title')
    ->orderByDesc('revenue')->limit(5)->get();

  // Monthly bookings this year
  $monthlyBookings = [];
  for($m=1;$m<=12;$m++){
    $monthlyBookings[] = \App\Models\Booking::whereMonth('created_at',$m)->whereYear('created_at',$now->year)->count();
  }
  $maxMonthly = max(array_merge($monthlyBookings,[1]));
  $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
@endphp

{{-- Action Alerts --}}
@if($pendingVendors > 0 || $pendingHotels > 0)
<div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap">
  @if($pendingVendors > 0)
  <a href="{{ route('admin.vendor_management.registered_vendor',['language'=>$defaultLang->code??'en']) }}"
     style="flex:1;min-width:220px;display:flex;align-items:center;gap:12px;padding:14px 18px;background:rgba(217,119,6,.07);border:1px solid rgba(217,119,6,.25);border-radius:10px;text-decoration:none;color:var(--sz-amber,#d97706)">
    <i class="ti ti-user-exclamation" style="font-size:22px;flex-shrink:0"></i>
    <div>
      <div style="font-size:13px;font-weight:700">{{ $pendingVendors }} Vendor{{ $pendingVendors>1?'s':'' }} Awaiting Activation</div>
      <div style="font-size:11px;opacity:.75">Click to review and activate</div>
    </div>
    <i class="ti ti-arrow-right" style="margin-left:auto;font-size:16px"></i>
  </a>
  @endif
  @if($pendingHotels > 0)
  <a href="{{ route('admin.hotel_management.pending_hotels',['language'=>$defaultLang->code??'en']) }}"
     style="flex:1;min-width:220px;display:flex;align-items:center;gap:12px;padding:14px 18px;background:rgba(37,99,235,.07);border:1px solid rgba(37,99,235,.2);border-radius:10px;text-decoration:none;color:var(--sz-blue,#2563eb)">
    <i class="ti ti-building-plus" style="font-size:22px;flex-shrink:0"></i>
    <div>
      <div style="font-size:13px;font-weight:700">{{ $pendingHotels }} Hotel{{ $pendingHotels>1?'s':'' }} Pending Approval</div>
      <div style="font-size:11px;opacity:.75">Click to approve or reject</div>
    </div>
    <i class="ti ti-arrow-right" style="margin-left:auto;font-size:16px"></i>
  </a>
  @endif
</div>
@endif

{{-- Page Header --}}
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Welcome back, {{ optional($authAdmin)->first_name }} {{ optional($authAdmin)->last_name }} 👋</h2>
    <p>Platform overview — {{ date('d M Y') }}</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.select_vendor',['language'=>$defaultLang->code??'en']) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Hotel</a>
  </div>
</div>

{{-- KPI Row 1: Core metrics --}}
<div class="kpi-grid g4">
  @if(is_null($rp)||in_array('Transaction',(array)$rp))
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-currency-rupee"></i></div><span class="delta up">All time</span></div>
    <div class="kpi-val">{{ $fmt($totalEarning) }}</div>
    <div class="kpi-lbl">Total platform revenue</div>
  </div>
  @endif
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon r"><i class="ti ti-calendar-check"></i></div><span class="delta neu">All time</span></div>
    <div class="kpi-val"><a href="{{ route('admin.room_bookings.all_bookings',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ number_format($totalBookings) }}</a></div>
    <div class="kpi-lbl">Total bookings</div>
  </div>
  @if(is_null($rp)||in_array('Rooms Management',(array)$rp))
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon c"><i class="ti ti-bed"></i></div></div>
    <div class="kpi-val"><a href="{{ route('admin.room_management.rooms',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $totalRooms??0 }}</a></div>
    <div class="kpi-lbl">Total rooms</div>
  </div>
  @endif
  @if(is_null($rp)||in_array('Vendors Management',(array)$rp))
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon a"><i class="ti ti-building-store"></i></div></div>
    <div class="kpi-val"><a href="{{ route('admin.vendor_management.registered_vendor',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $vendors??0 }}</a></div>
    <div class="kpi-lbl">Total vendors</div>
  </div>
  @endif
</div>

{{-- KPI Row 2: Today + This Month --}}
<div class="kpi-grid g4">
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-calendar-due"></i></div><span class="delta up">Today</span></div>
    <div class="kpi-val">{{ $todayBookings }}</div>
    <div class="kpi-lbl">Bookings today</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-coin"></i></div><span class="delta up">Today</span></div>
    <div class="kpi-val">{{ $fmt($todayRevenue) }}</div>
    <div class="kpi-lbl">Revenue today</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon p"><i class="ti ti-calendar-stats"></i></div><span class="delta neu">{{ $now->format('M') }}</span></div>
    <div class="kpi-val">{{ $monthBookings }}</div>
    <div class="kpi-lbl">Bookings this month</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-currency-rupee"></i></div><span class="delta up">{{ $now->format('M') }}</span></div>
    <div class="kpi-val">{{ $fmt($monthRevenue) }}</div>
    <div class="kpi-lbl">Revenue this month</div>
  </div>
</div>

{{-- KPI Row 3: People + Pending --}}
<div class="kpi-grid g4">
  @if(is_null($rp)||in_array('User Management',(array)$rp))
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-users"></i></div></div>
    <div class="kpi-val"><a href="{{ route('admin.user_management.registered_users',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $totalUser??0 }}</a></div>
    <div class="kpi-lbl">Total customers</div>
  </div>
  @endif
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon p"><i class="ti ti-map-pin"></i></div></div>
    <div class="kpi-val">{{ $locations??0 }}</div>
    <div class="kpi-lbl">Cities covered</div>
  </div>
  @if(is_null($rp)||in_array('Vendors Management',(array)$rp))
  <div class="kpi" style="{{ $pendingVendors>0?'border-color:rgba(217,119,6,.4)':'' }}">
    <div class="kpi-top"><div class="kpi-icon a"><i class="ti ti-user-check"></i></div>
      @if($pendingVendors>0)<span class="delta dn">Action needed</span>@endif
    </div>
    <div class="kpi-val"><a href="{{ route('admin.vendor_management.registered_vendor',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $pendingVendors }}</a></div>
    <div class="kpi-lbl">Vendors awaiting activation</div>
  </div>
  @endif
  @if(is_null($rp)||in_array('Hotels Management',(array)$rp))
  <div class="kpi" style="{{ $pendingHotels>0?'border-color:rgba(37,99,235,.4)':'' }}">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-building-plus"></i></div>
      @if($pendingHotels>0)<span class="delta dn">Action needed</span>@endif
    </div>
    <div class="kpi-val"><a href="{{ route('admin.hotel_management.pending_hotels',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $pendingHotels }}</a></div>
    <div class="kpi-lbl">Hotels pending approval</div>
  </div>
  @endif
</div>

{{-- Analytics Row: Monthly Bookings + Booking Slots + Revenue by City --}}
<div class="three-col" style="margin-bottom:16px">

  {{-- Monthly Bookings Chart --}}
  <div class="card" style="grid-column:span 2">
    <div class="card-hdr">
      <div>
        <div class="card-title">Monthly Bookings — {{ date('Y') }}</div>
        <div class="card-sub">Total bookings per month this year</div>
      </div>
      <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--sz-muted)">
        <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:var(--sz-red)"></span> Bookings
      </div>
    </div>
    <div class="card-body">
      <div style="display:flex;align-items:flex-end;gap:8px;height:140px;padding-bottom:4px">
        @foreach($monthlyBookings as $i => $val)
        @php
          $pct = $maxMonthly>0 ? max(round($val/$maxMonthly*100),($val>0?4:0)) : 0;
          $isCurrentMonth = ($i+1)==$now->month;
        @endphp
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;justify-content:flex-end">
          @if($val>0)<div style="font-size:9px;color:var(--sz-muted);font-weight:600">{{ $val }}</div>@endif
          <div style="width:100%;border-radius:4px 4px 0 0;background:{{ $isCurrentMonth?'var(--sz-red)':'rgba(232,25,44,.25)' }};height:{{ $pct }}%;transition:height .3s" title="{{ $months[$i] }}: {{ $val }} bookings"></div>
          <div style="font-size:9px;color:{{ $isCurrentMonth?'var(--sz-red)':'var(--sz-muted)' }};font-weight:{{ $isCurrentMonth?'700':'400' }}">{{ $months[$i] }}</div>
        </div>
        @endforeach
      </div>
      <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--sz-border2);display:flex;justify-content:space-between;font-size:11px;color:var(--sz-muted)">
        <span>YTD bookings: <strong style="color:var(--sz-text)">{{ number_format(array_sum($monthlyBookings)) }}</strong></span>
        <span>Paid: <strong style="color:var(--sz-green)">{{ number_format($paidBookings) }}</strong></span>
      </div>
    </div>
  </div>

  {{-- Booking Slot Breakdown --}}
  <div class="card">
    <div class="card-hdr"><div class="card-title">Booking Slots</div><div class="card-sub">Paid bookings by duration</div></div>
    <div class="card-body">
      @php
        $slots = [
          ['label'=>'3 Hours','count'=>$slot3,'color'=>'#E8192C','icon'=>'ti-clock'],
          ['label'=>'6 Hours','count'=>$slot6,'color'=>'#2563eb','icon'=>'ti-clock-2'],
          ['label'=>'12 Hours','count'=>$slot12,'color'=>'#7c3aed','icon'=>'ti-clock-hour-8'],
          ['label'=>'Full Day','count'=>$slotFull,'color'=>'#16a34a','icon'=>'ti-sun'],
        ];
      @endphp
      @foreach($slots as $sl)
      @php $pct = round($sl['count']/$slotTotal*100); @endphp
      <div style="margin-bottom:14px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px">
          <div style="display:flex;align-items:center;gap:7px;font-size:12px;font-weight:600;color:var(--sz-text)">
            <i class="ti {{ $sl['icon'] }}" style="color:{{ $sl['color'] }};font-size:13px"></i>
            {{ $sl['label'] }}
          </div>
          <div style="font-size:12px;color:var(--sz-muted)"><strong style="color:var(--sz-text)">{{ $sl['count'] }}</strong> ({{ $pct }}%)</div>
        </div>
        <div style="height:6px;background:var(--sz-surface2);border-radius:3px;overflow:hidden;border:1px solid var(--sz-border2)">
          <div style="height:100%;width:{{ $pct }}%;background:{{ $sl['color'] }};border-radius:3px;transition:width .4s"></div>
        </div>
      </div>
      @endforeach
      <div style="margin-top:16px;padding-top:12px;border-top:1px solid var(--sz-border2);text-align:center">
        <div style="font-size:22px;font-weight:800;color:var(--sz-text)">{{ number_format($paidBookings) }}</div>
        <div style="font-size:11px;color:var(--sz-muted)">Total paid bookings</div>
      </div>
    </div>
  </div>
</div>

{{-- Revenue by City + Top Hotels + Quick Actions --}}
<div class="three-col">

  {{-- Revenue by City --}}
  <div class="card">
    <div class="card-hdr">
      <div><div class="card-title">Revenue by City</div><div class="card-sub">Top 6 cities by booking revenue</div></div>
    </div>
    <div class="card-body">
      @forelse($cityRevenue as $city)
      @php $pct = round($city->total/$maxCity*100); @endphp
      <div class="bar-row">
        <div class="bar-lbl">{{ $city->city_name }}</div>
        <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%"></div></div>
        <div class="bar-val">{{ $sym }}{{ number_format($city->total/1000,1) }}k</div>
      </div>
      @empty
      <div style="text-align:center;padding:20px;color:var(--sz-muted);font-size:12px">
        <i class="ti ti-map-pin" style="font-size:28px;display:block;margin-bottom:8px"></i>
        No city data yet
      </div>
      @endforelse
    </div>
  </div>

  {{-- Top Hotels --}}
  <div class="card">
    <div class="card-hdr">
      <div><div class="card-title">Top Hotels</div><div class="card-sub">By revenue generated</div></div>
      <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code??'en']) }}" style="font-size:12px;color:var(--sz-red);text-decoration:none">View all →</a>
    </div>
    <div class="card-body" style="padding:0">
      @forelse($topHotels as $i => $hotel)
      <div style="display:flex;align-items:center;gap:12px;padding:12px 18px;border-bottom:1px solid var(--sz-border2)">
        <div style="width:26px;height:26px;border-radius:50%;background:{{ $i==0?'var(--sz-red)':($i==1?'rgba(217,119,6,.15)':'var(--sz-surface2)') }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:{{ $i==0?'#fff':($i==1?'var(--sz-amber)':'var(--sz-muted)') }};flex-shrink:0">{{ $i+1 }}</div>
        <div style="flex:1;min-width:0">
          <div style="font-size:12.5px;font-weight:600;color:var(--sz-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $hotel->hotel_name }}</div>
          <div style="font-size:11px;color:var(--sz-muted)">{{ number_format($hotel->bookings) }} bookings</div>
        </div>
        <div style="font-size:12px;font-weight:700;color:var(--sz-green);flex-shrink:0">{{ $sym }}{{ number_format($hotel->revenue/1000,1) }}k</div>
      </div>
      @empty
      <div style="text-align:center;padding:24px;color:var(--sz-muted);font-size:12px">No booking data yet</div>
      @endforelse
    </div>
  </div>

  {{-- Quick Actions --}}
  <div class="card">
    <div class="card-hdr"><div class="card-title">Quick Actions</div></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
      @php
        $actions = [
          ['icon'=>'ti-building','color'=>'var(--sz-blue)','label'=>'Add Hotel','sub'=>'Register new property','route'=>route('admin.hotel_management.select_vendor',['language'=>$defaultLang->code??'en'])],
          ['icon'=>'ti-bed','color'=>'var(--sz-purple)','label'=>'Add Room','sub'=>'Under a vendor hotel','route'=>route('admin.room_management.select_vendor')],
          ['icon'=>'ti-building-store','color'=>'var(--sz-green)','label'=>'Add Vendor','sub'=>'Onboard hotel partner','route'=>route('admin.vendor_management.add_vendor',['language'=>$defaultLang->code??'en'])],
          ['icon'=>'ti-bell','color'=>'var(--sz-amber)','label'=>'Push Notification','sub'=>'Broadcast to customers','route'=>route('admin.user_management.push_notification.notification_for_visitors',['language'=>$defaultLang->code??'en'])],
          ['icon'=>'ti-building-plus','color'=>'var(--sz-red)','label'=>'Pending Hotels','sub'=>$pendingHotels.' awaiting review','route'=>route('admin.hotel_management.pending_hotels',['language'=>$defaultLang->code??'en'])],
        ];
      @endphp
      @foreach($actions as $act)
      <a href="{{ $act['route'] }}"
         style="display:flex;align-items:center;gap:10px;background:var(--sz-surface2);border:1px solid var(--sz-border);border-radius:8px;padding:10px 12px;text-decoration:none;transition:border-color .15s,background .15s"
         onmouseover="this.style.borderColor='var(--sz-red)';this.style.background='rgba(232,25,44,.04)'"
         onmouseout="this.style.borderColor='var(--sz-border)';this.style.background='var(--sz-surface2)'">
        <i class="ti {{ $act['icon'] }}" style="font-size:18px;color:{{ $act['color'] }};flex-shrink:0"></i>
        <div>
          <div style="font-size:12px;font-weight:600;color:var(--sz-text)">{{ $act['label'] }}</div>
          <div style="font-size:11px;color:var(--sz-muted)">{{ $act['sub'] }}</div>
        </div>
        <i class="ti ti-chevron-right" style="margin-left:auto;color:var(--sz-muted);font-size:13px"></i>
      </a>
      @endforeach
    </div>
  </div>
</div>

@endsection
