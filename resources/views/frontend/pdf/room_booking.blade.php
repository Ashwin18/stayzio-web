<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Voucher #{{ $bookingInfo->order_number }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #1a1a2e; background: #fff; }

/* ── Page ── */
.page { padding: 0; }

/* ── Header Band ── */
.header-band { background: #e31e24; padding: 28px 36px 22px; position: relative; overflow: hidden; }
.header-band::after { content: ''; position: absolute; right: -40px; top: -40px; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,.08); }
.header-band::before { content: ''; position: absolute; right: 60px; bottom: -60px; width: 220px; height: 220px; border-radius: 50%; background: rgba(0,0,0,.06); }
.header-top { display: table; width: 100%; }
.header-logo { display: table-cell; vertical-align: middle; width: 50%; }
.header-logo img { height: 44px; max-width: 160px; }
.header-logo-text { font-size: 22px; font-weight: 900; color: #fff; letter-spacing: -0.5px; }
.header-right { display: table-cell; vertical-align: middle; text-align: right; }
.invoice-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: rgba(255,255,255,0.7); margin-bottom: 4px; }
.invoice-number { font-size: 20px; font-weight: 900; color: #fff; letter-spacing: 0.04em; font-family: monospace; }
.header-sub { display: table; width: 100%; margin-top: 16px; }
.header-sub-item { display: table-cell; color: rgba(255,255,255,0.85); font-size: 11px; }
.header-sub-item strong { display: block; color: #fff; font-size: 12px; margin-bottom: 2px; }

/* ── Status Badge ── */
.status-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; }
.status-paid { background: #dcfce7; color: #15803d; }
.status-pending { background: #fef9c3; color: #92400e; }

/* ── Body ── */
.body { padding: 28px 36px 0; }

/* ── Two Column ── */
.two-col { display: table; width: 100%; margin-bottom: 20px; border-spacing: 0 0; }
.col-left { display: table-cell; width: 52%; vertical-align: top; padding-right: 16px; }
.col-right { display: table-cell; width: 48%; vertical-align: top; }

/* ── Section Card ── */
.card { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px; margin-bottom: 16px; overflow: hidden; }
.card-hdr { background: #f1f3f5; border-bottom: 1px solid #dee2e6; padding: 9px 16px; }
.card-hdr-title { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.10em; color: #6c757d; }
.card-body { padding: 14px 16px; }

/* ── Info Rows ── */
.info-row { display: table; width: 100%; padding: 6px 0; border-bottom: 1px solid #e9ecef; }
.info-row:last-child { border-bottom: none; }
.info-label { display: table-cell; width: 46%; font-size: 11px; color: #6c757d; font-weight: 600; vertical-align: top; padding-top: 1px; }
.info-value { display: table-cell; font-size: 12px; color: #1a1a2e; font-weight: 700; }

/* ── Timeline Strip ── */
.timeline { background: linear-gradient(135deg, #fff5f5, #fff); border: 1px solid #fecaca; border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; display: table; width: 100%; }
.tl-item { display: table-cell; text-align: center; vertical-align: middle; }
.tl-item-left { text-align: left; }
.tl-item-right { text-align: right; }
.tl-label { font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.10em; color: #9ca3af; margin-bottom: 4px; }
.tl-value { font-size: 13px; font-weight: 900; color: #1a1a2e; }
.tl-time { font-size: 11px; font-weight: 700; color: #e31e24; margin-top: 2px; }
.tl-arrow { font-size: 20px; color: #e31e24; font-weight: 900; padding: 0 8px; }
.tl-duration { font-size: 16px; font-weight: 900; color: #e31e24; }

/* ── Price Table ── */
.price-table { width: 100%; border-collapse: collapse; }
.price-table tr td { padding: 8px 0; border-bottom: 1px solid #e9ecef; font-size: 12px; }
.price-table tr:last-child td { border-bottom: none; }
.price-label { color: #6c757d; font-weight: 600; }
.price-value { text-align: right; font-weight: 700; color: #1a1a2e; }
.price-total td { padding-top: 12px !important; border-top: 2px solid #e31e24 !important; }
.price-total .price-label { font-size: 14px; font-weight: 900; color: #1a1a2e; }
.price-total .price-value { font-size: 16px; font-weight: 900; color: #e31e24; }
.price-discount { color: #16a34a !important; }

/* ── QR / Booking ID Box ── */
.booking-id-box { background: linear-gradient(135deg, #e31e24, #b91219); border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; display: table; width: 100%; }
.bid-label { display: block; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.10em; color: rgba(255,255,255,0.7); margin-bottom: 5px; }
.bid-value { display: block; font-size: 17px; font-weight: 900; color: #fff; font-family: monospace; letter-spacing: 0.04em; }

/* ── Footer ── */
.footer { margin-top: 24px; padding: 16px 36px; background: #1a1a2e; display: table; width: 100%; }
.footer-left { display: table-cell; vertical-align: middle; }
.footer-left p { font-size: 11px; color: rgba(255,255,255,0.6); margin: 0; line-height: 1.6; }
.footer-left strong { color: #fff; }
.footer-right { display: table-cell; text-align: right; vertical-align: middle; }
.footer-right p { font-size: 10px; color: rgba(255,255,255,0.4); margin: 0; }
.footer-brand { font-size: 18px; font-weight: 900; color: #fff; }
.footer-brand span { color: #e31e24; }

/* ── Watermark ── */
.watermark-paid { position: fixed; top: 45%; right: 36px; font-size: 72px; font-weight: 900; color: rgba(22,163,74,0.07); text-transform: uppercase; letter-spacing: 0.2em; transform: rotate(-35deg); }
</style>
</head>
<body>
@php
  $sym  = $bookingInfo->currency_symbol ?? '₹';
  if ($sym === '₹') { $sym = 'Rs. '; } // dompdf's bundled font lacks the ₹ glyph, renders as "?" - use text symbol instead
  $hotelName = \App\Models\HotelContent::where('hotel_id', $bookingInfo->hotel_id)->value('title') ?? ('Hotel #' . $bookingInfo->hotel_id);
  $pos  = $bookingInfo->currency_symbol_position ?? 'left';
  $fmt  = fn($v) => $pos=='left' ? $sym.number_format($v,2) : number_format($v,2).$sym;
  $checkInDate  = \Carbon\Carbon::parse($bookingInfo->check_in_date)->format('d M Y');
  $checkInTime  = \Carbon\Carbon::parse($bookingInfo->check_in_time)->format('h:i A');
  $checkOutDate = \Carbon\Carbon::parse($bookingInfo->check_out_date)->format('d M Y');
  $checkOutTime = \Carbon\Carbon::parse($bookingInfo->check_out_time)->format('h:i A');
  $bookDate     = \Carbon\Carbon::parse($bookingInfo->created_at)->format('d M Y, h:i A');
  $isPaid       = $bookingInfo->payment_status == 1;
  $services     = json_decode($bookingInfo->service_details ?? '[]', true) ?? [];
  $serviceTotal = collect($services)->sum('price') ?? 0;
@endphp

@if($isPaid)<div class="watermark-paid">Paid</div>@endif

<div class="page">

{{-- ── HEADER ── --}}
<div class="header-band">
  <div class="header-top">
    <div class="header-logo">
      @if(!empty($websiteInfo->logo))
        <img src="{{ public_path('assets/img/'.$websiteInfo->logo) }}" alt="{{ $websiteInfo->website_title ?? 'StayZio' }}">
      @else
        <div class="header-logo-text">Stay<span style="color:rgba(255,255,255,0.7)">Zio</span></div>
      @endif
    </div>
    <div class="header-right">
      <div class="invoice-label">Booking Voucher</div>
      <div class="invoice-number">#{{ $bookingInfo->order_number }}</div>
      <div style="margin-top:6px">
        <span class="status-badge {{ $isPaid ? 'status-paid' : 'status-pending' }}">
          {{ $isPaid ? 'PAID' : 'PENDING' }}
        </span>
      </div>
    </div>
  </div>
  <div class="header-sub">
    <div class="header-sub-item">
      <strong>Issued On</strong>
      {{ $bookDate }}
    </div>
    <div class="header-sub-item" style="text-align:center">
      <strong>Payment Method</strong>
      {{ $bookingInfo->payment_method ?? 'Online Payment' }}
    </div>
    <div class="header-sub-item" style="text-align:right">
      <strong>Duration</strong>
      {{ $bookingInfo->hour }} Hour Stay
    </div>
  </div>
</div>

{{-- ── BODY ── --}}
<div class="body">

  {{-- Stay Timeline --}}
  <div class="timeline">
    <div class="tl-item tl-item-left">
      <div class="tl-label">Check-in</div>
      <div class="tl-value">{{ $checkInDate }}</div>
      <div class="tl-time">{{ $checkInTime }}</div>
    </div>
    <div class="tl-item" style="width:30px">
      <div class="tl-arrow">&#45;&gt;</div>
    </div>
    <div class="tl-item" style="text-align:center;width:80px">
      <div class="tl-label">Duration</div>
      <div class="tl-duration">{{ $bookingInfo->hour }} Hr</div>
    </div>
    <div class="tl-item" style="width:30px">
      <div class="tl-arrow">&#45;&gt;</div>
    </div>
    <div class="tl-item tl-item-right">
      <div class="tl-label">Check-out</div>
      <div class="tl-value">{{ $checkOutDate }}</div>
      <div class="tl-time" style="color:#6c757d">{{ $checkOutTime }}</div>
    </div>
  </div>

  {{-- Two Column --}}
  <div class="two-col">

    {{-- Left: Guest + Room Info --}}
    <div class="col-left">

      {{-- Booking ID --}}
      <div class="booking-id-box">
        <span class="bid-label">Booking Reference ID</span>
        <span class="bid-value">{{ $bookingInfo->order_number }}</span>
      </div>

      {{-- Guest Info --}}
      <div class="card">
        <div class="card-hdr"><div class="card-hdr-title">Guest Information</div></div>
        <div class="card-body">
          <div class="info-row">
            <div class="info-label">Guest Name</div>
            <div class="info-value">{{ $bookingInfo->booking_name }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $bookingInfo->booking_email ?? '—' }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Phone</div>
            <div class="info-value">{{ $bookingInfo->booking_phone }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Adults</div>
            <div class="info-value">{{ $bookingInfo->adult ?? 1 }}</div>
          </div>
          @if(($bookingInfo->children ?? 0) > 0)
          <div class="info-row">
            <div class="info-label">Children</div>
            <div class="info-value">{{ $bookingInfo->children }}</div>
          </div>
          @endif
        </div>
      </div>

      {{-- Room Info --}}
      <div class="card">
        <div class="card-hdr"><div class="card-hdr-title">Room & Booking Details</div></div>
        <div class="card-body">
          <div class="info-row">
            <div class="info-label">Hotel Name</div>
            <div class="info-value">{{ $hotelName }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Booking Date</div>
            <div class="info-value">{{ $bookDate }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Check-in</div>
            <div class="info-value">{{ $checkInDate }} at {{ $checkInTime }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Check-out</div>
            <div class="info-value">{{ $checkOutDate }} at {{ $checkOutTime }}</div>
          </div>
          <div class="info-row">
            <div class="info-label">Stay Duration</div>
            <div class="info-value">{{ $bookingInfo->hour }} Hour(s)</div>
          </div>
          <div class="info-row">
            <div class="info-label">Payment Via</div>
            <div class="info-value">{{ $bookingInfo->payment_method ?? '—' }}</div>
          </div>
        </div>
      </div>

    </div>

    {{-- Right: Price Breakdown --}}
    <div class="col-right">

      <div class="card">
        <div class="card-hdr"><div class="card-hdr-title">Price Breakdown</div></div>
        <div class="card-body">
          <table class="price-table">
            <tr>
              <td class="price-label">Room Rent ({{ $bookingInfo->hour }} Hr)</td>
              <td class="price-value">{{ $fmt($bookingInfo->roomPrice ?? 0) }}</td>
            </tr>
            @if($serviceTotal > 0)
            <tr>
              <td class="price-label">Add-on Services</td>
              <td class="price-value">{{ $fmt($serviceTotal) }}</td>
            </tr>
            @foreach($services as $svc)
            <tr>
              <td class="price-label" style="padding-left:12px;font-size:11px;color:#9ca3af">&#8627; {{ $svc['title'] ?? '' }}</td>
              <td class="price-value" style="font-size:11px;color:#9ca3af">{{ $fmt($svc['price'] ?? 0) }}</td>
            </tr>
            @endforeach
            @endif
            @if(($bookingInfo->discount ?? 0) > 0)
            <tr>
              <td class="price-label">Discount</td>
              <td class="price-value price-discount">-{{ $fmt($bookingInfo->discount) }}</td>
            </tr>
            @endif
            <tr>
              <td class="price-label">Subtotal</td>
              <td class="price-value">{{ $fmt($bookingInfo->total ?? 0) }}</td>
            </tr>
            @if(($bookingInfo->tax ?? 0) > 0)
            <tr>
              <td class="price-label">Tax / GST</td>
              <td class="price-value">{{ $fmt($bookingInfo->tax) }}</td>
            </tr>
            @endif
            <tr class="price-total">
              <td class="price-label">Total Paid</td>
              <td class="price-value">{{ $fmt($bookingInfo->grand_total) }}</td>
            </tr>
          </table>
        </div>
      </div>

      {{-- Add-on Services summary (if any) --}}
      @if($serviceTotal > 0)
      <div class="card">
        <div class="card-hdr"><div class="card-hdr-title">Add-on Services Included</div></div>
        <div class="card-body">
          @foreach($services as $svc)
          <div class="info-row">
            <div class="info-label">{{ $svc['title'] ?? 'Service' }}</div>
            <div class="info-value">{{ $fmt($svc['price'] ?? 0) }}</div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Terms --}}
      <div style="padding:12px 14px;background:#fff8f8;border:1px solid #fecaca;border-radius:10px;font-size:10px;color:#6c757d;line-height:1.7">
        <strong style="color:#1a1a2e;display:block;margin-bottom:4px">Terms & Conditions</strong>
        &bull; Show this voucher at hotel reception during check-in.<br>
        &bull; Booking is non-transferable.<br>
        &bull; Refund subject to cancellation policy.<br>
        &bull; Contact support for any disputes.
      </div>

    </div>
  </div>

</div>

{{-- ── FOOTER ── --}}
<div class="footer">
  <div class="footer-left">
    <p>
      <strong>Thank you for choosing {{ $websiteInfo->website_title ?? 'StayZio' }}!</strong><br>
      For support, contact us at our Help Center.<br>
      This is a computer-generated voucher and does not require a signature.
    </p>
  </div>
  <div class="footer-right">
    <div class="footer-brand">Stay<span>Zio</span></div>
    <p style="margin-top:4px">Hourly Hotel Booking Platform</p>
  </div>
</div>

</div>
</body>
</html>
