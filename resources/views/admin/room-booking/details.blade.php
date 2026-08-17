@extends('admin.layout')
@section('section','Bookings')
@section('page','Booking Details')

@section('content')
@php $cur = $details->currency_symbol; $pos = $details->currency_symbol_position; $p=fn($v)=>$pos=='left'?$cur.number_format($v,2):number_format($v,2).$cur; @endphp
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Booking #{{ $details->order_number }}</h2><p>{{ $details->created_at->format('d M Y, h:i A') }}</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.room_bookings.all_bookings', ['language' => $defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
    <a href="{{ route('admin.room_bookings.booking_details_and_edit', $details->id) }}" class="btn btn-primary"><i class="ti ti-edit"></i> Edit Booking</a>
  </div>
</div>

<div class="two-col">
  {{-- Left: booking info --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-calendar-check" style="color:var(--red)"></i>  Booking Summary</div>
        @if($details->payment_status==1)<span class="badge green">Paid</span>@elseif($details->payment_status==2)<span class="badge red">Rejected</span>@else<span class="badge amber">Pending</span>@endif
      </div>
      <div class="card-body">
        <div class="form-row c2">
          <div><div class="flabel">Check-in</div><div class="fw6" style="margin-top:4px">{{ $details->check_in_date }} · {{ $details->check_in_time }}</div></div>
          <div><div class="flabel">Check-out</div><div class="fw6" style="margin-top:4px">{{ $details->check_out_date }} · {{ $details->check_out_time }}</div></div>
          <div><div class="flabel">Duration</div><div class="fw6" style="margin-top:4px">{{ $details->hour }} hours
            @if($details->hour<=3)<span class="slot s3">3hr</span>@elseif($details->hour<=6)<span class="slot s6">6hr</span>@elseif($details->hour<=12)<span class="slot s12">12hr</span>@else<span class="slot sf">Full day</span>@endif
          </div></div>
          <div><div class="flabel">Guests</div><div class="fw6" style="margin-top:4px">{{ $details->adult ?? 0 }} adults, {{ $details->children ?? 0 }} children</div></div>
        </div>
        <hr class="divider">
        <div class="stat-row"><span class="stat-lbl">Room rent</span><span class="stat-val">{{ $p($details->roomPrice) }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Service charge</span><span class="stat-val">{{ $p($details->serviceCharge) }}</span></div>
        @if($details->discount)<div class="stat-row"><span class="stat-lbl">Discount</span><span class="stat-val text-green">− {{ $p($details->discount) }}</span></div>@endif
        @if($details->tax)<div class="stat-row"><span class="stat-lbl">Tax</span><span class="stat-val">{{ $p($details->tax) }}</span></div>@endif
        <div class="stat-row" style="border-top:1px solid var(--border);padding-top:10px;margin-top:4px">
          <span class="stat-lbl fw6" style="color:var(--text)">Grand total</span>
          <span class="stat-val" style="font-size:18px;color:var(--green)">{{ $p($details->grand_total) }}</span>
        </div>
        <hr class="divider">
        <div class="stat-row"><span class="stat-lbl">Payment method</span><span class="stat-val"><span class="badge blue no-dot">{{ ucfirst($details->payment_method) }}</span></span></div>
        <div class="stat-row"><span class="stat-lbl">Gateway type</span><span class="stat-val">{{ ucfirst($details->gateway_type ?? '—') }}</span></div>
        @if($details->additional_service)
        <div class="stat-row"><span class="stat-lbl">Additional services</span><span class="stat-val text-amber">Yes</span></div>
        @endif
      </div>
    </div>

    {{-- Update status --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title">Update Booking Status</div></div>
      <div class="card-body">
        <form action="{{ route('admin.room_bookings.update_payment_status') }}" method="POST">
          @csrf
          <input type="hidden" name="id" value="{{ $details->id }}">
          <div class="form-row c2" style="margin-bottom:12px">
            <div class="fg">
              <label class="flabel">Order status</label>
              <select name="order_status" class="fc">
                <option value="pending" {{ $details->order_status=='pending'?'selected':'' }}>Pending</option>
                <option value="confirmed" {{ $details->order_status=='confirmed'?'selected':'' }}>Confirmed</option>
                <option value="cancelled" {{ $details->order_status=='cancelled'?'selected':'' }}>Cancelled</option>
              </select>
            </div>
            <div class="fg">
              <label class="flabel">Payment status</label>
              <select name="payment_status" class="fc">
                <option value="0" {{ $details->payment_status==0?'selected':'' }}>Pending</option>
                <option value="1" {{ $details->payment_status==1?'selected':'' }}>Paid</option>
                <option value="2" {{ $details->payment_status==2?'selected':'' }}>Rejected</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Update Status</button>
        </form>
        <hr class="divider">
        <a href="{{ route('admin.room_bookings.send_mail') }}" class="btn btn-secondary btn-block"><i class="ti ti-mail"></i> Send Mail to Customer</a>
      </div>
    </div>
  </div>

  {{-- Right: hotel, room, customer info --}}
  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-user"></i>  Customer Details</div></div>
      <div class="card-body">
        <div class="stat-row"><span class="stat-lbl">Name</span><span class="stat-val">{{ $details->booking_name }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Email</span><span class="stat-val">{{ $details->booking_email }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Phone</span><span class="stat-val">{{ $details->booking_phone }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Address</span><span class="stat-val">{{ $details->booking_address ?? '—' }}</span></div>
      </div>
    </div>
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-building"></i>  Hotel & Room</div></div>
      <div class="card-body">
        <div class="stat-row"><span class="stat-lbl">Hotel</span><span class="stat-val">{{ optional($details->hotel?->hotel_contents->first())->title ?? '—' }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Room</span><span class="stat-val">{{ optional($details->hotelRoom?->room_content->first())->name ?? '—' }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Vendor</span><span class="stat-val">{{ $details->vendor->username ?? '—' }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Preparation time</span><span class="stat-val">{{ $details->preparation_time ?? 0 }} min</span></div>
      </div>
    </div>
    @if($details->attachment)
    <div class="card">
      <div class="card-hdr"><div class="card-title">Attachment</div></div>
      <div class="card-body">
        <a href="{{ route('admin.room_bookings.show_attachment', ['id' => $details->id]) }}" class="btn btn-info btn-block"><i class="ti ti-file"></i> View Attachment</a>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
