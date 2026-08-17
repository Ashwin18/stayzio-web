@extends('vendors.layout')
@section('section','Finance')
@section('page','New Withdrawal')
@section('content')
@php $balance = optional(Auth::guard('vendor')->user())->amount ?? 0; @endphp
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Withdraw Funds</h2><p>Available balance: <strong style="color:var(--green)">{{ $settings->base_currency_symbol??'₹' }}{{ number_format($balance,0) }}</strong></p></div>
  <div class="page-hdr-actions"><a href="{{ route('vendor.withdraw') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a></div>
</div>
<div style="max-width:600px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('vendor.withdraw.send-request') }}" method="POST">
        @csrf
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Withdrawal Method *</label>
          <select name="method_id" class="fc" required>
            <option value="">Select method</option>
            @foreach($methods as $m)
            <option value="{{ $m->id }}">{{ $m->name }}</option>
            @endforeach
          </select>
          @error('method_id')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
        </div>
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Amount ({{ $settings->base_currency_text??'INR' }}) *</label>
          <input type="number" name="amount" class="fc" placeholder="Enter withdrawal amount" min="1" max="{{ $balance }}" required value="{{ old('amount') }}">
          <div style="font-size:11px;color:var(--muted);margin-top:3px">Min: {{ $settings->base_currency_symbol }}{{ $settings->min_withdraw_amount??0 }} — Max: {{ $settings->base_currency_symbol }}{{ number_format($balance,0) }}</div>
          @error('amount')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
        </div>
        <div id="methodFields"></div>
        <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px"><i class="ti ti-credit-card"></i> Submit Request</button>
      </form>
    </div>
  </div>
</div>
@endsection
