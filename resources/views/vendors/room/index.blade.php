@extends('vendors.layout')
@section('section','Rooms')
@section('page','All Rooms')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Rooms</h2><p>{{ $rooms->total() }} rooms listed</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('vendor.room_management.bulk_delete.room') }}"><i class="ti ti-trash"></i> Delete</button>
    <a href="{{ route('vendor.room_management.create_room',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Room</a>
  </div>
</div>
<div class="card">
  <form action="{{ route('vendor.room_management.rooms') }}" method="GET">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <select name="status" class="fc" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
      </select>
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
    </div>
  </form>
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>#</th><th>Room</th><th>Hotel</th><th>3hr</th><th>6hr</th><th>Full</th><th>Featured</th><th>Status</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($rooms as $room)
      @php
        $rc  = $room->room_content->where('lang_id',$language->id)->first() ?? $room->room_content->first();
        $hc  = optional($room->hotel)->hotel_contents->first();
        $feat= \App\Models\RoomFeature::where('room_id',$room->id)->first();
        $today=date('Y-m-d');
      @endphp
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $room->id }}"></td>
        <td class="td-muted">#{{ str_pad($room->id,3,'0',STR_PAD_LEFT) }}</td>
        <td class="td-main">{{ Str::limit(optional($rc)->name??'—',35) }}</td>
        <td class="td-muted">{{ Str::limit(optional($hc)->title??'—',30) }}</td>
        <td class="fw6">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($room->price_3hrs??0) }}</td>
        <td class="fw6">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($room->price_6hrs??0) }}</td>
        <td class="fw6">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($room->price_fullday??0) }}</td>
        <td>
          @if(is_null($feat))<span class="badge muted">—</span>
          @elseif($feat->order_status=='apporved'&&$feat->end_date>=$today)<span class="badge green">Featured</span>
          @elseif($feat->order_status=='pending')<span class="badge amber">Pending</span>
          @else<span class="badge red">Expired</span>@endif
        </td>
        <td>@if($room->status)<span class="badge green">Active</span>@else<span class="badge muted">Inactive</span>@endif</td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('vendor.room_management.edit_room',['id'=>$room->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
            <a href="{{ route('vendor.room_management.manage_additional_service',['id'=>$room->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="Add-ons"><i class="ti ti-package"></i></a>
            <form action="{{ route('vendor.room_management.delete_room',$room->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="10" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-bed" style="font-size:32px;display:block;margin-bottom:10px"></i>No rooms found. <a href="{{ route('vendor.room_management.create_room',['language'=>$defaultLang->code]) }}" style="color:var(--red)">Add your first room</a></td></tr>
      @endforelse
    </tbody>
  </table></div>
  <div class="pagination">{{ $rooms->appends(request()->all())->links() }}<span class="pg-info">{{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} of {{ $rooms->total() }}</span></div>
</div>
@endsection
