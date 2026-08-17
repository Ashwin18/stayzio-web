  <section>

    <div class="szb-card">
      <div class="szb-card-top">
        <img src="{{ $hotelImg ?? asset('assets/img/noimage.jpg') }}" 
             onerror="this.src='{{ asset('assets/img/noimage.jpg') }}'"
             alt="{{ $hotelTitle }}" style="width:80px;height:68px;object-fit:cover;border-radius:14px;border:1px solid #f0e8e6;flex-shrink:0">
        <div>
          <h2>{{ $hotelTitle }}</h2>
          <p><i class="fas fa-map-marker-alt" style="color:#e31e24;font-size:11px;margin-right:3px"></i>{{ $cityName }}{{ $cityName && $countryName ? ', ' : '' }}{{ $countryName }}</p>
        </div>
      </div>

      <div class="szb-card-body">

        <div class="szb-timeline">
          <div class="szb-tl-item" style="text-align:left">
            <span>Check-in</span>
            <b>{{ $checkInFmt }}</b>
            <div style="font-size:12px;color:#e31e24;font-weight:700;margin-top:3px">{{ $checkInTime }}</div>
          </div>
          <div class="szb-tl-sep">&#8594;</div>
          <div class="szb-tl-item">
            <span>Duration</span>
            <b style="color:#e31e24">{{ $hour }} Hr</b>
          </div>
          <div class="szb-tl-sep">&#8594;</div>
          <div class="szb-tl-item" style="text-align:right">
            <span>Check-out</span>
            <b>{{ $checkOutFmt }}</b>
            <div style="font-size:12px;color:#7a8394;font-weight:700;margin-top:3px">{{ $checkOutTime }}</div>
          </div>
        </div>

        <div class="szb-grid">
          <div class="szb-cell">
            <span>Room</span>
            <b>{{ optional($roomContent)->title ?? 'Room' }}</b>
          </div>
          <div class="szb-cell">
            <span>Guests</span>
            <b>{{ $b->adult ?? 1 }} Adult{{ ($b->adult??1)>1?'s':'' }}{{ ($b->children??0)>0 ? ' &middot; '.$b->children.' Child' : '' }}</b>
          </div>
          <div class="szb-cell">
            <span>Guest Name</span>
            <b>{{ $b->booking_name ?? optional(Auth::guard('web')->user())->name ?? '&#8212;' }}</b>
          </div>
          <div class="szb-cell">
            <span>Contact</span>
            <b>{{ $b->booking_phone ?? '&#8212;' }}</b>
          </div>
        </div>

@include('frontend.room.booking-success-card2')
      </div>
    </div>
  </section>

</div>
</main>