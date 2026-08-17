@extends('admin.layout')
@section('section','People')
@section('page','Customer Details')

@section('content')
@php
  $rbookings = $user->bookHotelRoom()->orderBy('id','desc')->limit(20)->get();
  $totalBookings = $user->bookHotelRoom()->count();
  $paidBookings  = $user->bookHotelRoom()->where('payment_status', 1)->count();
  $totalSpend    = $user->bookHotelRoom()->where('payment_status', 1)->sum('grand_total');
  $lastBooking   = $user->bookHotelRoom()->latest()->first();
@endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Customer Details</h2>
    <p>Customer profile and booking history</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.user_management.user.edit', ['id' => $user->id]) }}" class="btn btn-secondary btn-sm">
      <i class="ti ti-edit"></i> Edit
    </a>
    <form action="{{ route('admin.user_management.registered_user.delete', ['id' => $user->id]) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this customer?')">
      @csrf
      <button type="submit" class="btn btn-danger btn-sm"><i class="ti ti-trash"></i> Delete</button>
    </form>
    <a href="{{ route('admin.user_management.registered_users') }}" class="btn btn-secondary btn-sm">
      <i class="ti ti-arrow-left"></i> Back
    </a>
  </div>
</div>

<div class="two-col" style="align-items:start;gap:16px">

  {{-- LEFT: Profile + Stats + Mail --}}
  <div style="display:flex;flex-direction:column;gap:14px;min-width:0">

    {{-- Profile card --}}
    <div class="card">
      <div class="card-body" style="padding:24px 16px!important">
        <div style="display:flex;align-items:center;gap:16px">
          <div style="width:68px;height:68px;border-radius:50%;background:var(--navy3);border:2px solid var(--border);overflow:hidden;flex-shrink:0">
            @if($user->image)
              <img src="{{ asset('assets/img/users/'.$user->image) }}" style="width:100%;height:100%;object-fit:cover">
            @else
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:700;color:var(--muted)">
                {{ strtoupper(substr($user->username ?? $user->email ?? 'U', 0, 1)) }}
              </div>
            @endif
          </div>
          <div style="min-width:0">
            <div style="font-size:16px;font-weight:700;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
              {{ $user->first_name ? $user->first_name.' '.$user->last_name : ($user->username ?? 'Customer') }}
            </div>
            <div style="font-size:12px;color:var(--muted);margin-top:2px;overflow:hidden;text-overflow:ellipsis">
              {{ $user->email }}
            </div>
            <div style="margin-top:6px">
              @if($user->status == 1)
                <span class="badge green">Active</span>
              @else
                <span class="badge red">Inactive</span>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Info table --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-info-circle" style="color:var(--blue)"></i> Profile Info</div></div>
      <div class="card-body" style="padding:0!important">
        @php
          $rows = [
            ['Username',    $user->username],
            ['Phone',       $user->contact_number],
            ['Address',     $user->address],
            ['City',        $user->city],
            ['State',       $user->state],
            ['Country',     $user->country],
            ['Joined',      \Carbon\Carbon::parse($user->created_at)->format('d M Y')],
          ];
        @endphp
        @foreach($rows as $row)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-bottom:1px solid var(--border)">
          <span style="font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em">{{ $row[0] }}</span>
          <span style="font-size:13px;font-weight:500;color:var(--text)">{{ $row[1] ?: '—' }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Booking Stats --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-chart-bar" style="color:var(--green)"></i> Booking Stats</div></div>
      <div class="card-body" style="padding:0!important">
        @php
          $stats = [
            ['Total bookings',  $totalBookings,                         'var(--text)'],
            ['Paid bookings',   $paidBookings,                          'var(--green)'],
            ['Total spend',     symbolPrice($totalSpend),               'var(--amber)'],
            ['Last booking',    $lastBooking ? \Carbon\Carbon::parse($lastBooking->created_at)->format('d M Y') : '—', 'var(--text)'],
          ];
        @endphp
        @foreach($stats as $s)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid var(--border)">
          <span style="font-size:12px;color:var(--muted)">{{ $s[0] }}</span>
          <span style="font-size:14px;font-weight:700;color:{{ $s[2] }}">{{ $s[1] }}</span>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Send Mail --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-mail" style="color:var(--purple)"></i> Send Email</div></div>
      <div class="card-body">
        <form action="{{ route('admin.user_management.registered_user.send_mail', ['id' => $user->id]) }}" method="post">
          @csrf
          <div class="fg" style="margin-bottom:12px">
            <label class="flabel">To</label>
            <input type="email" name="email" class="fc" value="{{ $user->email }}" readonly style="opacity:.7">
          </div>
          <div class="fg" style="margin-bottom:12px">
            <label class="flabel">Subject *</label>
            <input type="text" name="subject" class="fc" placeholder="Enter email subject">
          </div>
          <div class="fg" style="margin-bottom:14px">
            <label class="flabel">Message *</label>
            <textarea name="message" class="fc" rows="4" placeholder="Type your message..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block">
            <i class="ti ti-send"></i> Send Email
          </button>
        </form>
      </div>
    </div>

  </div>

  {{-- RIGHT: Bookings table --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    <div class="card">
      <div class="card-hdr">
        <div>
          <div class="card-title"><i class="ti ti-calendar-check" style="color:var(--amber)"></i> Recent Bookings</div>
          <div class="card-sub">Last 20 bookings by this customer</div>
        </div>
        <a href="{{ route('admin.room_bookings.all_bookings', ['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm">
          All Bookings
        </a>
      </div>
      <div class="tbl-wrap">
        @if($rbookings->count() == 0)
        <div style="padding:40px;text-align:center;color:var(--muted)">
          <i class="ti ti-calendar-off" style="font-size:36px;margin-bottom:8px;display:block"></i>
          No bookings found for this customer
        </div>
        @else
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Room / Hotel</th>
              <th>Check-In</th>
              <th>Slot</th>
              <th>Amount</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rbookings as $booking)
            @php
              $roomhave = $booking->hotelRoom ? true : false;
              $roomInfo = $roomhave
                ? $booking->hotelRoom->room_content->where('language_id',$defaultLang->id)->first()
                : null;
            @endphp
            <tr>
              <td>
                <div class="td-main">#{{ substr($booking->order_number,0,10) }}</div>
                <div class="td-sub">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</div>
              </td>
              <td>
                @if($roomInfo)
                  <a href="{{ route('frontend.room.details',['id'=>$roomInfo->room_id,'slug'=>$roomInfo->slug]) }}"
                    target="_blank" style="color:var(--blue);font-size:12px;font-weight:600">
                    {{ Str::limit($roomInfo->title,22) }}
                  </a>
                @else
                  <span style="color:var(--muted)">—</span>
                @endif
              </td>
              <td>
                <div style="font-size:12px;color:var(--text)">{{ $booking->check_in_date ?? '—' }}</div>
                <div class="td-sub">{{ $booking->check_in_time ?? '' }}</div>
              </td>
              <td>
                <span class="badge blue no-dot">{{ $booking->booking_hour ?? '—' }}</span>
              </td>
              <td style="font-weight:700;color:var(--text)">{{ symbolPrice($booking->grand_total) }}</td>
              <td>
                @if($booking->payment_status == 1)
                  <span class="badge green">Paid</span>
                @elseif($booking->payment_status == 0)
                  <span class="badge amber">Pending</span>
                @else
                  <span class="badge red">Rejected</span>
                @endif
              </td>
              <td>
                @if($booking->order_status == 'cancelled')
                  <span class="badge red">Cancelled</span>
                @elseif($booking->order_status == 'confirmed')
                  <span class="badge green">Confirmed</span>
                @else
                  <span class="badge amber">{{ ucfirst($booking->order_status ?? 'pending') }}</span>
                @endif
              </td>
              <td>
                <div style="display:flex;gap:5px">
                  <a href="{{ route('admin.room_bookings.booking_details',['id'=>$booking->id,'language'=>$defaultLang->code]) }}"
                    class="btn btn-info btn-xs">View</a>
                  @if($roomhave)
                  <a href="{{ route('admin.room_bookings.booking_details_and_edit',['id'=>$booking->id,'language'=>$defaultLang->code]) }}"
                    class="btn btn-secondary btn-xs">Edit</a>
                  @endif
                  <a href="{{ asset('assets/file/invoices/room/'.$booking->invoice) }}" target="_blank"
                    class="btn btn-secondary btn-xs"><i class="ti ti-file"></i></a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>

  </div>
</div>

@includeIf('admin.room-booking.show-attachment')
@endsection
