@extends('admin.layout')
@section('section','People')
@section('page','Add Customer')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Add Customer</h2>
    <p>Create a new customer account</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.user_management.registered_users') }}" class="btn btn-secondary btn-sm">
      <i class="ti ti-arrow-left"></i> Back
    </a>
    <button type="submit" form="userForm" class="btn btn-primary">
      <i class="ti ti-device-floppy"></i> Save Customer
    </button>
  </div>
</div>

<div class="two-col-eq" style="align-items:start;gap:16px">

  {{-- LEFT: Photo + Account --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    <div class="card">
      <div class="card-hdr">
        <div class="card-title"><i class="ti ti-user-circle" style="color:var(--blue)"></i> Profile Photo</div>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;align-items:center;gap:12px;padding:24px 16px!important">
        <div style="width:100px;height:100px;border-radius:50%;background:var(--navy3);border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden">
          <img id="photoPreview" src="{{ asset('assets/img/noimage.jpg') }}"
            style="width:100%;height:100%;object-fit:cover;display:none">
          <i class="ti ti-user" style="font-size:40px;color:var(--muted)" id="photoIcon"></i>
        </div>
        <label class="btn btn-secondary btn-sm" style="cursor:pointer;margin:0">
          <i class="ti ti-upload"></i> Choose Photo
          <input type="file" name="image" style="display:none" accept="image/*" onchange="
            var r=new FileReader();
            r.onload=function(e){
              document.getElementById('photoPreview').src=e.target.result;
              document.getElementById('photoPreview').style.display='block';
              document.getElementById('photoIcon').style.display='none';
            };
            r.readAsDataURL(this.files[0])">
        </label>
        <div class="fc-hint">Recommended: 80×80px</div>
        <p id="editErr_image" class="text-danger em" style="font-size:11px;margin:0"></p>
      </div>
    </div>

    <div class="card">
      <div class="card-hdr">
        <div class="card-title"><i class="ti ti-lock" style="color:var(--amber)"></i> Account Security</div>
      </div>
      <div class="card-body">
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Password *</label>
          <div style="position:relative">
            <input type="password" name="password" class="fc" id="pwd" placeholder="Min. 6 characters" style="padding-right:40px">
            <button type="button" onclick="var i=document.getElementById('pwd');i.type=i.type==='password'?'text':'password'"
              style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;font-size:16px">
              <i class="ti ti-eye"></i>
            </button>
          </div>
          <div class="fc-hint">Minimum 6 characters required</div>
          <p id="editErr_password" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
        </div>
        <div class="fg">
          <label class="flabel">Status</label>
          <select name="status" class="fc">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
    </div>

  </div>

  {{-- RIGHT: Personal Details --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    <div class="card">
      <div class="card-hdr">
        <div class="card-title"><i class="ti ti-id-badge" style="color:var(--green)"></i> Personal Details</div>
      </div>
      <div class="card-body">
        <form id="userForm" action="{{ route('admin.user_management.registered_user.store') }}" method="post" enctype="multipart/form-data">
          @csrf

          <div class="form-row c2" style="margin-bottom:14px">
            <div class="fg">
              <label class="flabel">Full Name *</label>
              <input type="text" name="name" class="fc" placeholder="Enter full name">
              <p id="editErr_name" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
            <div class="fg">
              <label class="flabel">Username *</label>
              <input type="text" name="username" class="fc" placeholder="Enter username">
              <p id="editErr_username" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
          </div>

          <div class="form-row c2" style="margin-bottom:14px">
            <div class="fg">
              <label class="flabel">Email Address *</label>
              <input type="email" name="email" class="fc" placeholder="Enter email">
              <p id="editErr_email" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
            <div class="fg">
              <label class="flabel">Phone Number</label>
              <input type="tel" name="phone" class="fc" placeholder="Enter phone">
              <p id="editErr_phone" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
          </div>

          <hr style="border-color:var(--border);margin:16px 0">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px">Location</div>

          <div class="form-row c3" style="margin-bottom:14px">
            <div class="fg">
              <label class="flabel">Country</label>
              <input type="text" name="country" class="fc" placeholder="e.g. India">
              <p id="editErr_country" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
            <div class="fg">
              <label class="flabel">State</label>
              <input type="text" name="state" class="fc" placeholder="e.g. Tamil Nadu">
              <p id="editErr_state" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
            <div class="fg">
              <label class="flabel">City</label>
              <input type="text" name="city" class="fc" placeholder="e.g. Chennai">
              <p id="editErr_city" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
          </div>

          <div class="form-row c2" style="margin-bottom:0">
            <div class="fg">
              <label class="flabel">Address</label>
              <input type="text" name="address" class="fc" placeholder="Street address">
              <p id="editErr_address" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
            <div class="fg">
              <label class="flabel">Zip Code</label>
              <input type="text" name="zip_code" class="fc" placeholder="e.g. 600001">
              <p id="editErr_zip_code" class="text-danger em" style="font-size:11px;margin:4px 0 0"></p>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div style="display:flex;gap:10px">
      <button type="submit" form="userForm" class="btn btn-primary btn-block">
        <i class="ti ti-device-floppy"></i> Save Customer
      </button>
      <a href="{{ route('admin.user_management.registered_users') }}" class="btn btn-secondary">Cancel</a>
    </div>

  </div>
</div>
@endsection
