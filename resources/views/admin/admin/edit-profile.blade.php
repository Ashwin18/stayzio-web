@extends('admin.layout')
@section('section','Admin')
@section('page','Edit Profile')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Edit Profile</h2><p>Update your admin account details</p></div>
</div>
<div class="two-col-eq">
  <div class="card">
    <div class="card-hdr"><div class="card-title">Profile Information</div></div>
    <div class="card-body">
      <form id="editProfileForm" action="{{ route('admin.update_profile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Profile Photo</label>
          @if(!empty($adminInfo->image))
          <img src="{{ asset('assets/img/admins/'.$adminInfo->image) }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;display:block;margin-bottom:8px">
          @endif
          <input type="file" name="image" class="fc" accept="image/*">
          @error('image')<p class="fs11 text-red">{{ $message }}</p>@enderror
        </div>
        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg"><label class="flabel">Username *</label><input type="text" name="username" class="fc" value="{{ $adminInfo->username }}">@error('username')<p class="fs11 text-red">{{ $message }}</p>@enderror</div>
          <div class="fg"><label class="flabel">Email *</label><input type="email" name="email" class="fc" value="{{ $adminInfo->email }}">@error('email')<p class="fs11 text-red">{{ $message }}</p>@enderror</div>
          <div class="fg"><label class="flabel">First Name</label><input type="text" name="first_name" class="fc" value="{{ $adminInfo->first_name }}"></div>
          <div class="fg"><label class="flabel">Last Name</label><input type="text" name="last_name" class="fc" value="{{ $adminInfo->last_name }}"></div>
          <div class="fg form-span2"><label class="flabel">Address</label><input type="text" name="address" class="fc" value="{{ $adminInfo->address }}">@error('address')<p class="fs11 text-red">{{ $message }}</p>@enderror</div>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Update Profile</button>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-hdr"><div class="card-title">Change Password</div></div>
    <div class="card-body">
      <form action="{{ route('admin.update_password') }}" method="POST">
        @csrf
        <div class="form-row" style="margin-bottom:14px">
          <div class="fg"><label class="flabel">Current Password *</label><input type="password" name="current_password" class="fc" placeholder="Enter current password"></div>
          <div class="fg"><label class="flabel">New Password *</label><input type="password" name="new_password" class="fc" placeholder="Min 8 characters"></div>
          <div class="fg"><label class="flabel">Confirm New Password *</label><input type="password" name="new_password_confirmation" class="fc" placeholder="Repeat new password"></div>
        </div>
        <button type="submit" class="btn btn-warn btn-block"><i class="ti ti-lock"></i> Update Password</button>
      </form>
    </div>
  </div>
</div>
@endsection
