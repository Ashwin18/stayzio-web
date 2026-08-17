{{-- ============================================================
     FILE 1: resources/views/admin/hotel-management/index.blade.php
     ============================================================ --}}
@extends('admin.layout')
@section('section','Property')
@section('page','All Hotels')
@section('content')
@php $adminUser = App\Models\Admin::first(); @endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Hotels</h2>
    <p>{{ $hotels->total() }} properties listed across all vendors</p>
  </div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.hotel_management.bulk_delete.hotel') }}">
      <i class="ti ti-trash"></i> Bulk Delete
    </button>
    <a href="{{ route('admin.hotel_management.pending_hotels',['language'=>$defaultLang->code]) }}" class="btn btn-warn btn-sm">
      <i class="ti ti-clock-hour-4"></i> Pending Approvals
    </a>
    <a href="{{ route('admin.hotel_management.select_vendor',['language'=>$defaultLang->code]) }}" class="btn btn-primary">
      <i class="ti ti-plus"></i> Add Hotel
    </a>
    <a href="{{ route('admin.hotel_management.popularity_order') }}" class="btn btn-secondary">
      <i class="ti ti-arrows-sort"></i> Manage Popularity Order
    </a>
  </div>
</div>

<div class="card">
  <form action="{{ route('admin.hotel_management.hotels') }}" method="GET" id="hotelSearchForm">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <select name="vendor_id" class="fc" onchange="document.getElementById('hotelSearchForm').submit()">
        <option value="">All Vendors</option>
        <option value="admin" @selected(request('vendor_id')=='admin')>{{ $adminUser->username??'Admin' }} (admin)</option>
        @foreach($vendors as $v)<option @selected($v->id==request('vendor_id')) value="{{ $v->id }}">{{ $v->username }}</option>@endforeach
      </select>
      <select name="category" class="fc" onchange="document.getElementById('hotelSearchForm').submit()">
        <option value="">All Categories</option>
        @foreach($categories as $c)<option @selected($c->slug==request('category')) value="{{ $c->slug }}">{{ $c->name }}</option>@endforeach
      </select>
      <select name="approval" class="fc" onchange="document.getElementById('hotelSearchForm').submit()">
        <option value="">All Approval</option>
        <option value="0" {{ request('approval')==='0'?'selected':'' }}>Pending</option>
        <option value="1" {{ request('approval')==='1'?'selected':'' }}>Approved</option>
        <option value="2" {{ request('approval')==='2'?'selected':'' }}>Rejected</option>
      </select>
      <select name="status" class="fc" onchange="document.getElementById('hotelSearchForm').submit()">
        <option value="">All Status</option>
        <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
      </select>
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <a href="{{ route('admin.hotel_management.hotels') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>

  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
        <th>#</th><th>Hotel</th><th>Vendor</th><th>Location</th><th>Rooms</th><th>Rating</th><th>Approval</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($hotels as $hotel)
        @php
          $hc = $hotel->hotel_contents->where('lang_id',$defaultLang->id)->first() ?? $hotel->hotel_contents->first();
          $today = date('Y-m-d');
          $ap = $hotel->approval_status ?? 0;
        @endphp
        <tr>
          <td><input type="checkbox" class="bulk-check" data-val="{{ $hotel->id }}"></td>
          <td class="td-muted">#{{ str_pad($hotel->hotel_display_id,2,'0',STR_PAD_LEFT) }}</td>
          <td>
            <div class="td-pair">
              <div class="td-avatar" style="border-radius:8px;overflow:hidden">
                @if($hotel->logo)
                  <img src="{{ asset('assets/img/hotel/logo/'.$hotel->logo) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                  {{ strtoupper(substr($hc->title??'H',0,2)) }}
                @endif
              </div>
              <div>
                <div class="td-main">{{ Str::limit($hc->title??'—',35) }}</div>
                <div class="td-sub">@for($s=1;$s<=$hotel->stars;$s++)★@endfor {{ $hotel->stars }}★</div>
              </div>
            </div>
          </td>
          <td>
            @if($hotel->vendor_id==0)<span class="badge green no-dot">Admin</span>
            @else<a href="{{ route('admin.vendor_management.vendor_details',['id'=>optional($hotel->vendor)->id,'language'=>$defaultLang->code]) }}" style="color:var(--sz-blue);text-decoration:none;font-weight:500">{{ optional($hotel->vendor)->username??'—' }}</a>@endif
          </td>
          <td class="td-muted" style="font-size:12px">{{ Str::limit($hc->address??'—',30) }}</td>
          <td><span class="badge blue no-dot">{{ $hotel->room->count() }} rooms</span></td>
          <td>
            <span style="color:var(--sz-amber);font-weight:700;font-size:13px">{{ number_format($hotel->average_rating,1) }}</span>
            <span style="color:var(--sz-muted);font-size:11px">/ 5</span>
          </td>
          <td>
            @if($ap==1)<span class="badge green">Approved</span>
            @elseif($ap==2)<span class="badge red">Rejected</span>
            @else
              <div style="display:flex;gap:4px">
                <form action="{{ route('admin.hotel_management.approve_hotel',$hotel->id) }}" method="POST" style="display:inline">@csrf
                  <button type="submit" class="btn btn-success btn-xs" title="Approve"><i class="ti ti-check"></i></button>
                </form>
                <form action="{{ route('admin.hotel_management.reject_hotel',$hotel->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Reject this hotel?')">@csrf
                  <button type="submit" class="btn btn-danger btn-xs" title="Reject"><i class="ti ti-x"></i></button>
                </form>
              </div>
            @endif
          </td>
          <td>
            <form action="{{ route('admin.hotel_management.update_hotel_status') }}" method="POST" style="display:inline">
              @csrf
              <input type="hidden" name="id" value="{{ $hotel->id }}">
              <input type="hidden" name="status" value="{{ $hotel->status==1?0:1 }}">
              <button type="submit" class="btn {{ $hotel->status==1?'btn-success':'btn-light' }} btn-xs" title="{{ $hotel->status==1?'Active — click to deactivate':'Inactive — click to activate' }}">
                {{ $hotel->status==1?'Active':'Inactive' }}
              </button>
            </form>
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('admin.hotel_management.edit_hotel',['id'=>$hotel->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="Edit"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.hotel_management.delete_hotel',$hotel->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete hotel?')">@csrf
                <button class="btn btn-danger btn-xs" title="Delete"><i class="ti ti-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="10" style="text-align:center;padding:48px;color:var(--sz-muted)">
          <i class="ti ti-building-off" style="font-size:36px;display:block;margin-bottom:10px"></i>
          No hotels found
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    {{ $hotels->appends(request()->query())->links() }}
    <span class="pg-info">{{ $hotels->firstItem() }}–{{ $hotels->lastItem() }} of {{ $hotels->total() }}</span>
  </div>
</div>
@endsection
