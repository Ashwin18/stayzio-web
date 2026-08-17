@extends('admin.layout')
@section('section','People')
@section('page','Vendors')
@section('content')

{{-- Summary strip --}}
@php
  $totalV  = \App\Models\Vendor::count();
  $activeV = \App\Models\Vendor::where('status',1)->count();
  $inactiveV = $totalV - $activeV;
@endphp
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
  <div style="flex:1;min-width:140px;background:var(--sz-surface);border:1px solid var(--sz-border);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px">
    <div style="width:36px;height:36px;background:rgba(37,99,235,.1);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="ti ti-building-store" style="font-size:18px;color:var(--sz-blue)"></i></div>
    <div><div style="font-size:20px;font-weight:800;color:var(--sz-text)">{{ $totalV }}</div><div style="font-size:11px;color:var(--sz-muted)">Total vendors</div></div>
  </div>
  <div style="flex:1;min-width:140px;background:var(--sz-surface);border:1px solid var(--sz-border);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px">
    <div style="width:36px;height:36px;background:rgba(22,163,74,.1);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="ti ti-circle-check" style="font-size:18px;color:var(--sz-green)"></i></div>
    <div><div style="font-size:20px;font-weight:800;color:var(--sz-text)">{{ $activeV }}</div><div style="font-size:11px;color:var(--sz-muted)">Active</div></div>
  </div>
  <div style="flex:1;min-width:140px;background:var(--sz-surface);border:1px solid {{ $inactiveV>0?'rgba(217,119,6,.3)':'var(--sz-border)' }};border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px">
    <div style="width:36px;height:36px;background:rgba(217,119,6,.1);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="ti ti-clock-hour-4" style="font-size:18px;color:var(--sz-amber)"></i></div>
    <div><div style="font-size:20px;font-weight:800;color:var(--sz-text)">{{ $inactiveV }}</div><div style="font-size:11px;color:var(--sz-muted)">Awaiting activation</div></div>
  </div>
</div>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Vendors</h2>
    <p>{{ $vendors->total() }} registered hotel partners</p>
  </div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.vendor_management.bulk_delete_vendor') }}"><i class="ti ti-trash"></i> Bulk Delete</button>
    <a href="{{ route('admin.vendor_management.add_vendor',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Vendor</a>
  </div>
</div>

<div class="card">
  <form action="{{ route('admin.vendor_management.registered_vendor') }}" method="GET">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <input name="info" class="fc" placeholder="🔍  Search username or email…" value="{{ request('info') }}" style="width:260px">
      <select name="status" class="fc" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
        <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
      </select>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <div class="filter-spacer"></div>
      <a href="{{ route('admin.vendor_management.registered_vendor') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>

  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
        <th>#</th><th>Vendor</th><th>Contact</th><th>Hotels</th><th>Balance</th><th>Joined</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($vendors as $v)
        <tr>
          <td><input type="checkbox" class="bulk-check" data-val="{{ $v->id }}"></td>
          <td class="td-muted">#V-{{ str_pad($v->id,3,'0',STR_PAD_LEFT) }}</td>
          <td>
            <div class="td-pair">
              <div class="av {{ $v->status?'red':'muted' }}" style="font-size:13px">{{ strtoupper(substr($v->username,0,2)) }}</div>
              <div>
                <div class="td-main">{{ $v->username }}</div>
                <div class="td-sub">{{ $v->email }}</div>
              </div>
            </div>
          </td>
          <td>
            <div class="td-main" style="font-size:12.5px">{{ $v->contact_person_name??'—' }}</div>
            <div class="td-sub">{{ $v->phone??'—' }}</div>
          </td>
          <td>
            <span class="badge blue no-dot">{{ $v->hotels->count() }} hotels</span>
            @if(($v->pending_properties_count ?? 0) > 0)
            <div style="margin-top:4px">
              <a href="{{ route('admin.property_submissions.index') }}" class="badge amber no-dot" style="font-size:10px;text-decoration:none">{{ $v->pending_properties_count }} pending review</a>
            </div>
            @endif
          </td>
          <td class="fw6 text-green">{{ $settings->base_currency_symbol }}{{ number_format($v->amount??0,0) }}</td>
          <td class="td-muted">{{ $v->created_at->format('d M Y') }}</td>
          <td>
            {{-- Inline Activate / Deactivate --}}
            <form action="{{ route('admin.vendor_management.vendor.update_account_status',$v->id) }}" method="POST" style="display:inline">
              @csrf
              @if($v->status==1)
                <input type="hidden" name="account_status" value="0">
                <button type="submit" class="btn btn-success btn-xs" onclick="return confirm('Deactivate {{ $v->username }}?')" title="Active — click to deactivate">
                  <i class="ti ti-circle-check"></i> Active
                </button>
              @else
                <input type="hidden" name="account_status" value="1">
                <button type="submit" class="btn btn-warn btn-xs" onclick="return confirm('Activate {{ $v->username }}?')" title="Inactive — click to activate">
                  <i class="ti ti-circle-x"></i> Inactive
                </button>
              @endif
            </form>
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('admin.vendor_management.vendor_details',['id'=>$v->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="View"><i class="ti ti-eye"></i></a>
              <a href="{{ route('admin.edit_management.vendor_edit',['id'=>$v->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="Edit"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.vendor_management.vendor.delete',$v->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete vendor {{ $v->username }}?')">@csrf
                <button class="btn btn-danger btn-xs" title="Delete"><i class="ti ti-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:48px;color:var(--sz-muted)">
          <i class="ti ti-building-store" style="font-size:36px;display:block;margin-bottom:10px"></i>No vendors found
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    {{ $vendors->appends(['info'=>request('info'),'status'=>request('status')])->links() }}
    <span class="pg-info">{{ $vendors->firstItem() }}–{{ $vendors->lastItem() }} of {{ $vendors->total() }}</span>
  </div>
</div>
@endsection
