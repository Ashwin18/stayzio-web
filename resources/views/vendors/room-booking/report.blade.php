@extends('vendors.layout')
@section('section','Bookings')
@section('page','Report')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Booking Report</h2><p>Filter and export booking data</p></div>
</div>
<div class="card" style="margin-bottom:14px">
  <form action="{{ route('vendor.room_bookings.report',['language'=>$defaultLang->code]) }}" method="GET">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <div class="fg"><label class="flabel">From</label><input type="date" name="from_date" class="fc" value="{{ request('from_date') }}" style="width:150px"></div>
      <div class="fg"><label class="flabel">To</label><input type="date" name="to_date" class="fc" value="{{ request('to_date') }}" style="width:150px"></div>
      <div class="fg"><label class="flabel">Payment</label>
        <select name="payment_status" class="fc" style="width:120px">
          <option value="">All</option>
          <option value="1" {{ request('payment_status')=='1'?'selected':'' }}>Paid</option>
          <option value="0" {{ request('payment_status')=='0'?'selected':'' }}>Pending</option>
        </select>
      </div>
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-primary btn-sm" style="align-self:flex-end"><i class="ti ti-search"></i> Filter</button>
      <a href="{{ route('vendor.room_bookings.report',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm" style="align-self:flex-end"><i class="ti ti-x"></i></a>
    </div>
  </form>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr><th>#</th><th>Order</th><th>Guest</th><th>Hotel / Room</th><th>Check-in</th><th>Duration</th><th>Amount</th><th>Payment</th></tr></thead>
    <tbody>
      @forelse($bookings??[] as $bk)
      @php
        $hc=optional($bk->hotel)->hotel_contents->first();
        $rc=optional($bk->hotelRoom)->room_content->first();
        $h=$bk->hour??0;
      @endphp
      <tr>
        <td class="td-muted">{{ $loop->iteration }}</td>
        <td class="td-main">#{{ $bk->order_number }}</td>
        <td>{{ $bk->booking_name??'—' }}</td>
        <td><div class="td-main">{{ Str::limit(optional($rc)->name??'—',25) }}</div><div class="td-sub">{{ Str::limit(optional($hc)->title??'—',25) }}</div></td>
        <td class="td-muted">{{ \Carbon\Carbon::parse($bk->check_in_date)->format('d M Y') }}</td>
        <td>@if($h<=3)<span class="slot s3">{{ $h }}hr</span>@elseif($h<=6)<span class="slot s6">{{ $h }}hr</span>@elseif($h<=12)<span class="slot s12">{{ $h }}hr</span>@else<span class="slot sf">Full</span>@endif</td>
        <td class="fw6 text-green">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($bk->grand_total??0,0) }}</td>
        <td>@if($bk->payment_status==1)<span class="badge green">Paid</span>@else<span class="badge amber">Pending</span>@endif</td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)">No bookings match your filter</td></tr>
      @endforelse
    </tbody>
  </table></div>
  @if(isset($bookings) && method_exists($bookings, 'links'))
  <div class="pagination">{{ $bookings->appends(request()->all())->links() }}</div>
  @endif
</div>
@endsection
