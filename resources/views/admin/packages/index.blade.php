@extends('admin.layout')
@section('section','Finance')
@section('page','Packages')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Packages</h2><p>Vendor subscription plans</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.package.settings', ['language' => $defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-settings"></i> Settings</a>
    <button onclick="document.getElementById('createPkgModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> Add Package</button>
  </div>
</div>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
  @foreach($packages as $pkg)
  <div class="pkg-card {{ $pkg->recommended ? 'featured' : '' }}">
    @if($pkg->recommended)<div class="pkg-badge">⭐ Recommended</div>@endif
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px">
      <div class="av {{ $pkg->recommended ? 'red' : 'muted' }}" style="width:38px;height:38px;font-size:18px"><i class="{{ $pkg->icon ?? 'ti ti-package' }}"></i></div>
      <div><div class="td-main" style="font-size:14px">{{ $pkg->title }}</div><div class="td-sub">{{ ucfirst($pkg->term) }}</div></div>
    </div>
    <div class="pkg-price">@if($pkg->price==0)Free@else{{ $settings->base_currency_symbol }}{{ number_format($pkg->price,0) }}@endif</div>
    <div class="pkg-term">per {{ $pkg->term }}</div>
    <hr class="divider">
    <div class="pkg-feature"><i class="ti ti-building"></i> Up to {{ $pkg->number_of_hotel }} hotels</div>
    <div class="pkg-feature"><i class="ti ti-bed"></i> Up to {{ $pkg->number_of_room }} rooms</div>
    <div class="pkg-feature"><i class="ti ti-calendar-check"></i> Up to {{ $pkg->number_of_bookings }} bookings</div>
    <div class="pkg-feature"><i class="ti ti-photo"></i> {{ $pkg->number_of_images_per_hotel }} images/hotel</div>
    @if($pkg->features)
      @foreach(json_decode($pkg->features??'[]') as $f)
      <div class="pkg-feature"><i class="ti ti-check"></i> {{ $f }}</div>
      @endforeach
    @endif
    <hr class="divider">
    <div style="display:flex;gap:8px">
      <a href="{{ route('admin.package.edit', ['id' => $pkg->id, 'language' => $defaultLang->code]) }}" class="btn btn-secondary btn-sm btn-block"><i class="ti ti-edit"></i> Edit</a>
      <form action="{{ route('admin.package.delete') }}" method="POST" style="flex:1" onsubmit="return confirm('Delete this package?')">@csrf<input type="hidden" name="id" value="{{ $pkg->id }}"> 
        <button class="btn btn-danger btn-sm btn-block"><i class="ti ti-trash"></i></button>
      </form>
    </div>
  </div>
  @endforeach
</div>

{{-- Create Modal --}}
<div id="createPkgModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:560px;max-height:85vh;overflow-y:auto">
    <div style="padding:16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-size:15px;font-weight:600">Add Package</span>
      <button onclick="document.getElementById('createPkgModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('admin.package.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div style="padding:16px">
        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg"><label class="flabel">Package Title *</label><input type="text" name="title" class="fc" placeholder="e.g. Pro Plan" required></div>
          <div class="fg"><label class="flabel">Price ({{ $settings->base_currency_text }})</label><input type="number" name="price" class="fc" placeholder="0 = Free" min="0"></div>
          <div class="fg"><label class="flabel">Term *</label><select name="term" class="fc" required><option value="">Choose term</option><option value="monthly">Monthly</option><option value="yearly">Yearly</option><option value="lifetime">Lifetime</option></select></div>
          <div class="fg"><label class="flabel">Hotels allowed</label><input type="number" name="number_of_hotel" class="fc" value="5"></div>
          <div class="fg"><label class="flabel">Rooms allowed</label><input type="number" name="number_of_room" class="fc" value="20"></div>
          <div class="fg"><label class="flabel">Bookings allowed</label><input type="number" name="number_of_bookings" class="fc" value="100"></div>
          <div class="fg"><label class="flabel">Images per hotel</label><input type="number" name="number_of_images_per_hotel" class="fc" value="10"></div>
          <div class="fg"><label class="flabel">Images per room</label><input type="number" name="number_of_images_per_room" class="fc" value="5"></div>
        </div>
        <div class="fg" style="margin-bottom:14px"><label class="flabel">Recommended?</label><label class="check-item"><input type="checkbox" name="recommended" value="1"> Mark as recommended plan</label></div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Save Package</button>
      </div>
    </form>
  </div>
</div>
@endsection
