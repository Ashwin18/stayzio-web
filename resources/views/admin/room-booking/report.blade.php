@extends('admin.layout')
@section('section','Bookings')
@section('page','Booking Report')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Booking Report</h2><p>Filter and export booking data</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.room_bookings.export_report') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success"><i class="ti ti-download"></i> Export Excel</a>
  </div>
</div>
<div class="card mb-14">
  <div class="card-body">
    <form action="{{ route('admin.room_bookings.report') }}" method="GET">
      <div class="form-row c4" style="margin-bottom:12px">
        <div class="fg"><label class="flabel">From date</label><input type="date" name="from" value="{{ request('from') }}" class="fc"></div>
        <div class="fg"><label class="flabel">To date</label><input type="date" name="to" value="{{ request('to') }}" class="fc"></div>
        <div class="fg"><label class="flabel">Payment gateway</label>
          <select name="payment_gateway" class="fc">
            <option value="">All gateways</option>
            @foreach($gateways ?? [] as $gw)<option value="{{ $gw }}" {{ request('payment_gateway')==$gw?'selected':'' }}>{{ ucfirst($gw) }}</option>@endforeach
          </select>
        </div>
        <div class="fg"><label class="flabel">Order status</label>
          <select name="order_status" class="fc">
            <option value="">All status</option>
            <option value="pending" {{ request('order_status')=='pending'?'selected':'' }}>Pending</option>
            <option value="confirmed" {{ request('order_status')=='confirmed'?'selected':'' }}>Confirmed</option>
            <option value="cancelled" {{ request('order_status')=='cancelled'?'selected':'' }}>Cancelled</option>
          </select>
        </div>
      </div>
      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary"><i class="ti ti-filter"></i> Generate Report</button>
        <a href="{{ route('admin.room_bookings.report') }}" class="btn btn-secondary"><i class="ti ti-x"></i> Clear</a>
      </div>
    </form>
  </div>
</div>

@if(isset($bookings) && $bookings->count())
<div class="kpi-grid g4 mb-14">
  <div class="kpi"><div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-currency-rupee"></i></div></div><div class="kpi-val">{{ $settings->base_currency_symbol }}{{ number_format($bookings->sum('grand_total'),0) }}</div><div class="kpi-lbl">Total revenue</div></div>
  <div class="kpi"><div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-calendar-check"></i></div></div><div class="kpi-val">{{ $bookings->total() }}</div><div class="kpi-lbl">Total bookings</div></div>
  <div class="kpi"><div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-circle-check"></i></div></div><div class="kpi-val">{{ $bookings->where('payment_status','paid')->count() }}</div><div class="kpi-lbl">Paid bookings</div></div>
  <div class="kpi"><div class="kpi-top"><div class="kpi-icon r"><i class="ti ti-clock"></i></div></div><div class="kpi-val">{{ $bookings->where('payment_status','pending')->count() }}</div><div class="kpi-lbl">Pending</div></div>
</div>
<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Order</th><th>Customer</th><th>Hotel / Room</th><th>Check-in</th><th>Slot</th><th>Amount</th><th>Gateway</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($bookings as $bk)
        @php $hr=(int)$bk->hour; @endphp
        <tr>
          <td class="td-main">#{{ $bk->order_number }}</td>
          <td><div class="td-main">{{ $bk->booking_name }}</div><div class="td-sub">{{ $bk->booking_email }}</div></td>
          <td>{{ optional($bk->hotel?->hotel_contents->first())->title ?? '—' }}</td>
          <td class="td-muted">{{ $bk->check_in_date }}</td>
          <td>@if($hr<=3)<span class="slot s3">3hr</span>@elseif($hr<=6)<span class="slot s6">6hr</span>@elseif($hr<=12)<span class="slot s12">12hr</span>@else<span class="slot sf">Full</span>@endif</td>
          <td class="fw6">{{ $bk->currency_symbol }}{{ number_format($bk->grand_total,0) }}</td>
          <td><span class="badge blue no-dot">{{ $bk->payment_method }}</span></td>
          <td>@if($bk->payment_status=='paid')<span class="badge green">Paid</span>@else<span class="badge amber">Pending</span>@endif</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $bookings->links() }}<span class="pg-info">{{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}</span></div>
</div>
@endif
@endsection
