      <div class="szb-price">
          <div class="szb-price-row">
            <span>Room Price ({{ $hour }} Hr)</span>
            <b>{{ $fmt($roomPrice) }}</b>
          </div>
          {!! $serviceTotal > 0 ? '<div class="szb-price-row"><span>Add-on Services</span><b>'.$fmt($serviceTotal).'</b></div>' : '' !!}
          {!! ($b->discount??0)>0 ? '<div class="szb-price-row"><span>Discount</span><b style="color:#16a34a">-'.$fmt($b->discount).'</b></div>' : '' !!}
          {!! ($b->tax??0)>0 ? '<div class="szb-price-row"><span>Taxes &amp; Fees (GST)</span><b>'.$fmt($b->tax).'</b></div>' : '' !!}
          <div class="szb-price-row total">
            <span>Total Paid</span>
            <b>{{ $fmt($b->grand_total) }}</b>
          </div>
        </div>

        <div class="szb-actions">
          <a href="{{ $b->invoice ? asset('assets/file/invoices/room/'.$b->invoice) : '#' }}"
             class="szb-btn-primary" {{ $b->invoice ? 'download' : '' }}>
            <i class="fas fa-file-pdf"></i> Download Voucher
          </a>
          <a href="{{ url('/') }}" class="szb-btn-sec">
            <i class="fas fa-home"></i> Back to Home
          </a>
        </div>

        <a href="https://wa.me/?text={{ urlencode('Booking Confirmed! Hotel: '.$hotelTitle.' Date: '.$checkInFmt.' Booking ID: '.$b->order_number) }}" target="_blank" class="szb-wa">
          <i class="fab fa-whatsapp" style="font-size:18px"></i> Share on WhatsApp
        </a>

        <div class="szb-help">
          <div>
            <b>Need help with this booking?</b>
            <span>Our support team is available 24/7</span>
          </div>
          <a href="{{ url('/contact') }}"><i class="fas fa-headset"></i> Contact Support</a>
        </div>

      </div>
    </div>
  </section>

</div>
</main>