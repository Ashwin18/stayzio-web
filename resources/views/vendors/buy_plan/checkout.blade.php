@extends('vendors.layout')
@section('section','Account')
@section('page','Checkout')
@php Config::set('app.timezone', App\Models\BasicSettings\Basic::first()->timezone); @endphp

@section('content')
@php
  $sym = $settings->base_currency_symbol ?? '₹';
@endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Plan Checkout</h2>
    <p>Complete your subscription purchase</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.plan.extend.index') }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back to Plans</a>
  </div>
</div>

@if(session('error'))
<div style="background:rgba(227,30,36,.1);border:1px solid rgba(227,30,36,.25);color:#f87171;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:8px">
  <i class="ti ti-alert-circle"></i> {{ session('error') }}
</div>
@endif

@if(!empty($membership) && ($membership->package->term=='lifetime'||$membership->is_trial==1))
<div style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);color:var(--amber);border-radius:8px;padding:12px 16px;margin-bottom:16px">
  <i class="ti ti-alert-triangle"></i>
  Purchasing <strong>{{ $package->title }}</strong> will immediately replace your current plan <strong>{{ $membership->package->title }}</strong>.
</div>
@endif

<div class="two-col-eq" style="max-width:900px;align-items:start">

  {{-- Plan Summary --}}
  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card">
      <div class="card-hdr"><div class="card-title">Order Summary</div></div>
      <div class="card-body">
        <div class="stat-row">
          <span class="stat-lbl">Package</span>
          <span class="stat-val">{{ $package->title }}</span>
        </div>
        <div class="stat-row">
          <span class="stat-lbl">Term</span>
          <span class="stat-val">{{ ucfirst($package->term) }}</span>
        </div>
        <div class="stat-row">
          <span class="stat-lbl">Price</span>
          <span class="stat-val" style="font-size:18px;color:var(--green)">
            @if($package->price==0) Free @else {{ $sym }}{{ number_format($package->price,0) }}@endif
          </span>
        </div>
        <div class="stat-row">
          <span class="stat-lbl">Start Date</span>
          <span class="stat-val">
            @if((!empty($previousPackage)&&$previousPackage->term=='lifetime')||(!empty($membership)&&$membership->is_trial==1))
              {{ \Carbon\Carbon::today()->format('d M Y') }}
            @else
              {{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d M Y') }}
            @endif
          </span>
        </div>
        <div class="stat-row">
          <span class="stat-lbl">Expires</span>
          <span class="stat-val">
            @if($package->term=='lifetime') Lifetime
            @elseif($package->term=='monthly')
              @if((!empty($previousPackage)&&$previousPackage->term=='lifetime')||(!empty($membership)&&$membership->is_trial==1))
                {{ \Carbon\Carbon::now()->addMonth()->format('d M Y') }}
              @else
                {{ \Carbon\Carbon::parse($membership->expire_date??now())->addMonth()->format('d M Y') }}
              @endif
            @else
              @if((!empty($previousPackage)&&$previousPackage->term=='lifetime')||(!empty($membership)&&$membership->is_trial==1))
                {{ \Carbon\Carbon::now()->addYear()->format('d M Y') }}
              @else
                {{ \Carbon\Carbon::parse($membership->expire_date??now())->addYear()->format('d M Y') }}
              @endif
            @endif
          </span>
        </div>
        <hr class="divider">
        <div class="pkg-feature"><i class="ti ti-building"></i> {{ $package->number_of_hotel >= 999999 ? 'Unlimited hotels' : $package->number_of_hotel.' hotels' }}</div>
        <div class="pkg-feature"><i class="ti ti-bed"></i> {{ $package->number_of_room >= 999999 ? 'Unlimited rooms' : $package->number_of_room.' rooms' }}</div>
        <div class="pkg-feature"><i class="ti ti-calendar-check"></i> {{ $package->number_of_bookings >= 999999 ? 'Unlimited bookings' : $package->number_of_bookings.' bookings' }}</div>
      </div>
    </div>
  </div>

  {{-- Payment --}}
  <div class="card">
    <div class="card-hdr"><div class="card-title">Payment</div></div>
    <div class="card-body">
      <form id="my-checkout-form" action="{{ route('vendor.plan.checkout') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="package_id" value="{{ $package->id }}">
        <input type="hidden" name="vendor_id" value="{{ auth()->id() }}">
        <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">

        {{-- Hidden date fields --}}
        @if((!empty($previousPackage)&&$previousPackage->term=='lifetime')||(!empty($membership)&&$membership->is_trial==1))
          <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::yesterday()->format('d-m-Y') }}">
          @if($package->term=='monthly')
            <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::now()->addMonth()->format('d-m-Y') }}">
          @elseif($package->term=='lifetime')
            <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::maxValue()->format('d-m-Y') }}">
          @else
            <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::now()->addYear()->format('d-m-Y') }}">
          @endif
        @else
          <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}">
          @if($package->term=='monthly')
            <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::parse($membership->expire_date??now())->addMonth()->format('d-m-Y') }}">
          @elseif($package->term=='lifetime')
            <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::maxValue()->format('d-m-Y') }}">
          @else
            <input type="hidden" name="expire_date" value="{{ \Carbon\Carbon::parse($membership->expire_date??now())->addYear()->format('d-m-Y') }}">
          @endif
        @endif

        @if($package->price > 0)
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Payment Method *</label>
          <select name="payment_method" id="payment-gateway" class="fc" required onchange="handleGatewayChange(this.value)">
            <option value="">Select payment method</option>
            @foreach($online_gateways ?? [] as $gw)
              <option value="{{ $gw->name }}" {{ old('payment_method')==$gw->name?'selected':'' }}>{{ $gw->name }}</option>
            @endforeach
            @foreach($offline_gateways ?? [] as $gw)
              <option value="{{ $gw->name }}" {{ old('payment_method')==$gw->name?'selected':'' }}>{{ $gw->name }} (Offline)</option>
            @endforeach
          </select>
        </div>

        {{-- Offline gateway fields --}}
        <div id="offline-fields" style="display:none;margin-bottom:16px">
          <div class="fg" style="margin-bottom:12px">
            <label class="flabel">Transaction / Reference ID</label>
            <input type="text" name="anet_transaction_key" class="fc" placeholder="Enter transaction reference">
          </div>
          <div class="fg">
            <label class="flabel">Payment Receipt (optional)</label>
            <input type="file" name="receipt" class="fc" accept="image/*,application/pdf">
          </div>
        </div>

        {{-- Stripe element placeholder --}}
        <div id="stripe-element" style="display:none;margin-bottom:16px">
          <div id="card-element" style="background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px"></div>
          <div id="stripe-errors" style="color:#f87171;font-size:12px;margin-top:6px"></div>
        </div>
        @endif

        <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px">
          <i class="ti ti-shopping-cart"></i>
          @if($package->price==0) Activate Free Plan @else Pay {{ $sym }}{{ number_format($package->price,0) }}@endif
        </button>
      </form>
    </div>
  </div>

</div>

@endsection
@section('script')
<script>
var offlineData = @json($offline_gateways ?? []);
function handleGatewayChange(val){
  var isOffline = offlineData.some(function(gw){ return gw.name === val; });
  document.getElementById('offline-fields').style.display = isOffline ? 'block' : 'none';
  document.getElementById('payment').value = val;
}
</script>
@if(!empty($stripe_key))
<script src="https://js.stripe.com/v3/"></script>
@endif
@endsection
