@php
  $hourVal = $booking->hour ?? '';
  $isFullDay = ($hourVal == 'Full Day' || $hourVal == '24' || intval($hourVal) >= 24);
  $checkInDt = $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date) : null;
  $checkOutDt = $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date) : null;
  $checkInTime = $booking->check_in_time ? \Carbon\Carbon::parse($booking->check_in_time)->format('h:i A') : '';
  $checkOutTime = $booking->check_out_time ? \Carbon\Carbon::parse($booking->check_out_time)->format('h:i A') : '';
  $bookedAt = \Carbon\Carbon::parse($booking->created_at);
  $nights = ($isFullDay && $checkInDt && $checkOutDt) ? $checkInDt->diffInDays($checkOutDt) : 0;
  $isCancelled = ($booking->order_status ?? '') === 'cancelled';
  $now = \Carbon\Carbon::now();
  $isPast = $checkInDt && \Carbon\Carbon::parse($booking->check_in_date.' '.$booking->check_in_time)->isPast();
  $statusFilter = $isCancelled ? 'cancelled' : ($isPast ? 'past' : 'upcoming');
  $sym = '₹';
  $fmt = fn($v) => $sym.number_format($v, 0);
  $hotelLogo = $booking->hotel_logo ?? null;
  $hotelImagePath = $hotelLogo ? asset('assets/img/hotel/logo/'.$hotelLogo) : null;
  $hotelInitial = strtoupper(substr($booking->hotel_name ?? 'H', 0, 2));
@endphp

<div class="mb-card {{ $isCancelled ? 'cancelled' : '' }}" data-status="{{ $statusFilter }}">
  <div class="mb-img">
    @if($hotelImagePath)
      <img src="{{ $hotelImagePath }}" alt="{{ $booking->hotel_name }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
      <div class="mb-img-fallback" style="display:none">{{ $hotelInitial }}</div>
    @else
      <div class="mb-img-fallback">{{ $hotelInitial }}</div>
    @endif
  </div>
  <div class="mb-mid">
    <b>{{ $booking->hotel_name ?? 'Hotel' }}</b>
    <div class="mb-loc"><i class="fas fa-map-marker-alt"></i> {{ \Illuminate\Support\Str::limit($booking->hotel_address ?? '', 50) }}</div>
    <div class="mb-tags">
      @if($isFullDay)
        <span class="mb-tag mb-tag-fullday">FULL DAY{{ $nights > 1 ? ' · '.$nights.' NIGHTS' : '' }}</span>
      @else
        <span class="mb-tag mb-tag-hourly">{{ $hourVal }} HOURS</span>
      @endif
      @if($isCancelled)
        <span class="mb-tag mb-tag-cancelled">CANCELLED</span>
      @elseif($booking->payment_status == 1)
        <span class="mb-tag mb-tag-paid">PAID</span>
      @else
        <span class="mb-tag mb-tag-pending">PENDING</span>
      @endif
    </div>
    <div class="mb-dt">
      @if($isFullDay)
        <div class="mb-dt-row"><i class="fas fa-sign-in-alt"></i> Check-in <b>{{ $checkInDt ? $checkInDt->format('D, d M') : '—' }} · {{ $checkInTime }}</b></div>
        <div class="mb-dt-row"><i class="fas fa-sign-out-alt"></i> Check-out <b>{{ $checkOutDt ? $checkOutDt->format('D, d M Y') : '—' }} · {{ $checkOutTime }}</b></div>
      @else
        <div class="mb-dt-row"><i class="far fa-calendar"></i> <b>{{ $checkInDt ? $checkInDt->format('D, d M Y') : '—' }}</b> · {{ $checkInTime }} to {{ $checkOutTime }}</div>
      @endif
    </div>
    @if($isCancelled && ($booking->cancellation_refund_amount ?? 0) > 0)
      <div class="mb-refund-msg"><i class="fas fa-redo"></i> Refund {{ $fmt($booking->cancellation_refund_amount) }} in progress (5-7 days)</div>
    @elseif($isCancelled)
      <div class="mb-refund-msg"><i class="fas fa-info-circle"></i> No refund applicable</div>
    @else
      <div class="mb-booked"><i class="far fa-clock"></i> Booked on {{ $bookedAt->format('d M, h:i A') }} · {{ $booking->room_qty ?? 1 }} Room{{ ($booking->room_qty ?? 1) > 1 ? 's' : '' }} · {{ $booking->adult ?? 1 }} Adult{{ ($booking->adult ?? 1) > 1 ? 's' : '' }}</div>
    @endif
  </div>
  <div class="mb-r">
    <div class="mb-amt {{ $isCancelled ? 'cancelled' : '' }}">{{ $fmt($booking->grand_total) }}</div>
    <div class="mb-lbl {{ $isCancelled ? 'cancelled' : '' }}">{{ $isCancelled ? 'Cancelled' : 'Total Paid' }}</div>
    @if(!$isCancelled)
    <div class="mb-acts">
      <a class="mb-btn mb-btn-view" href="{{ route('user.room_booking_details', $booking->id) }}">
        <i class="far fa-eye"></i> View
      </a>
      @if(!$isPast)
      <button type="button" class="mb-btn mb-btn-cancel" onclick="cancelBookingViaJS({{ $booking->id }}, '{{ $booking->order_number }}')">
        <i class="fas fa-times"></i> Cancel
      </button>
      @endif
    </div>
    @endif
  </div>
</div>
