@extends('admin.layout')
@section('section','Support')
@section('page','Ticket Settings')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Support Ticket Settings</h2><p>Configure your support ticket system</p></div>
</div>
<div style="max-width:560px">
  <div class="card">
    <div class="card-hdr"><div class="card-title">Settings</div></div>
    <div class="card-body">
      <form action="{{ route('admin.support_ticket.update_setting') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display:flex;align-items:center;justify-content:space-between;background:var(--navy3);border-radius:8px;padding:14px;margin-bottom:14px">
          <div>
            <div class="fw6 fs12">Support Ticket System</div>
            <div class="fs11 text-muted" style="margin-top:3px">Enable or disable the customer support ticket module</div>
          </div>
          <div style="display:flex;gap:12px">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:12px">
              <input type="radio" name="support_ticket_status" value="active" {{ ($content->support_ticket_status??'active')=='active'?'checked':'' }}>
              <span class="text-green">Active</span>
            </label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:12px">
              <input type="radio" name="support_ticket_status" value="deactive" {{ ($content->support_ticket_status??'')=='deactive'?'checked':'' }}>
              <span class="text-red">Inactive</span>
            </label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Save Settings</button>
      </form>
    </div>
  </div>
</div>
@endsection
