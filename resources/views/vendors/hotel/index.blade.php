@extends('vendors.layout')
@section('section','Hotels')
@section('page','All Hotels')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Hotels</h2><p>{{ $hotels->total() }} hotels listed</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('vendor.hotel_management.bulk_delete.hotel') }}"><i class="ti ti-trash"></i> Delete</button>
    <a href="{{ route('vendor.hotel_management.create_hotel',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Hotel</a>
  </div>
</div>
<div class="card">
  <form action="{{ route('vendor.hotel_management.hotels') }}" method="GET">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <select name="status" class="fc" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
      </select>
      <select name="approval_status" class="fc" onchange="this.form.submit()">
        <option value="">All Approval</option>
        <option value="0" {{ request('approval_status')==='' ? '' : (request('approval_status')=='0'?'selected':'') }}>Pending Review</option>
        <option value="1" {{ request('approval_status')=='1'?'selected':'' }}>Approved</option>
        <option value="2" {{ request('approval_status')=='2'?'selected':'' }}>Rejected</option>
      </select>
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
    </div>
  </form>
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>#</th><th>Hotel</th><th>Category</th><th>Rooms</th><th>Stars</th><th>Approval</th><th>Featured</th><th>Status</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($hotels as $hotel)
      @php
        $hc = $hotel->hotel_contents->where('lang_id',$language->id)->first() ?? $hotel->hotel_contents->first();
        $today = date('Y-m-d');
        $feat = \App\Models\HotelFeature::where('hotel_id',$hotel->id)->first();
      @endphp
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $hotel->id }}"></td>
        <td class="td-muted">#{{ str_pad($hotel->id,3,'0',STR_PAD_LEFT) }}</td>
        <td>
          <div class="td-pair">
            <div class="td-avatar">{{ strtoupper(substr(optional($hc)->title??'H',0,2)) }}</div>
            <div>
              <div class="td-main">{{ Str::limit(optional($hc)->title??'—',35) }}</div>
              <div class="td-sub">{{ Str::limit(optional($hc)->address??'',40) }}</div>
            </div>
          </div>
        </td>
        <td class="td-muted">{{ optional($hc)->hotel_category??'—' }}</td>
        <td><span class="badge blue no-dot">{{ $hotel->room->count() }}</span></td>
        <td>@for($s=1;$s<=$hotel->stars;$s++)⭐@endfor</td>
        <td>
          @php $ap = $hotel->approval_status ?? 0; @endphp
          @if($ap == 1)<span class="badge green">Approved</span>
          @elseif($ap == 2)<span class="badge red">Rejected</span>
          @else<span class="badge amber">Pending Review</span>@endif
        </td>
        <td>
          @if(is_null($feat))<span class="badge muted">—</span>
          @elseif($feat->order_status=='apporved'&&$feat->end_date>=$today)<span class="badge green">Featured</span>
          @elseif($feat->order_status=='pending')<span class="badge amber">Pending</span>
          @else<span class="badge red">Expired</span>@endif
        </td>
        <td>@if($hotel->status==1)<span class="badge green">Active</span>@else<span class="badge muted">Inactive</span>@endif</td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('vendor.hotel_management.edit_hotel',['id'=>$hotel->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
            <a href="{{ route('vendor.hotel_management.manage_counter_section',$hotel->id) }}" class="btn btn-secondary btn-xs" title="Counter"><i class="ti ti-list"></i></a>
            <form action="{{ route('vendor.hotel_management.delete_hotel',$hotel->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete hotel?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-building" style="font-size:32px;display:block;margin-bottom:10px"></i>No hotels found. <a href="{{ route('vendor.hotel_management.create_hotel',['language'=>$defaultLang->code]) }}" style="color:var(--red)">Add your first hotel</a></td></tr>
      @endforelse
    </tbody>
  </table></div>
  <div class="pagination">{{ $hotels->appends(request()->all())->links() }}<span class="pg-info">{{ $hotels->firstItem() }}–{{ $hotels->lastItem() }} of {{ $hotels->total() }}</span></div>
</div>
@endsection
