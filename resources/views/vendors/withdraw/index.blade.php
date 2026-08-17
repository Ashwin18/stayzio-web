@extends('vendors.layout')
@section('section','Finance')
@section('page','Withdrawals')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Withdrawals</h2>
    <p>Manage your payout requests</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.withdraw.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> New Request</a>
  </div>
</div>

@php
  $balance   = optional(Auth::guard('vendor')->user())->amount ?? 0;
  $totalReq  = $collection->count();
  $approved  = $collection->where('status',1)->count();
  $pending   = $collection->where('status',0)->count();
@endphp

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:14px">
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon g"><i class="ti ti-wallet"></i></div></div>
    <div class="kpi-val">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($balance,0) }}</div>
    <div class="kpi-lbl">Available balance</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon b"><i class="ti ti-credit-card"></i></div></div>
    <div class="kpi-val">{{ $totalReq }}</div>
    <div class="kpi-lbl">Total requests</div>
  </div>
  <div class="kpi">
    <div class="kpi-top"><div class="kpi-icon a"><i class="ti ti-clock"></i></div></div>
    <div class="kpi-val">{{ $pending }}</div>
    <div class="kpi-lbl">Pending</div>
  </div>
</div>

<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>#</th><th>Amount</th><th>Method</th><th>Details</th><th>Requested</th><th>Status</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($collection as $wd)
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $wd->id }}"></td>
        <td class="td-muted">{{ $loop->iteration }}</td>
        <td class="fw6 text-green">{{ $currencyInfo->base_currency_symbol??'₹' }}{{ number_format($wd->amount??0,0) }}</td>
        <td class="td-main">{{ optional($wd->method)->name??'—' }}</td>
        <td>
          @php $details = json_decode($wd->withdrawal_info??'{}'); @endphp
          @if($details)
          <div style="font-size:11px;color:var(--muted)">
            @foreach((array)$details as $k=>$v)
            <span>{{ ucfirst(str_replace('_',' ',$k)) }}: {{ $v }}</span><br>
            @endforeach
          </div>
          @endif
        </td>
        <td class="td-muted">{{ $wd->created_at ? \Carbon\Carbon::parse($wd->created_at)->format('d M Y') : '—' }}</td>
        <td>
          @if($wd->status==1)<span class="badge green">Approved</span>
          @elseif($wd->status==2)<span class="badge red">Rejected</span>
          @else<span class="badge amber">Pending</span>@endif
        </td>
        <td>
          @if($wd->status==0)
          <form action="{{ route('vendor.witdraw.delete_withdraw') }}" method="POST" style="display:inline" onsubmit="return confirm('Cancel request?')">
            @csrf<input type="hidden" name="id" value="{{ $wd->id }}">
            <button class="btn btn-danger btn-xs"><i class="ti ti-x"></i> Cancel</button>
          </form>
          @else
          <span class="td-muted">—</span>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-credit-card" style="font-size:32px;display:block;margin-bottom:10px"></i>No withdrawal requests yet</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>
@endsection
