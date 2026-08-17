@extends('admin.layout')
@section('section','Property / Rooms')
@section('page','All Rooms')
@section('content')
@php $adminUser = App\Models\Admin::first(); @endphp

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Rooms</h2>
    <p>{{ $rooms->total() }} rooms across all hotels</p>
  </div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.room_management.bulk_delete.room') }}">
      <i class="ti ti-trash"></i> Bulk Delete
    </button>
    <a href="{{ route('admin.room_management.select_vendor') }}" class="btn btn-primary">
      <i class="ti ti-plus"></i> Add Room
    </a>
  </div>
</div>

<div class="card">
  <form action="{{ route('admin.room_management.rooms') }}" method="GET" id="roomSearchForm">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <select name="vendor_id" class="fc" onchange="document.getElementById('roomSearchForm').submit()">
        <option value="">All Vendors</option>
        <option value="admin" @selected(request('vendor_id')=='admin')>{{ $adminUser->username??'Admin' }} (admin)</option>
        @foreach($vendors as $v)<option @selected($v->id==request('vendor_id')) value="{{ $v->id }}">{{ $v->username }}</option>@endforeach
      </select>
      <select name="roomCategories" class="fc" onchange="document.getElementById('roomSearchForm').submit()">
        <option value="">All Categories</option>
        @foreach($roomCategories as $rc)<option @selected($rc->name==request('roomCategories')) value="{{ $rc->name }}">{{ $rc->name }}</option>@endforeach
      </select>
      <select name="status" class="fc" onchange="document.getElementById('roomSearchForm').submit()">
        <option value="">All Status</option>
        <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
      </select>
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <a href="{{ route('admin.room_management.rooms') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>

  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
        <th>#</th><th>Room</th><th>Hotel</th><th>Vendor</th><th>Category</th>
        <th>3hr</th><th>6hr</th><th>Full</th><th>Featured</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($rooms as $room)
        @php
          $rc = $room->room_content->where('lang_id',$defaultLang->id)->first() ?? $room->room_content->first();
          $hc = optional($room->hotel)->hotel_contents->first();
          $today = date('Y-m-d');
          $feat = App\Models\RoomFeature::where('room_id',$room->id)->first();
          $sym = $settings->base_currency_symbol;
          $prices = $room->hourly_room_prices ?? collect();
          $p3  = $prices->where('hour_id',1)->first();
          $p6  = $prices->where('hour_id',2)->first();
          $pFull = $prices->where('hour_id',3)->first();
        @endphp
        <tr>
          <td><input type="checkbox" class="bulk-check" data-val="{{ $room->id }}"></td>
          <td class="td-muted">#{{ str_pad($room->id,3,'0',STR_PAD_LEFT) }}</td>
          <td>
            <div class="td-main">
              @if($rc)<a href="{{ route('frontend.room.details',['slug'=>$rc->slug,'id'=>$room->id]) }}" target="_blank" style="color:var(--sz-blue);text-decoration:none">{{ Str::limit($rc->name,30) }}</a>@else—@endif
            </div>
            <div class="td-sub">{{ $room->number_of_rooms_of_this_same_type??1 }} of this type</div>
          </td>
          <td class="td-muted" style="font-size:12px">{{ Str::limit(optional($hc)->title??'—',25) }}</td>
          <td>
            @if($room->vendor_id==0)<span class="badge green no-dot">Admin</span>
            @else<span class="badge muted no-dot">{{ optional($room->vendor)->username??'—' }}</span>@endif
          </td>
          <td class="td-muted" style="font-size:11px">{{ $room->category_name??'—' }}</td>
          <td class="fw6" style="font-size:12px">{{ $p3 ? $sym.number_format($p3->price,0) : '—' }}</td>
          <td class="fw6" style="font-size:12px">{{ $p6 ? $sym.number_format($p6->price,0) : '—' }}</td>
          <td class="fw6" style="font-size:12px">{{ $pFull ? $sym.number_format($pFull->price,0) : '—' }}</td>
          <td>
            @if(is_null($feat))<span class="badge muted no-dot">—</span>
            @elseif($feat->order_status=='apporved'&&$feat->end_date>=$today)<span class="badge green">Featured</span>
            @elseif($feat->order_status=='pending')<span class="badge amber">Pending</span>
            @else<span class="badge red">Expired</span>@endif
          </td>
          <td>
            <form action="{{ route('admin.room_management.update_room_status') }}" method="POST" style="display:inline">
              @csrf<input type="hidden" name="id" value="{{ $room->id }}">
              <input type="hidden" name="status" value="{{ $room->status==1?0:1 }}">
              <button type="submit" class="btn {{ $room->status==1?'btn-success':'btn-light' }} btn-xs">
                {{ $room->status==1?'Active':'Inactive' }}
              </button>
            </form>
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('admin.room_management.edit_room',['id'=>$room->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="Edit"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.room_management.delete_room',$room->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete room?')">@csrf
                <button class="btn btn-danger btn-xs" title="Delete"><i class="ti ti-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="12" style="text-align:center;padding:48px;color:var(--sz-muted)">
          <i class="ti ti-bed-off" style="font-size:36px;display:block;margin-bottom:10px"></i>No rooms found
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    {{ $rooms->appends(request()->query())->links() }}
    <span class="pg-info">{{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} of {{ $rooms->total() }}</span>
  </div>
</div>
@endsection
