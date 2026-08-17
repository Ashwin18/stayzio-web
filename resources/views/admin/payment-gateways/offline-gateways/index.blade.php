@extends('admin.layout')
@section('section','Settings')
@section('page','Offline Payment Gateways')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Offline Payment Gateways</h2><p>Manage manual payment methods</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.settings.payment_gateways.online_gateways') }}" class="btn btn-secondary"><i class="ti ti-credit-card"></i> Online Gateways</a>
    <a href="{{ route('admin.settings.payment_gateways.create_offline_gateway') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Gateway</a>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr><th>#</th><th>Gateway Name</th><th>Serial</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($offlineGateways as $gw)
      <tr>
        <td class="td-muted">{{ $loop->iteration }}</td>
        <td class="td-main">{{ $gw->name }}</td>
        <td class="td-muted">{{ $gw->serial_number }}</td>
        <td>
          <form id="statusForm-{{ $gw->id }}" action="{{ route('admin.settings.payment_gateways.update_status',['id'=>$gw->id]) }}" method="POST" style="display:inline">@csrf
            <select name="status" class="fc" style="width:100px;padding:4px 8px;font-size:11px" onchange="document.getElementById('statusForm-{{ $gw->id }}').submit()">
              <option value="1" {{ $gw->status==1?'selected':'' }}>Active</option>
              <option value="0" {{ $gw->status==0?'selected':'' }}>Inactive</option>
            </select>
          </form>
        </td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('admin.settings.payment_gateways.edit_offline_gateway',['id'=>$gw->id]) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
            <form action="{{ route('admin.settings.payment_gateways.delete_offline_gateway',['id'=>$gw->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete gateway?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>
      @empty<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No offline gateways found</td></tr>@endforelse
    </tbody>
  </table></div>
</div>
@endsection
