@extends('admin.layout')
@section('section','People')
@section('page','Edit Customer')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Edit Customer</h2><p>Update account details for {{ $user->first_name }} {{ $user->last_name }}</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.user_management.registered_user.view',['id'=>$user->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back to Profile</a>
  </div>
</div>

<div style="max-width:700px">
  <div class="card">
    <div class="card-hdr"><div class="card-title">Account Information</div></div>
    <div class="card-body">
      <form id="ajaxEditForm" action="{{ route('admin.user_management.registered_user.update',['id'=>$user->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Profile photo --}}
        <div style="text-align:center;margin-bottom:20px">
          @if(!empty($user->image))
            <img src="{{ asset('assets/img/users/'.$user->image) }}" id="imgPreview" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border);margin-bottom:10px">
          @else
            <div id="imgPreview" style="width:80px;height:80px;border-radius:50%;background:var(--red-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:28px;font-weight:800;color:var(--red2)">
              {{ strtoupper(substr($user->first_name??'U',0,1)) }}
            </div>
          @endif
          <div>
            <label class="btn btn-secondary btn-sm" style="cursor:pointer;margin:0">
              <i class="ti ti-upload"></i> Change Photo
              <input type="file" name="image" style="display:none" accept="image/*" onchange="previewImg(this)">
            </label>
          </div>
        </div>

        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">First Name *</label>
            <input type="text" name="first_name" class="fc" value="{{ old('first_name',$user->first_name) }}" required>
            @error('first_name')<div style="font-size:11px;color:var(--red2);margin-top:3px">{{ $message }}</div>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Last Name *</label>
            <input type="text" name="last_name" class="fc" value="{{ old('last_name',$user->last_name) }}" required>
            @error('last_name')<div style="font-size:11px;color:var(--red2);margin-top:3px">{{ $message }}</div>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Username *</label>
            <input type="text" name="username" class="fc" value="{{ old('username',$user->username) }}" required>
            @error('username')<div style="font-size:11px;color:var(--red2);margin-top:3px">{{ $message }}</div>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Email *</label>
            <input type="email" name="email" class="fc" value="{{ old('email',$user->email) }}" required>
            @error('email')<div style="font-size:11px;color:var(--red2);margin-top:3px">{{ $message }}</div>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Phone</label>
            <input type="text" name="contact_number" class="fc" value="{{ old('contact_number',$user->contact_number) }}">
          </div>
          <div class="fg">
            <label class="flabel">Status</label>
            <select name="status" class="fc">
              <option value="1" {{ $user->status==1?'selected':'' }}>Active</option>
              <option value="0" {{ $user->status==0?'selected':'' }}>Inactive</option>
            </select>
          </div>
        </div>

        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Address</label>
          <input type="text" name="address" class="fc" value="{{ old('address',$user->address) }}">
        </div>

        <div class="form-row c3" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">City</label>
            <input type="text" name="city" class="fc" value="{{ old('city',$user->city) }}">
          </div>
          <div class="fg">
            <label class="flabel">State</label>
            <input type="text" name="state" class="fc" value="{{ old('state',$user->state) }}">
          </div>
          <div class="fg">
            <label class="flabel">Country</label>
            <input type="text" name="country" class="fc" value="{{ old('country',$user->country) }}">
          </div>
        </div>

        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">New Password <span style="color:var(--muted);font-weight:400">(leave blank to keep current)</span></label>
          <input type="password" name="password" class="fc" placeholder="Enter new password">
          @error('password')<div style="font-size:11px;color:var(--red2);margin-top:3px">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:10px">
          <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Save Changes</button>
          <a href="{{ route('admin.user_management.registered_user.view',['id'=>$user->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
@section('script')
<script>
function previewImg(input){
  if(input.files&&input.files[0]){
    var reader=new FileReader();
    reader.onload=function(e){
      var prev=document.getElementById('imgPreview');
      if(prev.tagName==='IMG'){prev.src=e.target.result;}
      else{
        var img=document.createElement('img');
        img.src=e.target.result;
        img.id='imgPreview';
        img.style.cssText='width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border);display:block;margin:0 auto 10px';
        prev.replaceWith(img);
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endsection
