{{-- Right Column --}}
<div>
  {{-- Payment --}}
  <div class="bkd-card">
    <div class="bkd-card-hdr">
      <h3>Payment Summary</h3>
      <span style="font-size:11px;font-weight:800;padding:4px 10px;border-radius:20px;background:{{ $stColor }}1a;color:{{ $stColor }}">{{ $stLabel }}</span>
    </div>
    <div class="bkd-card-body">
      <div class="bkd-price-row">
        <span>Room Price ({{ $b->hour }} Hr)</span>
        <b>{{ $fmt($roomPrice) }}</b>
      </div>
      <div class="bkd-price-row">
        <span>Add-on Services</span>
        <b>{{ $fmt($serviceTotal) }}</b>
      </div>
      <div class="bkd-price-row">
        <span>Discount</span>
        <b style="color:#16a34a">-{{ $fmt($b->discount??0) }}</b>
      </div>
      <div class="bkd-price-row">
        <span>Tax (GST)</span>
        <b>{{ $fmt($b->tax??0) }}</b>
      </div>
      <div class="bkd-price-row total">
        <span>Total Paid</span>
        <b>{{ $fmt($b->grand_total) }}</b>
      </div>
      <div style="margin-top:14px;padding:12px 14px;background:#f9f7f6;border-radius:12px">
        <div style="font-size:11px;color:#9aa0ae;font-weight:700;margin-bottom:4px">PAYMENT METHOD</div>
        <div style="font-size:14px;font-weight:800;color:#111">{{ $b->payment_gateway ?? 'Online Payment' }}</div>
      </div>
    </div>
  </div>
@include('frontend.user.booking.booking-details-cancel-inline')
</div>
</div>
</div>
