<canvas id="sz-confetti"></canvas>

<main class="szb-page">
<div class="szb-shell">

  <section class="szb-hero">
    <div class="szb-check">&#10003;</div>
    <h1>Booking<br>Confirmed! &#127881;</h1>
    <p>Your StayZio booking is confirmed. Show this Booking ID at the hotel reception during check-in.</p>
    <div class="szb-id-box">
      <div>
        <span>Booking ID</span>
        <b id="szBookingId">{{ $b->order_number }}</b>
      </div>
      <button class="szb-copy-btn" onclick="szCopyId()">
        <i class="fas fa-copy"></i> Copy
      </button>
    </div>
    <div class="szb-badges">
      <div class="szb-badge"><i class="fas fa-shield-alt"></i> 100% Verified</div>
      <div class="szb-badge"><i class="fas fa-bolt"></i> Instant Confirmed</div>
      <div class="szb-badge"><i class="fas fa-lock"></i> Secure Payment</div>
    </div>
  </section>

@include('frontend.room.booking-success-card')
</div>
</main>