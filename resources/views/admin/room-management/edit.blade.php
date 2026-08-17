@extends('admin.layout')
@section('section','Property / Rooms')
@section('page','Edit Room')

@section('content')
@php
  $vendorId = $room->vendor_id;
  $numberoffImages = ($vendorId == 0) ? 99999999
    : (\App\Http\Helpers\VendorPermissionHelper::packagePermission($vendorId)->number_of_images_per_room - count($room->room_galleries));
  $dContent = \App\Models\RoomContent::where('room_id',$room->id)->where('language_id',$defaultLang->id)->first();
  $slug = optional($dContent)->slug;
@endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Edit Room</h2>
    <p>{{ optional($dContent)->title ?? 'Room #'.$room->id }}</p>
  </div>
  <div class="page-hdr-actions">
    @if($dContent)
    <a href="{{ route('frontend.room.details',['slug'=>$slug,'id'=>$room->id]) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="ti ti-external-link"></i> Preview</a>
    @endif
    <a href="{{ route('admin.room_management.rooms',['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-arrow-left"></i> Back</a>
    <button type="submit" form="roomForm" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Save Changes</button>
  </div>
</div>

<div class="alert alert-danger pb-1 d-none" id="roomErrors" style="margin-bottom:14px"><ul></ul></div>

{{-- Gallery --}}
<div class="card" style="margin-bottom:14px">
  <div class="card-hdr"><div class="card-title"><i class="ti ti-photo" style="color:var(--blue)"></i> Gallery Images</div></div>
  <div class="card-body">
    @if($room->room_galleries->count() > 0)
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px" id="imgtable">
      @foreach($room->room_galleries as $item)
      <div style="position:relative" id="trdb{{ $item->id }}">
        <img src="{{ asset('assets/img/room/room-gallery/'.$item->image) }}" style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:1px solid var(--border)">
        <button type="button" class="rmvbtndb" data-indb="{{ $item->id }}"
          style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:var(--red);color:#fff;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center">×</button>
      </div>
      @endforeach
    </div>
    @endif
    <form action="#" id="my-dropzone" enctype="multipart/form-data" class="dropzone create">
      @csrf<div class="fallback"><input name="file" type="file" multiple></div>
      <input type="hidden" value="{{ $room->id }}" name="room_id">
    </form>
    @if($vendorId != 0)<div class="fc-hint" style="margin-top:6px">{{ $numberoffImages }} more images allowed</div>@endif
  </div>
</div>

<form id="roomForm" action="{{ route('admin.room_management.update_room',$room->id) }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="room_id" value="{{ $room->id }}">
<input type="hidden" name="vendor_id" value="{{ $vendorId }}">

<div class="two-col" style="align-items:start;gap:16px">

  <div style="display:flex;flex-direction:column;gap:14px">

    {{-- Featured Image --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-camera" style="color:var(--red)"></i> Featured Image *</div></div>
      <div class="card-body" style="display:flex;flex-direction:column;align-items:center;gap:12px">
        <img id="featPreview"
          src="{{ $room->feature_image ? asset('assets/img/room/featureImage/'.$room->feature_image) : asset('assets/admin/img/noimage.jpg') }}"
          style="width:100%;max-height:180px;object-fit:cover;border-radius:8px;border:1px solid var(--border)">
        <label class="btn btn-secondary btn-sm" style="cursor:pointer;margin:0;width:100%;justify-content:center">
          <i class="ti ti-upload"></i> Change Image
          <input type="file" class="img-input" name="thumbnail" style="display:none" accept="image/*" onchange="document.getElementById('featPreview').src=URL.createObjectURL(this.files[0])">
        </label>
      </div>
    </div>

    {{-- Specs --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-settings" style="color:var(--amber)"></i> Room Specs</div></div>
      <div class="card-body">
        <div class="form-row c2" style="margin-bottom:12px">
          <div class="fg"><label class="flabel">Status *</label>
            <select name="status" class="fc">
              <option value="1" {{ $room->status==1?'selected':'' }}>Active</option>
              <option value="0" {{ $room->status==0?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <div class="fg"><label class="flabel">Area (sqft)</label><input type="text" name="area" class="fc" value="{{ $room->area }}" placeholder="e.g. 250"></div>
        </div>
        <div class="form-row c2" style="margin-bottom:12px">
          <div class="fg"><label class="flabel">Adults *</label><input type="number" name="adult" class="fc" value="{{ $room->adult }}" min="1"></div>
          <div class="fg"><label class="flabel">Children</label><input type="number" name="children" class="fc" value="{{ $room->children }}" min="0"></div>
        </div>
        <div class="form-row c2" style="margin-bottom:12px">
          <div class="fg"><label class="flabel">Beds *</label><input type="number" name="bed" class="fc" value="{{ $room->bed }}" min="1"></div>
          <div class="fg"><label class="flabel">Bathrooms *</label><input type="number" name="bathroom" class="fc" value="{{ $room->bathroom }}" min="1"></div>
        </div>
        <div class="form-row c2" style="margin-bottom:0">
          <div class="fg"><label class="flabel">Rooms of this type *</label><input type="number" name="number_of_rooms_of_this_same_type" class="fc" value="{{ $room->number_of_rooms_of_this_same_type }}" min="1"></div>
          <div class="fg"><label class="flabel">Prep Time (mins) *</label>
            <input type="text" name="preparation_time" class="fc" value="{{ $room->preparation_time }}">
            <div class="fc-hint">Cleaning time between guests</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Pricing --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-clock" style="color:var(--green)"></i> Hourly Pricing ({{ $settings->base_currency_text }})</div></div>
      <div class="card-body">
        @for($i = 0; $i < count($bookingHours); $i++)
        @php $price = null; foreach($prices as $p){ if($p->hour_id == $bookingHours[$i]->id){ $price = $p->price; break; } } @endphp
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">
            @if($bookingHours[$i]->hour == 24 || strtolower($bookingHours[$i]->hour) == 'full day') Full Day
            @else {{ $bookingHours[$i]->hour }} Hours @endif Price *
          </label>
          <div style="position:relative">
            <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);font-weight:600">{{ $settings->base_currency_symbol }}</span>
            <input type="text" name="prices[]" class="fc" value="{{ $price ?? '' }}" placeholder="0" style="padding-left:28px">
          </div>
        </div>
        @endfor
      </div>
    </div>

    {{-- Hotel --}}
    <div class="card">
      <div class="card-hdr"><div class="card-title"><i class="ti ti-building" style="color:var(--blue)"></i> Assigned Hotel</div></div>
      <div class="card-body">
        <div class="fg">
          <label class="flabel">Select Hotel *</label>
          <select name="hotel_id" class="fc js-example-basic-single2 select2">
            <option value="">Select a Hotel</option>
            @foreach($hotels as $hotel)
            <option value="{{ $hotel->id }}" {{ $room->hotel_id==$hotel->id?'selected':'' }}>
              {{ optional($hotel->hotel_contents->first())->title ?? 'Hotel #'.$hotel->id }}
            </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    {{-- Restrictions --}}
    <div class="card">
      <div class="card-hdr">
        <div class="card-title"><i class="ti ti-ban" style="color:var(--red)"></i> Room Restrictions</div>
        <button type="button" id="add-restriction" class="btn btn-secondary btn-sm"><i class="ti ti-plus"></i> Add Rule</button>
      </div>
      <div class="card-body">
        <div id="restrictions-wrapper">
          @php
            $restrictions = [];
            if(!empty($room->restrictions)){
              $rc = \App\Models\RoomContent::where('room_id',$room->id)->where('language_id',$defaultLang->id)->first();
              $restrictions = json_decode(optional($rc)->restrictions ?? '[]', true) ?? [];
            }
            if(empty($restrictions)) $restrictions[] = ['icon'=>'','title'=>'','type'=>'not_allowed'];
          @endphp
          @foreach($restrictions as $ri => $r)
          <div class="restriction-item" style="background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:8px">
            <div style="display:grid;grid-template-columns:70px 1fr 140px 38px;gap:8px;align-items:end">
              <div class="fg"><label class="flabel">Icon</label><input type="text" class="fc" name="restrictions[{{ $ri }}][icon]" value="{{ $r['icon']??'' }}" placeholder="🚭"></div>
              <div class="fg"><label class="flabel">Rule</label><input type="text" class="fc" name="restrictions[{{ $ri }}][title]" value="{{ $r['title']??'' }}" placeholder="No Smoking"></div>
              <div class="fg"><label class="flabel">Type</label>
                <select class="fc" name="restrictions[{{ $ri }}][type]">
                  <option value="allowed" {{ ($r['type']??'')=='allowed'?'selected':'' }}>✅ Allowed</option>
                  <option value="limited" {{ ($r['type']??'')=='limited'?'selected':'' }}>⚠️ Limited</option>
                  <option value="restricted" {{ ($r['type']??'')=='restricted'?'selected':'' }}>🔴 Restricted</option>
                  <option value="not_allowed" {{ ($r['type']??'')=='not_allowed'?'selected':'' }}>🚫 Not Allowed</option>
                </select>
              </div>
              <div style="display:flex;align-items:flex-end">
                <button type="button" class="btn btn-danger btn-sm remove-restriction" style="width:38px;height:38px;padding:0;justify-content:center;border-radius:8px">×</button>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

  {{-- Language Content --}}
  <div style="display:flex;flex-direction:column;gap:14px">
    @foreach($languages as $language)
    @php
      $rc = \App\Models\RoomContent::where('room_id',$room->id)->where('language_id',$language->id)->first();
      $types = \App\Models\RoomCategory::where('language_id',$language->id)->where('status',1)->orderBy('serial_number')->get();
      $amenities = \App\Models\Amenitie::where('language_id',$language->id)->get();
      $hasAmenities = json_decode(optional($rc)->amenities ?? '[]');
    @endphp
    <div class="card">
      <div class="card-hdr">
        <div>
          <div class="card-title"><i class="ti ti-language" style="color:var(--amber)"></i> {{ $language->name }}
            @if($language->is_default)<span class="badge green no-dot" style="margin-left:6px">Default</span>@endif
          </div>
        </div>
        @foreach($languages as $ol)
          @if($ol->id != $language->id)
          <label style="font-size:11px;cursor:pointer;display:flex;align-items:center;gap:5px;color:var(--muted)">
            <input type="checkbox" onchange="cloneInput('collapse{{ $language->id }}','collapse{{ $ol->id }}',event)" style="accent-color:var(--red)"> Copy to {{ $ol->name }}
          </label>
          @endif
        @endforeach
      </div>
      <div class="card-body" id="collapse{{ $language->id }}" dir="{{ $language->direction==1?'rtl':'ltr' }}">
        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg"><label class="flabel">Room Title *</label>
            <input type="text" class="fc" name="{{ $language->code }}_title" value="{{ optional($rc)->title }}" placeholder="e.g. Deluxe King Room"></div>
          <div class="fg"><label class="flabel">Category *</label>
            <select name="{{ $language->code }}_room_category" class="fc js-example-basic-single2 select2">
              <option value="">Select Category</option>
              @foreach($types as $type)
                <option value="{{ $type->id }}" {{ optional($rc)->room_category==$type->id?'selected':'' }}>{{ $type->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        @if($amenities->count() > 0)
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Amenities</label>
          <div style="display:flex;flex-wrap:wrap;gap:7px;padding:10px;background:var(--navy3);border:1px solid var(--border);border-radius:8px">
            @foreach($amenities as $a)
            <label style="display:flex;align-items:center;gap:5px;cursor:pointer;padding:4px 8px;background:var(--navy2);border:1px solid var(--border);border-radius:6px;font-size:12px">
              <input type="checkbox" name="{{ $language->code }}_amenities[]" value="{{ $a->id }}"
                {{ ($hasAmenities && in_array($a->id,(array)$hasAmenities))?'checked':'' }} style="accent-color:var(--red)"> {{ $a->title }}
            </label>
            @endforeach
          </div>
        </div>
        @endif
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Description *</label>
          <textarea class="form-control summernote" id="{{ $language->code }}_description" name="{{ $language->code }}_description" data-height="250">{{ optional($rc)->description }}</textarea>
        </div>
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Guest Policies / What to Know</label>
          <textarea class="form-control summernote" id="{{ $language->code }}_room_policy" name="{{ $language->code }}_room_policy" data-height="180">{{ optional($rc)->room_policy }}</textarea>
        </div>
        <div style="background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">SEO</div>
          <div class="fg" style="margin-bottom:10px">
            <label class="flabel">Meta Keywords</label>
            <input class="form-control" name="{{ $language->code }}_meta_keyword" value="{{ optional($rc)->meta_keyword }}" placeholder="Enter meta keywords" data-role="tagsinput">
          </div>
          <div class="fg">
            <label class="flabel">Meta Description</label>
            <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="3" placeholder="Enter meta description">{{ optional($rc)->meta_description }}</textarea>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    <div style="display:flex;gap:10px">
      <button type="submit" form="roomForm" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Save Changes</button>
      <a href="{{ route('admin.room_management.rooms',['language'=>$defaultLang->code]) }}" class="btn btn-secondary">Cancel</a>
    </div>
  </div>
</div>
</form>
<div id="sliders"></div>
@endsection

@section('script')
@include('admin.partials.restriction-script')
<script>
"use strict";
var storeUrl = "{{ route('admin.room_management.room.imagesstore') }}";
var removeUrl = "{{ route('admin.room_management.room.imagermv') }}";
var rmvdbUrl = "{{ route('admin.room_management.room.imgdbrmv') }}";
var galleryImages = {{ $numberoffImages }};
var languages = {!! json_encode($languages) !!};
</script>
<script src="{{ asset('assets/admin/js/feature.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin-dropzone.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin-room.js') }}"></script>
@endsection