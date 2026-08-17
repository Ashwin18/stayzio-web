@extends('vendors.layout')
@section('section','Rooms')
@section('page','Coupons')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Coupons</h2><p>Manage discount coupons for your rooms</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('vendor.room_management.coupon.bulk_delete') }}"><i class="ti ti-trash"></i> Delete</button>
    <a href="{{ route('vendor.room_management.coupon.create_page',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Coupon</a>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>Code</th><th>Name</th><th>Type</th><th>Value</th><th>Start</th><th>End</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($coupons as $c)
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $c->id }}"></td>
        <td><span class="badge blue no-dot" style="font-family:monospace">{{ $c->code }}</span></td>
        <td class="td-main">{{ $c->name??'—' }}</td>
        <td class="td-muted">{{ ucfirst($c->type??'—') }}</td>
        <td class="fw6">{{ $c->type=='percentage' ? $c->value.'%' : ($currencyInfo->base_currency_symbol??'₹').$c->value }}</td>
        <td class="td-muted">{{ $c->start_date ? \Carbon\Carbon::parse($c->start_date)->format('d M Y') : '—' }}</td>
        <td class="td-muted">{{ $c->end_date ? \Carbon\Carbon::parse($c->end_date)->format('d M Y') : '—' }}</td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('vendor.room_management.coupon.edit_page',['id'=>$c->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
            <form action="{{ route('vendor.room_management.coupon.delete',$c->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-ticket" style="font-size:32px;display:block;margin-bottom:10px"></i>No coupons created yet</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>
@endsection
