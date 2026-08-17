@extends('admin.layout')
@section('section','Hotel Management')
@section('page','Add Hotel')

@section('content')
@php
  $vendorId = $vendor_id;
  $numberoffImages = $numberoffImages ?? 99999999;
@endphp

<div class="page-hdr">
  <div class="page-hdr-left"><h2>Add Hotel</h2><p>Create a new hotel listing</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back</a>
    <button type="submit" form="hotelForm" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Hotel</button>
  </div>
</div>

<div class="alert alert-danger pb-1 d-none" id="hotelErrors" style="margin-bottom:14px"><ul></ul></div>

{{-- Gallery --}}
<div class="card" style="margin-bottom:14px">
  <div class="card-hdr"><div class="card-title"><i class="ti ti-photo" style="color:var(--blue)"></i> Gallery Images</div></div>
  <div class="card-body">
    <form action="{{ route('admin.hotel_management.hotel.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata" class="dropzone create">
      @csrf<div class="fallback"><input name="file" type="file" multiple></div>
    </form>
    <div class="fc-hint" style="margin-top:6px">Max {{ $numberoffImages }} images allowed</div>
  </div>
</div>

<form id="hotelForm" action="{{ route('admin.hotel_management.store_hotel') }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="vendor_id" value="{{ $vendorId }}">

<div class="two-col" style="align-items:start;gap:16px">

  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Logo --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-camera" style="color:var(--red)"></i> Hotel Logo *</div></div>
      <div class="card-body" style="display:flex;flex-direction:column;align-items:center;gap:12px">
        <img id="logoPreview" src="{{ asset('assets/admin/img/noimage.jpg') }}" style="width:100%;max-height:180px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
        <label class="btn btn-secondary btn-sm" style="cursor:pointer;margin:0;width:100%;justify-content:center">
          <i class="ti ti-upload"></i> Choose Logo
          <input type="file" name="logo" style="display:none" accept="image/*"
            onchange="document.getElementById('logoPreview').src=URL.createObjectURL(this.files[0])">
        </label>
      </div>
    </div>

    {{-- Hotel Specs --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-settings" style="color:var(--amber)"></i> Hotel Details</div></div>
      <div class="card-body">
        <div class="form-row c2" style="margin-bottom:12px">
          <div class="fg">
            <label class="flabel">Status *</label>
            <select name="status" class="fc">
              <option value="1">Active</option>
              <option value="0" selected>Inactive</option>
            </select>
          </div>
          <div class="fg">
            <label class="flabel">Star Rating</label>
            <select name="stars" class="fc">
              <option value="0">Unrated</option>
              <option value="1">★ 1 Star</option>
              <option value="2">★★ 2 Stars</option>
              <option value="3">★★★ 3 Stars</option>
              <option value="4">★★★★ 4 Stars</option>
              <option value="5">★★★★★ 5 Stars</option>
            </select>
          </div>
        </div>
        <div class="form-row c2" style="margin-bottom:12px">
          <div class="fg">
            <label class="flabel">Latitude</label>
            <input type="text" name="latitude" class="fc" placeholder="e.g. 13.0827">
          </div>
          <div class="fg">
            <label class="flabel">Longitude</label>
            <input type="text" name="longitude" class="fc" placeholder="e.g. 80.2707">
          </div>
        </div>
      </div>
    </div>

    {{-- Restrictions --}}
    <div class="card">
      <div class="card-hdr">
        <div class="card-title"><i class="ti ti-ban" style="color:var(--red)"></i> Hotel Restrictions</div>
        <button type="button" id="add-restriction" class="btn btn-secondary btn-sm"><i class="ti ti-plus"></i> Add Rule</button>
      </div>
      <div class="card-body">
        <div id="restrictions-wrapper">
          <div class="restriction-item" style="background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:8px">
            <div style="display:grid;grid-template-columns:70px 1fr 160px 38px;gap:8px;align-items:end">
              <div class="fg"><label class="flabel">Emoji</label><input type="text" class="fc" name="restrictions[0][icon]" placeholder="🚭" style="font-size:20px;text-align:center"></div>
              <div class="fg"><label class="flabel">Rule</label><input type="text" class="fc" name="restrictions[0][title]" placeholder="No Smoking"></div>
              <div class="fg"><label class="flabel">Type</label>
                <select class="fc" name="restrictions[0][type]">
                  <option value="allowed">✅ Allowed</option>
                  <option value="limited">⚠️ Limited</option>
                  <option value="not_allowed" selected>🚫 Not Allowed</option>
                </select>
              </div>
              <div style="display:flex;align-items:flex-end">
                <button type="button" class="btn btn-danger btn-sm remove-restriction" style="width:38px;height:38px;padding:0;border-radius:8px">&times;</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- Language Content --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    @foreach($languages as $language)
    @php
      $amenities = \App\Models\Amenitie::where('language_id',$language->id)->get();
    @endphp
    <div class="card">
      <div class="card-hdr">
        <div class="card-title"><i class="ti ti-language" style="color:var(--amber)"></i> {{ $language->name }}
          @if($language->is_default)<span class="badge green no-dot" style="margin-left:6px">Default</span>@endif
        </div>
      </div>
      <div class="card-body" id="collapse{{ $language->id }}" dir="{{ $language->direction==1?'rtl':'ltr' }}">

        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">Hotel Name *</label>
            <input type="text" class="fc" name="{{ $language->code }}_title" placeholder="e.g. i5 Boutique Hotel">
          </div>
          <div class="fg">
            <label class="flabel">Category *</label>
            <select name="{{ $language->code }}_category_id" class="fc">
              <option value="">Select Category</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Address *</label>
          <input type="text" class="fc" name="{{ $language->code }}_address" placeholder="Full hotel address">
        </div>

        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">Country *</label>
            <select name="{{ $language->code }}_country_id" class="fc js-example-basic-single2 select2"
              onchange="getState(this.value,'{{ $language->code }}')">
              <option value="">Select Country</option>
              @foreach($countries as $country)
              <option value="{{ $country->id }}">{{ $country->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="fg">
            <label class="flabel">State *</label>
            <select name="{{ $language->code }}_state_id" class="fc js-example-basic-single2 select2"
              id="{{ $language->code }}_state" onchange="getCity(this.value,'{{ $language->code }}')">
              <option value="">Select State</option>
            </select>
          </div>
        </div>

        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">City *</label>
          <select name="{{ $language->code }}_city_id" class="fc js-example-basic-single2 select2"
            id="{{ $language->code }}_city">
            <option value="">Select City</option>
          </select>
        </div>

        @if($amenities->count() > 0)
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Amenities</label>
          <div style="display:flex;flex-wrap:wrap;gap:7px;padding:10px;background:var(--navy3);border:1px solid var(--border);border-radius:8px">
            @foreach($amenities as $a)
            <label style="display:flex;align-items:center;gap:5px;cursor:pointer;padding:4px 8px;background:var(--navy2);border:1px solid var(--border);border-radius:6px;font-size:12px">
              <input type="checkbox" name="{{ $language->code }}_aminities[]" value="{{ $a->id }}" style="accent-color:var(--red)">
              <i class="{{ $a->icon }}" style="color:var(--red);font-size:13px"></i> {{ $a->title }}
            </label>
            @endforeach
          </div>
        </div>
        @endif

        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Description *</label>
          <textarea class="form-control summernote" name="{{ $language->code }}_description" data-height="250"></textarea>
        </div>

        <div style="background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">SEO</div>
          <div class="fg" style="margin-bottom:10px">
            <label class="flabel">Meta Keywords</label>
            <input class="form-control" name="{{ $language->code }}_meta_keyword" placeholder="Enter meta keywords" data-role="tagsinput">
          </div>
          <div class="fg">
            <label class="flabel">Meta Description</label>
            <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="3" placeholder="Enter meta description"></textarea>
          </div>
        </div>

      </div>
    </div>
    @endforeach

    <div style="display:flex;gap:10px">
      <button type="submit" form="hotelForm" class="btn btn-primary btn-block">
        <i class="ti ti-device-floppy"></i> Save Hotel
      </button>
      <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code]) }}" class="btn btn-secondary">Cancel</a>
    </div>

  </div>

</div>
</form>
<div id="sliders"></div>
@endsection

@section('script')
@verbatim
<script>
document.addEventListener("DOMContentLoaded", function () {
  var idx = document.querySelectorAll(".restriction-item").length;
  document.getElementById("add-restriction") && document.getElementById("add-restriction").addEventListener("click", function () {
    var wrap = document.getElementById("restrictions-wrapper");
    var item = document.createElement("div");
    item.className = "restriction-item";
    item.style.cssText = "background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:8px";
    item.innerHTML = '<div style="display:grid;grid-template-columns:70px 1fr 160px 38px;gap:8px;align-items:end">'
      + '<div class="fg"><label class="flabel">Emoji</label><input type="text" class="fc" id="ri_icon" placeholder="🚭" style="font-size:20px;text-align:center"></div>'
      + '<div class="fg"><label class="flabel">Rule</label><input type="text" class="fc" id="ri_title" placeholder="No Pets"></div>'
      + '<div class="fg"><label class="flabel">Type</label><select class="fc" id="ri_type">'
      + '<option value="allowed">✅ Allowed</option><option value="limited">⚠️ Limited</option>'
      + '<option value="not_allowed" selected>🚫 Not Allowed</option></select></div>'
      + '<div style="display:flex;align-items:flex-end">'
      + '<button type="button" class="btn btn-danger btn-sm remove-restriction" style="width:38px;height:38px;padding:0;border-radius:8px">&times;</button>'
      + '</div></div>';
    item.querySelector('#ri_icon').name = 'restrictions[' + idx + '][icon]';
    item.querySelector('#ri_title').name = 'restrictions[' + idx + '][title]';
    item.querySelector('#ri_type').name = 'restrictions[' + idx + '][type]';
    item.querySelector('#ri_icon').removeAttribute('id');
    item.querySelector('#ri_title').removeAttribute('id');
    item.querySelector('#ri_type').removeAttribute('id');
    wrap.appendChild(item);
    idx++;
  });
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-restriction")) {
      e.target.closest(".restriction-item").remove();
    }
  });
});
</script>
@endverbatim
<script>
"use strict";
var storeUrl = "{{ route('admin.hotel_management.hotel.imagesstore') }}";
var removeUrl = "{{ route('admin.hotel_management.hotel.imagermv') }}";
var galleryImages = {{ $numberoffImages }};
var languages = {{ json_encode($languages) }};
</script>
<script>
function getState(countryId, langCode) {
  if (!countryId) return;
  fetch("{{ route('admin.hotel_management.get-state') }}", {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
    body: JSON.stringify({country_id: countryId})
  }).then(r=>r.json()).then(data=>{
    var sel = document.getElementById(langCode+'_state');
    sel.innerHTML = '<option value="">Select State</option>';
    data.forEach(s => sel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>');
    document.getElementById(langCode+'_city').innerHTML = '<option value="">Select City</option>';
  });
}
function getCity(stateId, langCode) {
  if (!stateId) return;
  fetch("{{ route('admin.hotel_management.get-city') }}", {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
    body: JSON.stringify({state_id: stateId})
  }).then(r=>r.json()).then(data=>{
    var sel = document.getElementById(langCode+'_city');
    sel.innerHTML = '<option value="">Select City</option>';
    data.forEach(c => sel.innerHTML += '<option value="'+c.id+'">'+c.name+'</option>');
  });
}
</script>
<script src="{{ asset('assets/admin/js/admin-hotel.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin-dropzone.js') }}"></script>
@endsection