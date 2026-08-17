@extends('vendors.layout')
@section('section','Bookings')
@section('page','All Bookings')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Bookings</h2><p>{{ $bookings->total() }} total bookings</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.room_bookings.calendar',['language'=>$defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-calendar"></i> Calendar View</a>
    <a href="{{ route('vendor.room_bookings.booking_form',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> New Booking</a>
  </div>
</div>
<div class="card">
  <form action="{{ route('vendor.room_bookings.all_bookings') }}" method="GET" id="bkSearch">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <input name="booking_no" class="fc" placeholder="Order number…" value="{{ request('booking_no') }}" style="width:160px">
      <input name="title" class="fc" placeholder="Room title…" value="{{ request('title') }}" style="width:160px">
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i> Search</button>
      <a href="{{ route('vendor.room_bookings.all_bookings',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th>#</th><th>Guest</th><th>Room / Hotel</th><th>Check In</th><th>Duration</th><th>Amount</th><th>Payment</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($bookings as $bk)
      @php
        $hc = optional($bk->hotel)->hotel_contents->first();
        $rc = optional($bk->hotelRoom)->room_content->first();
        $h  = $bk->hour??0;
      @endphp
      <tr>
        <td class="td-muted">#{{ $bk->order_number }}</td>
        <td>
          <div class="td-pair">
            <div class="td-avatar">{{ strtoupper(substr($bk->booking_name??'?',0,1)) }}</div>
            <div><div class="td-main">{{ $bk->booking_name??'—' }}</div><div class="td-sub">{{ $bk->booking_phone??$bk->booking_email??'—' }}</div></div>
          </div>
        </td>
        <td>
          <div class="td-main">{{ Str::limit(optional($rc)->name??'—',30) }}</div>
          <div class="td-sub">{{ Str::limit(optional($hc)->title??'—',30) }}</div>
        </td>
        <td>
          <div class="td-main">{{ \Carbon\Carbon::parse($bk->check_in_date)->format('d M Y') }}</div>
          <div class="td-sub">{{ $bk->check_in_time }}</div>
        </td>
        <td>
          @if($h<=3)<span class="slot s3">{{ $h }}hr</span>
          @elseif($h<=6)<span class="slot s6">{{ $h }}hr</span>
          @elseif($h<=12)<span class="slot s12">{{ $h }}hr</span>
          @else<span class="slot sf">Full Day</span>@endif
        </td>
        <td>
          <div class="fw6 text-green">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format(($bk->grand_total??0) - ($bk->commission_price??0),0) }}</div>
          @if(($bk->commission_price??0) > 0)
          <div class="td-sub" style="font-size:10.5px">Booking {{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($bk->grand_total??0,0) }} · Comm. {{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($bk->commission_price??0,0) }}</div>
          @endif
        </td>
        <td>
          @if($bk->payment_status==1)<span class="badge green">Paid</span>
          @elseif($bk->payment_status==2)<span class="badge red">Rejected</span>
          @else<span class="badge amber">Pending</span>@endif
        </td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('vendor.room_bookings.booking_details',['language'=>$defaultLang->code,'id'=>$bk->id]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-eye"></i></a>
            @if($bk->invoice)
            <a href="{{ asset('assets/file/invoices/room/'.$bk->invoice) }}" target="_blank" class="btn btn-secondary btn-xs" title="Download Customer Voucher"><i class="ti ti-file-download"></i></a>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-calendar-off" style="font-size:32px;display:block;margin-bottom:10px"></i>No bookings found</td></tr>
      @endforelse
    </tbody>
  </table></div>
  <div class="pagination">{{ $bookings->appends(request()->all())->links() }}<span class="pg-info">{{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}</span></div>
</div>
@endsection
