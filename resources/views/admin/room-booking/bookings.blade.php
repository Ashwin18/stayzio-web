{{-- ============================================================
     resources/views/admin/room-booking/bookings.blade.php
     ============================================================ --}}
@extends('admin.layout')
@section('section','Bookings')
@section('page','Bookings')

@section('content')
@php
  $pageTitle = request()->routeIs('admin.room_bookings.paid_bookings') ? 'Paid Bookings'
    : (request()->routeIs('admin.room_bookings.unpaid_bookings') ? 'Unpaid Bookings' : 'All Bookings');
@endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>{{ $pageTitle }}</h2>
    <p>{{ $bookings->total() }} bookings found</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.room_bookings.export_report') }}" class="btn btn-secondary"><i class="ti ti-download"></i> Export CSV</a>
    <a href="{{ route('admin.room_bookings.booking_form',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> New Booking</a>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
  <a href="{{ route('admin.room_bookings.all_bookings',['language'=>$defaultLang->code]) }}" class="ftag {{ request()->routeIs('admin.room_bookings.all_bookings')?'active':'' }}"><i class="ti ti-list"></i> All</a>
  <a href="{{ route('admin.room_bookings.paid_bookings',['language'=>$defaultLang->code]) }}" class="ftag {{ request()->routeIs('admin.room_bookings.paid_bookings')?'active':'' }}">
    <i class="ti ti-circle-check" style="color:var(--sz-green)"></i> Paid
  </a>
  <a href="{{ route('admin.room_bookings.unpaid_bookings',['language'=>$defaultLang->code]) }}" class="ftag {{ request()->routeIs('admin.room_bookings.unpaid_bookings')?'active':'' }}">
    <i class="ti ti-clock" style="color:var(--sz-amber)"></i> Unpaid
  </a>
</div>

<div class="card">
  <form action="{{ request()->url() }}" method="GET" id="bookingFilter">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <input name="booking_no" class="fc" placeholder="Booking #" value="{{ request('booking_no') }}" style="width:130px">
      <input name="title" class="fc" placeholder="Hotel / room…" value="{{ request('title') }}" style="width:150px">
      <input name="date" type="date" class="fc" value="{{ request('date') }}">
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <div class="filter-spacer"></div>
      <a href="{{ request()->url() }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th>Order</th><th>Customer</th><th>Hotel / Room</th><th>Check-in</th><th>Slot</th><th>Amount</th><th>Method</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($bookings as $bk)
        @php $hr = (int)$bk->hour; @endphp
        <tr>
          <td>
            <div class="td-main">#{{ $bk->order_number }}</div>
            <div class="td-sub">{{ $bk->created_at->format('d M Y') }}</div>
          </td>
          <td>
            <div class="td-main">{{ $bk->booking_name }}</div>
            <div class="td-sub">{{ $bk->booking_phone }}</div>
          </td>
          <td>
            <div class="td-main" style="font-size:12px">{{ Str::limit(optional($bk->hotel?->hotel_contents->first())->title??'—',28) }}</div>
            <div class="td-sub">{{ optional($bk->hotelRoom?->room_content->first())->name??'—' }}</div>
          </td>
          <td>
            <div style="font-size:12px;font-weight:600;color:var(--sz-text)">{{ $bk->check_in_date }}</div>
            <div class="td-sub">{{ $bk->check_in_time }}</div>
          </td>
          <td>
            @if($hr<=3)<span style="background:rgba(232,25,44,.1);color:var(--sz-red);padding:3px 8px;border-radius:6px;font-size:11px;font-weight:700">3hr</span>
            @elseif($hr<=6)<span style="background:rgba(37,99,235,.1);color:var(--sz-blue);padding:3px 8px;border-radius:6px;font-size:11px;font-weight:700">6hr</span>
            @elseif($hr<=12)<span style="background:rgba(124,58,237,.1);color:var(--sz-purple);padding:3px 8px;border-radius:6px;font-size:11px;font-weight:700">12hr</span>
            @else<span style="background:rgba(22,163,74,.1);color:var(--sz-green);padding:3px 8px;border-radius:6px;font-size:11px;font-weight:700">Full</span>@endif
          </td>
          <td class="fw6">{{ $bk->currency_symbol }}{{ number_format($bk->grand_total,0) }}</td>
          <td><span class="badge blue no-dot">{{ ucfirst($bk->payment_method) }}</span></td>
          <td>
            @if($bk->payment_status=='paid'||$bk->payment_status==1)<span class="badge green">Paid</span>
            @elseif($bk->payment_status==2)<span class="badge red">Rejected</span>
            @else<span class="badge amber">Pending</span>@endif
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('admin.room_bookings.booking_details',['id'=>$bk->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="View"><i class="ti ti-eye"></i></a>
              <a href="{{ route('admin.room_bookings.booking_details_and_edit',$bk->id) }}" class="btn btn-secondary btn-xs" title="Edit"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.room_bookings.delete_booking',$bk->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this booking?')">@csrf
                <button class="btn btn-danger btn-xs" title="Delete"><i class="ti ti-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:48px;color:var(--sz-muted)">
          <i class="ti ti-calendar-off" style="font-size:36px;display:block;margin-bottom:10px"></i>No bookings found
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    {{ $bookings->appends(request()->query())->links() }}
    <span class="pg-info">{{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}</span>
  </div>
</div>
@endsection
