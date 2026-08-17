@extends('admin.layout')
@section('section','Property')
@section('page','Edit Hotel')

@section('content')
@php
  $vendorId = $hotel->vendor_id;
  $numberoffImages = ($vendorId == 0) ? 99999999
    : (\App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId)->number_of_images_per_hotel
       - count($hotel->hotel_galleries));
  $dContent = \App\Models\HotelContent::where('hotel_id',$hotel->id)
    ->where('language_id',$defaultLang->id)->first();
  $slug = optional($dContent)->slug;
@endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Edit Hotel</h2>
    <p>{{ optional($dContent)->title ?? 'Hotel #'.$hotel->id }}</p>
  </div>
  <div class="page-hdr-actions">
    @if($dContent)
    <a href="{{ route('frontend.hotel.details',['slug'=>$slug,'id'=>$hotel->id]) }}" target="_blank" class="btn btn-secondary btn-sm">
      <i class="ti ti-external-link"></i> Preview
    </a>
    @endif
    <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm">
      <i class="ti ti-arrow-left"></i> Back
    </a>
    <button type="submit" form="hotelForm" class="btn btn-primary">
      <i class="ti ti-device-floppy"></i> Save Changes
    </button>
  </div>
</div>

{{-- Error alert --}}
<div class="alert alert-danger pb-1 d-none" id="hotelErrors" style="margin-bottom:14px"><ul></ul></div>

<form id="hotelForm" action="{{ route('admin.hotel_management.update_hotel',$hotel->id) }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
<input type="hidden" name="vendor_id" value="{{ $vendorId }}">

<div class="two-col" style="align-items:start;gap:16px">

  {{-- LEFT COLUMN --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Basic Settings --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-settings" style="color:var(--red)"></i> Basic Settings</div></div>
      <div class="card-body">

        {{-- Hotel Logo --}}
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Hotel Logo</label>
          <div style="display:flex;align-items:center;gap:14px">
            <img id="logoPreview"
              src="{{ $hotel->logo ? asset('assets/img/hotel/logo/'.$hotel->logo) : asset('assets/admin/img/noimage.jpg') }}"
              style="width:70px;height:70px;border-radius:10px;object-fit:cover;border:1px solid var(--border)">
            <div>
              <label class="btn btn-secondary btn-sm" style="cursor:pointer;margin:0">
                <i class="ti ti-upload"></i> Choose Logo
                <input type="file" name="logo" style="display:none" accept="image/*"
                  onchange="document.getElementById('logoPreview').src=URL.createObjectURL(this.files[0])">
              </label>
              <div class="fc-hint" style="margin-top:5px">Recommended: 300×300px</div>
            </div>
          </div>
        </div>

        {{-- Status + Stars + Couple Friendly --}}
        <div class="form-row c3" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">Status *</label>
            <select name="status" class="fc">
              <option value="1" {{ $hotel->status==1?'selected':'' }}>Active</option>
              <option value="0" {{ $hotel->status==0?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <div class="fg">
            <label class="flabel">Star Rating *</label>
            <select name="stars" class="fc">
              @for($s=1;$s<=5;$s++)
              <option value="{{ $s }}" {{ $hotel->stars==$s?'selected':'' }}>{{ $s }} {{ str_repeat('★',$s) }}</option>
              @endfor
            </select>
          </div>
          <div class="fg" style="justify-content:center">
            <label class="flabel">Couple Friendly</label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:6px;padding:8px 12px;background:var(--navy3);border:1px solid var(--border);border-radius:8px">
              <input type="hidden" name="couple_friendly" value="0">
              <input type="checkbox" name="couple_friendly" value="1" {{ $hotel->couple_friendly ? 'checked' : '' }}
                style="width:16px;height:16px;accent-color:var(--red)">
              <span style="font-size:13px;font-weight:500;color:var(--text)">
                <i class="ti ti-heart" style="color:var(--red);font-size:14px"></i>
                Couple Friendly
              </span>
            </label>
            <div class="fc-hint">Unmarried couples welcome</div>
          </div>
        </div>

        {{-- Lat/Long (if Google Maps disabled) --}}
        @if($settings->google_map_api_key_status == 0)
        <div class="form-row c2">
          <div class="fg">
            <label class="flabel">Latitude *</label>
            <input type="text" name="latitude" class="fc" value="{{ $hotel->latitude }}" placeholder="e.g. 13.08268" id="latitude">
            <div class="fc-hint">Between -90 to 90</div>
          </div>
          <div class="fg">
            <label class="flabel">Longitude *</label>
            <input type="text" name="longitude" class="fc" value="{{ $hotel->longitude }}" placeholder="e.g. 80.27004" id="longitude">
            <div class="fc-hint">Between -180 to 180</div>
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- Gallery Images --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-photo" style="color:var(--blue)"></i> Gallery Images</div></div>
      <div class="card-body">
        {{-- Existing images --}}
        @if($hotel->hotel_galleries->count() > 0)
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px" id="imgtable">
          @foreach($hotel->hotel_galleries as $item)
          <div style="position:relative" id="trdb{{ $item->id }}">
            <img src="{{ asset('assets/img/hotel/hotel-gallery/'.$item->image) }}"
              style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:1px solid var(--border)">
            <button type="button" class="rmvbtndb" data-indb="{{ $item->id }}"
              style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:var(--red);color:#fff;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;line-height:1">×</button>
          </div>
          @endforeach
        </div>
        @endif
        <form action="#" id="my-dropzone" enctype="multipart/formdata" class="dropzone create">
          @csrf
          <div class="fallback"><input name="file" type="file" multiple></div>
          <input type="hidden" value="{{ $hotel->id }}" name="hotel_id">
        </form>
        @if($vendorId != 0)
        <div class="fc-hint" style="margin-top:8px">Max {{ $numberoffImages }} more images allowed</div>
        @endif
      </div>
    </div>

  </div>

  {{-- RIGHT COLUMN — Language tabs --}}
  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Language sections --}}
    @foreach($languages as $language)
    @php
      $hc = \App\Models\HotelContent::where('hotel_id',$hotel->id)->where('language_id',$language->id)->first();
      $countries = \App\Models\Location\Country::where('language_id',$language->id)->get();
      $states    = \App\Models\Location\State::where('language_id',$language->id)->get();
      $cities    = \App\Models\Location\City::where('language_id',$language->id)->get();
      $categories= \App\Models\HotelCategory::where('language_id',$language->id)->where('status',1)->orderBy('serial_number')->get();
      $amenities = \App\Models\Amenitie::where('language_id',$language->id)->get();
      $hasAmenities = json_decode(optional($hc)->amenities ?? '[]');
    @endphp

    <div class="card">
      <div class="card-hdr">
        <div>
          <div class="card-title">
            <i class="ti ti-language" style="color:var(--amber)"></i>
            {{ $language->name }}
            @if($language->is_default) <span class="badge green no-dot" style="margin-left:6px">Default</span> @endif
          </div>
        </div>
        @foreach($languages as $otherLang)
          @if($otherLang->id != $language->id)
          <label style="font-size:11px;cursor:pointer;display:flex;align-items:center;gap:5px;color:var(--muted)">
            <input type="checkbox" onchange="cloneInput('collapse{{ $language->id }}','collapse{{ $otherLang->id }}',event)" style="accent-color:var(--red)">
            Copy to {{ $otherLang->name }}
          </label>
          @endif
        @endforeach
      </div>
      <div class="card-body" id="collapse{{ $language->id }}" dir="{{ $language->direction==1?'rtl':'ltr' }}">

        {{-- Title + Category --}}
        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">Hotel Title *</label>
            <input type="text" name="{{ $language->code }}_title" class="fc"
              placeholder="Enter hotel name" value="{{ optional($hc)->title }}">
          </div>
          <div class="fg">
            <label class="flabel">Category *</label>
            <select name="{{ $language->code }}_category_id" class="fc js-example-basic-single2">
              <option value="">Select Category</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ optional($hc)->category_id==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Location --}}
        <div class="form-row c3" style="margin-bottom:14px">
          @if($countries->count() > 0)
          <div class="fg">
            <label class="flabel">Country *</label>
            <select name="{{ $language->code }}_country_id" class="fc js-example-basic-single3" data-code="{{ $language->code }}">
              <option value="">Select Country</option>
              @foreach($countries as $c)
              <option value="{{ $c->id }}" {{ optional($hc)->country_id==$c->id?'selected':'' }}>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          @if($states->count() > 0)
          <div class="fg {{ $language->code }}_hide_state {{ !optional($hc)->state_id ? 'd-none' : '' }}">
            <label class="flabel">State</label>
            <select name="{{ $language->code }}_state_id" class="fc js-example-basic-single4 {{ $language->code }}_country_state_id" data-code="{{ $language->code }}">
              <option value="">Select State</option>
              @foreach($states as $s)
              <option value="{{ $s->id }}" {{ optional($hc)->state_id==$s->id?'selected':'' }}>{{ $s->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="fg">
            <label class="flabel">City *</label>
            <select name="{{ $language->code }}_city_id" class="fc js-example-basic-single5 {{ $language->code }}_state_city_id">
              <option value="">Select City</option>
              @foreach($cities as $c)
              <option value="{{ $c->id }}" {{ optional($hc)->city_id==$c->id?'selected':'' }}>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Address --}}
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Address *</label>
          <div style="display:flex;gap:8px">
            <input type="text" name="{{ $language->code }}_address" class="fc"
              placeholder="Full hotel address" id="search-address"
              value="{{ optional($hc)->address }}" style="flex:1">
            @if($hc && $language->is_default && $settings->google_map_api_key_status)
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#GoogleMapModal">
              <i class="ti ti-map-pin"></i> Map
            </button>
            @endif
          </div>
        </div>

        {{-- Lat/Long (Google Maps enabled) --}}
        @if($language->is_default && $settings->google_map_api_key_status)
        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg">
            <label class="flabel">Latitude *</label>
            <input type="text" name="latitude" class="fc" value="{{ $hotel->latitude }}" id="latitude" placeholder="e.g. 13.08268">
          </div>
          <div class="fg">
            <label class="flabel">Longitude *</label>
            <input type="text" name="longitude" class="fc" value="{{ $hotel->longitude }}" id="longitude" placeholder="e.g. 80.27004">
          </div>
        </div>
        @endif

        {{-- Amenities --}}
        @if($amenities->count() > 0)
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Amenities</label>
          <div style="display:flex;flex-wrap:wrap;gap:8px;padding:10px;background:var(--navy3);border:1px solid var(--border);border-radius:8px">
            @foreach($amenities as $a)
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:4px 8px;background:var(--navy2);border:1px solid var(--border);border-radius:6px;font-size:12px">
              <input type="checkbox" name="{{ $language->code }}_aminities[]" value="{{ $a->id }}"
                data-code="{{ $language->code }}" data-listing_id="{{ $hotel->id }}" data-language_id="{{ $language->id }}"
                {{ ($hasAmenities && in_array($a->id,(array)$hasAmenities)) ? 'checked' : '' }}
                style="accent-color:var(--red)">
              <i class="{{ $a->icon }}" style="font-size:12px;color:var(--muted)"></i>
              {{ $a->title }}
            </label>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Description --}}
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Description *</label>
          <textarea class="form-control summernote" id="{{ $language->code }}_description"
            name="{{ $language->code }}_description" data-height="250">{{ optional($hc)->description }}</textarea>
        </div>

        {{-- SEO --}}
        <div style="background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">SEO</div>
          <div class="fg" style="margin-bottom:10px">
            <label class="flabel">Meta Keywords</label>
            <input class="form-control" name="{{ $language->code }}_meta_keyword"
              placeholder="Enter meta keywords" data-role="tagsinput"
              value="{{ optional($hc)->meta_keyword }}">
          </div>
          <div class="fg">
            <label class="flabel">Meta Description</label>
            <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="3"
              placeholder="Enter meta description">{{ optional($hc)->meta_description }}</textarea>
          </div>
        </div>

      </div>
    </div>
    @endforeach

    {{-- Save button --}}
    <div style="display:flex;gap:10px">
      <button type="submit" form="hotelForm" class="btn btn-primary btn-block">
        <i class="ti ti-device-floppy"></i> Save All Changes
      </button>
      <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code]) }}" class="btn btn-secondary">Cancel</a>
    </div>

  </div>
</div>
</form>

{{-- Google Map Modal --}}
@if($settings->google_map_api_key_status)
<div class="modal fade" id="GoogleMapModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pick Location</h5>
        <div style="display:flex;gap:6px">
          <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">Use This Location</button>
          <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">×</button>
        </div>
      </div>
      <div class="modal-body" style="padding:0"><div id="map" style="height:400px;width:100%"></div></div>
    </div>
  </div>
</div>
@endif

@endsection

@section('script')
@if($settings->google_map_api_key_status)
<script src="{{ asset('assets/admin/js/edit-map-init.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $settings->google_map_api_key }}&libraries=places&callback=initMap" async defer></script>
@endif
<script src="{{ asset('assets/admin/js/feature.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin-dropzone.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin-hotel.js') }}"></script>
@endsection

@section('variables')
<script>
"use strict";
var address = "{{ $hotelAddress }}";
var storeUrl = "{{ route('admin.hotel_management.hotel.imagesstore') }}";
var removeUrl = "{{ route('admin.hotel_management.hotel.imagermv') }}";
var getStateUrl = "{{ route('admin.hotel_management.get-state') }}";
var getCityUrl = "{{ route('admin.hotel_management.get-city') }}";
var rmvdbUrl = "{{ route('admin.hotel_management.hotel.imgdbrmv') }}";
var galleryImages = {{ $numberoffImages }};
var languages = {{ json_encode($languages) }};
</script>
@endsection
