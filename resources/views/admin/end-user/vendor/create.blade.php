@extends('admin.layout')
@section('section','People / Vendors')
@section('page','Add Vendor')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Add Vendor</h2><p>Register a new hotel partner account</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.vendor_management.registered_vendor', ['language' => $defaultLang->code]) }}"
       class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back to Vendors</a>
  </div>
</div>

<div style="max-width:800px">
  <div class="card">
    <div class="card-hdr"><div class="card-title">Vendor Information</div></div>
    <div class="card-body">
      <form id="commonForm" action="{{ route('admin.vendor_management.save-vendor') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Photo --}}
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Photo * (80×80 px)</label>
          <div style="display:flex;align-items:center;gap:14px">
            <div id="photoPreview" style="width:80px;height:80px;border-radius:8px;background:var(--navy3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
              <i class="ti ti-user" style="font-size:28px;color:var(--muted)"></i>
            </div>
            <div>
              <input type="file" name="photo" class="fc" accept="image/*"
                     onchange="previewPhoto(this)" style="width:auto">
              <div class="fc-hint">Exactly 80×80 pixels required</div>
              @error('photo')<p class="fs11 text-red">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>

        {{-- Basic fields --}}
        <div class="form-row c2" style="margin-bottom:16px">
          <div class="fg">
            <label class="flabel">Username *</label>
            <input type="text" name="username" class="fc" placeholder="Enter username" required>
            @error('username')<p class="fs11 text-red">{{ $message }}</p>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Password *</label>
            <input type="password" name="password" class="fc" placeholder="Min 6 characters" required>
            @error('password')<p class="fs11 text-red">{{ $message }}</p>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Email *</label>
            <input type="email" name="email" class="fc" placeholder="Enter email" required>
            @error('email')<p class="fs11 text-red">{{ $message }}</p>@enderror
          </div>
          <div class="fg">
            <label class="flabel">Phone</label>
            <input type="tel" name="phone" class="fc" placeholder="Enter phone">
            @error('phone')<p class="fs11 text-red">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- Language sections --}}
        @foreach($languages as $language)
        <div style="background:var(--navy3);border-radius:8px;margin-bottom:12px;overflow:hidden">
          <div style="padding:12px 14px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none"
               onclick="toggleLangSection('lang-{{ $language->id }}', this)">
            <span class="fw6 fs12">{{ $language->name }} {{ $language->is_default ? '(Default)' : '' }}</span>
            <i class="ti ti-chevron-{{ $language->is_default ? 'up' : 'down' }}" id="icon-lang-{{ $language->id }}"></i>
          </div>
          <div id="lang-{{ $language->id }}" style="{{ $language->is_default ? '' : 'display:none' }};padding:14px;border-top:1px solid var(--border)">
            <input type="hidden" name="lang_id[{{ $language->id }}]" value="{{ $language->id }}">
            <div class="form-row c2">
              <div class="fg">
                <label class="flabel">Contact Person Name</label>
                <input type="text" name="contact_person_name[{{ $language->id }}]" class="fc" placeholder="Enter name">
              </div>
              <div class="fg">
                <label class="flabel">Country</label>
                <input type="text" name="country[{{ $language->id }}]" class="fc" placeholder="Enter country">
              </div>
              <div class="fg">
                <label class="flabel">City</label>
                <input type="text" name="city[{{ $language->id }}]" class="fc" placeholder="Enter city">
              </div>
              <div class="fg">
                <label class="flabel">State</label>
                <input type="text" name="state[{{ $language->id }}]" class="fc" placeholder="Enter state">
              </div>
              <div class="fg">
                <label class="flabel">Zip Code</label>
                <input type="text" name="zip_code[{{ $language->id }}]" class="fc" placeholder="Enter zip code">
              </div>
              <div class="fg">
                <label class="flabel">Address</label>
                <input type="text" name="address[{{ $language->id }}]" class="fc" placeholder="Enter address">
              </div>
              <div class="fg form-span2">
                <label class="flabel">Details</label>
                <textarea name="details[{{ $language->id }}]" class="fc" rows="3" placeholder="About this vendor…"></textarea>
              </div>
            </div>
          </div>
        </div>
        @endforeach

        <div style="display:flex;gap:8px;margin-top:8px">
          <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Vendor</button>
          <a href="{{ route('admin.vendor_management.registered_vendor', ['language' => $defaultLang->code]) }}"
             class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
function previewPhoto(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var p = document.getElementById('photoPreview');
      p.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
function toggleLangSection(id, header) {
  var el = document.getElementById(id);
  var icon = document.getElementById('icon-' + id);
  if (el.style.display === 'none') {
    el.style.display = 'block';
    icon.className = 'ti ti-chevron-up';
  } else {
    el.style.display = 'none';
    icon.className = 'ti ti-chevron-down';
  }
}
</script>
@endsection
