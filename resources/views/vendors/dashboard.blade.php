@extends('vendors.layout')
@section('section','Dashboard')
@section('page','Overview')

@section('content')
@php
  $sym = $settings->base_currency_symbol ?? '₹';
  $pos = $settings->base_currency_symbol_position ?? 'left';
  $fmt = fn($v) => $pos=='left' ? $sym.number_format($v,0) : number_format($v,0).$sym;
  $vendor = Auth::guard('vendor')->user();
  $maxHotelRev = collect($revenueByHotel ?? [])->max('total') ?: 1;
@endphp

{{-- Page Header --}}
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Good {{ $greeting ?? 'day' }}, {{ $vendorName ?? $vendor->username }} 👋</h2>
    <p>Your property performance — {{ date('d M Y') }}</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.hotel_management.create_hotel',['language'=>$defaultLang->code??'en']) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Hotel</a>
    <a href="{{ route('vendor.room_bookings.all_bookings',['language'=>$defaultLang->code??'en']) }}" class="btn btn-secondary"><i class="ti ti-calendar-check"></i> All Bookings</a>
  </div>
</div>

{{-- Pending hotel notice --}}
@if(($pendingHotels ?? 0) > 0)
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.3);border-radius:8px;margin-bottom:16px;font-size:13px;color:var(--amber)">
  <i class="ti ti-clock-hour-4" style="font-size:18px;flex-shrink:0"></i>
  <span><strong>{{ $pendingHotels }}</strong> hotel{{ $pendingHotels>1?'s':'' }} pending admin approval. They will be live once approved.</span>
</div>
@endif

{{-- Approval notice --}}
@if(isset($admin_setting) && $admin_setting->vendor_admin_approval == 1 && $vendor->status == 0)
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:rgba(227,30,36,.08);border:1px solid rgba(227,30,36,.2);border-radius:8px;margin-bottom:16px;font-size:13px;color:var(--red2)">
  <i class="ti ti-alert-triangle" style="font-size:18px;flex-shrink:0"></i>
  <span>{{ $admin_setting->admin_approval_notice ?? 'Your account is pending admin approval.' }}</span>
</div>
@endif

{{-- KPI Row 1: Core --}}
<div class="kpi-grid g4">
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon r"><i class="ti ti-calendar-check"></i></div><span class="delta neu">All time</span></div>
    <div class="kpi-val">{{ $totalBookings ?? 0 }}</div>
    <div class="kpi-lbl">Total bookings</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-currency-rupee"></i></div><span class="delta up">All time</span></div>
    <div class="kpi-val">{{ $fmt($totalRevenue ?? 0) }}</div>
    <div class="kpi-lbl">Total revenue (paid)</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-building"></i></div></div>
    <div class="kpi-val">{{ $totalHotels ?? 0 }}</div>
    <div class="kpi-lbl">
      My hotels
      @if(($approvedHotels ?? 0) > 0)<span style="font-size:10px;color:var(--green);margin-left:6px">{{ $approvedHotels }} live</span>@endif
    </div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon a"><i class="ti ti-bed"></i></div></div>
    <div class="kpi-val">{{ $totalRooms ?? 0 }}</div>
    <div class="kpi-lbl">My rooms</div>
  </div>
</div>

{{-- KPI Row 2: Today + Month --}}
<div class="kpi-grid g4">
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon c"><i class="ti ti-calendar-due"></i></div><span class="delta up">Today</span></div>
    <div class="kpi-val">{{ $todayBookings ?? 0 }}</div>
    <div class="kpi-lbl">Bookings today</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-coin"></i></div><span class="delta up">Today</span></div>
    <div class="kpi-val">{{ $fmt($todayRevenue ?? 0) }}</div>
    <div class="kpi-lbl">Revenue today</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon p"><i class="ti ti-currency-rupee"></i></div><span class="delta neu">This month</span></div>
    <div class="kpi-val">{{ $fmt($monthRevenue ?? 0) }}</div>
    <div class="kpi-lbl">Revenue this month</div>
  </div>
  <div class="kpi" style="{{ ($pendingBookings??0)>0?'border-color:rgba(245,158,11,.4)':'' }}">
    <div class="kpi-top">
      <div class="kpi-icon a"><i class="ti ti-clock-hour-4"></i></div>
      @if(($pendingBookings??0)>0)<span class="delta dn">Action needed</span>@endif
    </div>
    <div class="kpi-val">
      <a href="{{ route('vendor.room_bookings.unpaid_bookings',['language'=>$defaultLang->code??'en']) }}" style="color:inherit;text-decoration:none">{{ $pendingBookings ?? 0 }}</a>
    </div>
    <div class="kpi-lbl">Pending bookings</div>
  </div>
</div>

{{-- 3-column grid --}}
<div class="three-col">

  {{-- Recent Bookings --}}
  <div class="card" style="grid-column:span 2">
    <div class="card-hdr">
      <div><div class="card-title">Recent Bookings</div></div>
      <a href="{{ route('vendor.room_bookings.all_bookings',['language'=>$defaultLang->code??'en']) }}" class="btn btn-secondary btn-sm">View all</a>
    </div>
    <div class="tbl-wrap">
      <table>
        <thead><tr><th>Order</th><th>Guest</th><th>Room / Hotel</th><th>Check-in</th><th>Slot</th><th>Amount</th><th>Status</th><th></th></tr></thead>
        <tbody>
          @forelse($recentBookings ?? [] as $bk)
          @php $hr = (int)($bk->hour ?? 0); @endphp
          <tr>
            <td>
              <div class="td-main">#{{ $bk->order_number }}</div>
              <div class="td-sub">{{ $bk->created_at->format('d M') }}</div>
            </td>
            <td>
              <div class="td-pair">
                <div class="td-avatar">{{ strtoupper(substr($bk->booking_name??'?',0,1)) }}</div>
                <div><div class="td-main">{{ Str::limit($bk->booking_name??'—',18) }}</div><div class="td-sub">{{ $bk->booking_phone??'—' }}</div></div>
              </div>
            </td>
            <td>
              <div class="td-main" style="font-size:12px">{{ Str::limit(optional($bk->hotelRoom?->room_content->first())->name??'—',20) }}</div>
              <div class="td-sub">{{ Str::limit(optional($bk->hotel?->hotel_contents->first())->title??'—',20) }}</div>
            </td>
            <td>
              <div style="font-size:12px;font-weight:600">{{ $bk->check_in_date }}</div>
              <div class="td-sub">{{ $bk->check_in_time }}</div>
            </td>
            <td>
              @if($hr<=3)<span style="color:var(--red);font-size:10px;font-weight:700">3hr</span>
              @elseif($hr<=6)<span style="color:var(--blue);font-size:10px;font-weight:700">6hr</span>
              @elseif($hr<=12)<span style="color:var(--purple);font-size:10px;font-weight:700">12hr</span>
              @else<span style="color:var(--green);font-size:10px;font-weight:700">Full</span>@endif
            </td>
            <td class="fw6 text-green">{{ $sym }}{{ number_format($bk->grand_total??0,0) }}</td>
            <td>
              @if($bk->payment_status==1||$bk->payment_status=='paid')<span class="badge green">Paid</span>
              @elseif($bk->payment_status==2)<span class="badge red">Rejected</span>
              @else<span class="badge amber">Pending</span>@endif
            </td>
            <td>
              <a href="{{ route('vendor.room_bookings.booking_details',['language'=>$defaultLang->code??'en','id'=>$bk->id]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-eye"></i></a>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--muted)">
            <i class="ti ti-calendar-off" style="font-size:28px;display:block;margin-bottom:8px"></i>No bookings yet
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Right column --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Revenue by Hotel --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title">Revenue by Hotel</div></div>
      <div class="card-body">
        @forelse($revenueByHotel ?? [] as $row)
        <div class="bar-row">
          <div class="bar-lbl">{{ Str::limit($row->hotel_name??'—',14) }}</div>
          <div class="bar-track"><div class="bar-fill" style="width:{{ round(($row->total??0)/$maxHotelRev*100) }}%;background:var(--red)"></div></div>
          <div class="bar-val">{{ $sym }}{{ number_format(($row->total??0)/1000,1) }}k</div>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px"><i class="ti ti-chart-bar" style="font-size:24px;display:block;margin-bottom:6px"></i>No revenue data yet</div>
        @endforelse
      </div>
    </div>

    {{-- My Hotels --}}
    <div class="card">
      <div class="card-hdr">
        <div class="card-title">My Hotels</div>
        <a href="{{ route('vendor.hotel_management.create_hotel',['language'=>$defaultLang->code??'en']) }}" class="btn btn-primary btn-xs"><i class="ti ti-plus"></i></a>
      </div>
      <div>
        @forelse($myHotels ?? [] as $hotel)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border)">
          <div class="td-avatar av red" style="width:30px;height:30px;font-size:11px">{{ strtoupper(substr($hotel->hotel_name??'H',0,2)) }}</div>
          <div style="flex:1;min-width:0">
            <div style="font-size:12.5px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $hotel->hotel_name ?? '—' }}</div>
            <div style="font-size:11px;color:var(--muted)">{{ optional($hotel->city)->name ?? '—' }}</div>
          </div>
          @php $ap = $hotel->approval_status ?? 0; @endphp
          <span class="badge {{ $ap==1?'green':($ap==2?'red':'amber') }} badge-sm">{{ $ap==1?'Live':($ap==2?'Rejected':'Pending') }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px">
          <a href="{{ route('vendor.hotel_management.create_hotel',['language'=>$defaultLang->code??'en']) }}" style="color:var(--red)">Add your first hotel →</a>
        </div>
        @endforelse
      </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title">Quick Actions</div></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:7px">
        @php
          $actions = [
            ['icon'=>'ti-building-plus','color'=>'var(--blue)','label'=>'Add Hotel','sub'=>'Register new property','route'=>route('vendor.hotel_management.create_hotel',['language'=>$defaultLang->code??'en'])],
            ['icon'=>'ti-bed','color'=>'var(--purple)','label'=>'Add Room','sub'=>'Under your hotel','route'=>route('vendor.room_management.create_room',['language'=>$defaultLang->code??'en'])],
            ['icon'=>'ti-calendar-check','color'=>'var(--green)','label'=>'View Bookings','sub'=>($totalBookings??0).' total','route'=>route('vendor.room_bookings.all_bookings',['language'=>$defaultLang->code??'en'])],
            ['icon'=>'ti-arrows-exchange','color'=>'var(--cyan)','label'=>'Transactions','sub'=>'Payment history','route'=>route('vendor.transcation')],
            ['icon'=>'ti-package','color'=>'var(--amber)','label'=>'Buy / Extend Plan','sub'=>'Manage subscription','route'=>route('vendor.plan.extend.index')],
          ];
        @endphp
        @foreach($actions as $act)
        <a href="{{ $act['route'] }}"
           style="display:flex;align-items:center;gap:10px;background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:9px 12px;text-decoration:none"
           onmouseover="this.style.borderColor='var(--red)'" onmouseout="this.style.borderColor='var(--border)'">
          <i class="ti {{ $act['icon'] }}" style="font-size:16px;color:{{ $act['color'] }};flex-shrink:0"></i>
          <div>
            <div style="font-size:12px;font-weight:600;color:var(--text)">{{ $act['label'] }}</div>
            <div style="font-size:11px;color:var(--muted)">{{ $act['sub'] }}</div>
          </div>
          <i class="ti ti-chevron-right" style="margin-left:auto;color:var(--muted);font-size:12px"></i>
        </a>
        @endforeach
      </div>
    </div>

  </div>
</div>

@endsection
