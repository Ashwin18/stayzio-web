@extends('admin.layout')
@section('section','Property / Rooms')
@section('page','Add Room — Select Vendor')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Add Room</h2><p>Step 1 — select which vendor this room belongs to</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.room_management.rooms',['language'=>$defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>
<div style="max-width:560px">
  <div class="card">
    <div class="card-hdr"><div class="card-title"><i class="ti ti-building-store" style="color:var(--amber)"></i>  Select Vendor</div></div>
    <div class="card-body">
      <div id="vendorMessage" style="display:none;background:rgba(227,30,36,.1);border:1px solid rgba(227,30,36,.2);color:#f87171;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:13px"></div>
      <form id="vendorSelect" action="{{ route('admin.room_management.find_vendor_id') }}" method="POST">
        @csrf
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Assign Room to Vendor</label>
          <select name="vendor_id" class="fc">
            <option value="0">Admin (no vendor)</option>
            @foreach($vendors as $v)<option value="{{ $v->id }}">{{ $v->username }}</option>@endforeach
          </select>
          <div class="fc-hint">If no vendor selected, this room will be listed under Admin.</div>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-arrow-right"></i> Next — Configure Room</button>
      </form>
    </div>
  </div>
</div>
@endsection
