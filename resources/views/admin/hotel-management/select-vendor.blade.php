@extends('admin.layout')
@section('section','Property / Hotels')
@section('page','Add Hotel — Select Vendor')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Add Hotel</h2><p>Step 1 — select the vendor for this hotel</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>
<div style="max-width:560px">
  <div class="card">
    <div class="card-hdr"><div class="card-title"><i class="ti ti-building-store" style="color:var(--amber)"></i>  Select Vendor</div></div>
    <div class="card-body">
      <form id="vendorSelect" action="{{ route('admin.hotel_management.find_vendor_id') }}" method="POST">
        @csrf
        <input type="hidden" name="language" value="{{ $defaultLang->code }}">
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Assign Hotel to Vendor</label>
          <select name="vendor_id" class="fc">
            <option value="0">Admin (no vendor)</option>
            @foreach($vendors as $v)<option value="{{ $v->id }}">{{ $v->username }}</option>@endforeach
          </select>
          <div class="fc-hint">If no vendor selected, the hotel will be managed by Admin.</div>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-arrow-right"></i> Next — Configure Hotel</button>
      </form>
    </div>
  </div>
</div>
@endsection
