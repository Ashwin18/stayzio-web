@extends('admin.layout')
@section('section','Property / Hotels')
@section('page','Pending Approvals')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Hotel Approvals</h2>
    <p>{{ $pendingHotels->total() }} hotels awaiting your review</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.hotels',['language'=>$defaultLang->code]) }}" class="btn btn-secondary">
      <i class="ti ti-arrow-left"></i> All Hotels
    </a>
  </div>
</div>

@if($pendingHotels->total() == 0)
<div class="card">
  <div class="card-body" style="text-align:center;padding:48px;color:var(--muted)">
    <i class="ti ti-circle-check" style="font-size:40px;display:block;margin-bottom:12px;color:var(--green)"></i>
    <div style="font-size:14px;font-weight:600;margin-bottom:6px;color:var(--text)">All caught up!</div>
    <div style="font-size:12px">No hotels pending approval right now.</div>
  </div>
</div>
@else
<div style="display:flex;flex-direction:column;gap:12px">
  @foreach($pendingHotels as $hotel)
  @php
    $hc = $hotel->hotel_contents->where('lang_id', $defaultLang->id)->first()
       ?? $hotel->hotel_contents->first();
    $vendor = $hotel->vendor;
  @endphp
  <div class="card">
    <div style="padding:16px;display:flex;align-items:flex-start;gap:16px">
      {{-- Hotel logo / placeholder --}}
      <div style="width:56px;height:56px;border-radius:10px;background:var(--navy3);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;flex-shrink:0;color:var(--muted)">
        @if($hotel->logo)
          <img src="{{ asset('assets/img/hotel/logo/'.$hotel->logo) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px">
        @else
          {{ strtoupper(substr(optional($hc)->title??'H',0,2)) }}
        @endif
      </div>

      {{-- Info --}}
      <div style="flex:1;min-width:0">
        <div style="font-size:14px;font-weight:700;margin-bottom:3px">{{ optional($hc)->title ?? 'Unnamed Hotel' }}</div>
        <div style="font-size:11px;color:var(--muted);margin-bottom:8px">
          <i class="ti ti-map-pin" style="font-size:12px"></i>
          {{ optional($hc)->address ?? '—' }}
          @if(optional($hc)->hotel_category)
            &nbsp;·&nbsp;<span class="badge blue no-dot">{{ $hc->hotel_category }}</span>
          @endif
          &nbsp;·&nbsp;@for($s=1;$s<=$hotel->stars;$s++)⭐@endfor
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <div style="font-size:11px;color:var(--muted)">
            <i class="ti ti-building-store" style="font-size:12px"></i>
            Vendor: <span style="color:var(--text);font-weight:600">{{ optional($vendor)->username ?? 'Admin' }}</span>
          </div>
          <div style="font-size:11px;color:var(--muted)">
            <i class="ti ti-mail" style="font-size:12px"></i>
            {{ optional($vendor)->email ?? '—' }}
          </div>
          <div style="font-size:11px;color:var(--muted)">
            <i class="ti ti-bed" style="font-size:12px"></i>
            {{ $hotel->room->count() }} rooms listed
          </div>
          <div style="font-size:11px;color:var(--muted)">
            <i class="ti ti-clock" style="font-size:12px"></i>
            Submitted {{ $hotel->created_at ? \Carbon\Carbon::parse($hotel->created_at)->diffForHumans() : '—' }}
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div style="display:flex;gap:8px;flex-shrink:0">
        <a href="{{ route('admin.hotel_management.edit_hotel',['id'=>$hotel->id,'language'=>$defaultLang->code]) }}"
           class="btn btn-secondary btn-sm" target="_blank">
          <i class="ti ti-eye"></i> Preview
        </a>
        <form action="{{ route('admin.hotel_management.approve_hotel',$hotel->id) }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" class="btn btn-success btn-sm">
            <i class="ti ti-circle-check"></i> Approve
          </button>
        </form>
        <form action="{{ route('admin.hotel_management.reject_hotel',$hotel->id) }}" method="POST" style="display:inline"
              onsubmit="return confirm('Reject this hotel listing?')">
          @csrf
          <button type="submit" class="btn btn-danger btn-sm">
            <i class="ti ti-circle-x"></i> Reject
          </button>
        </form>
      </div>
    </div>

    {{-- Description preview --}}
    @if(optional($hc)->description)
    <div style="padding:0 16px 14px;font-size:11.5px;color:var(--muted);border-top:1px solid var(--border);padding-top:12px;margin-top:0">
      {{ Str::limit(strip_tags($hc->description), 200) }}
    </div>
    @endif
  </div>
  @endforeach

  <div class="pagination">
    {{ $pendingHotels->links() }}
    <span class="pg-info">{{ $pendingHotels->firstItem() }}–{{ $pendingHotels->lastItem() }} of {{ $pendingHotels->total() }}</span>
  </div>
</div>
@endif
@endsection
