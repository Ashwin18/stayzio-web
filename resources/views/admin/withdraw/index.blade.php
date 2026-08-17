@extends('admin.layout')
@section('section','Finance')
@section('page','Withdrawals')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Withdrawals</h2><p>Manage vendor payout requests</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-credit-card"></i> Payout Methods</a>
  </div>
</div>
<div style="display:flex;gap:8px;margin-bottom:14px">
  <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}" class="ftag {{ !request('status') ? 'active' : '' }}"><i class="ti ti-list"></i> All</a>
  <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code, 'status' => '0']) }}" class="ftag {{ request('status')=='0' ? 'active' : '' }}" style="color:var(--amber)"><i class="ti ti-clock"></i> Pending</a>
  <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code, 'status' => '1']) }}" class="ftag {{ request('status')=='1' ? 'active' : '' }}" style="color:var(--green)"><i class="ti ti-circle-check"></i> Approved</a>
  <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code, 'status' => '2']) }}" class="ftag {{ request('status')=='2' ? 'active' : '' }}" style="color:#f87171"><i class="ti ti-circle-x"></i> Rejected</a>
</div>
<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Vendor</th><th>Method</th><th>Amount</th><th>Charge</th><th>Payable</th><th>Requested</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($collection as $wd)
        @php $vn = $wd->vendor; @endphp
        <tr>
          <td class="td-muted">#WD-{{ str_pad($wd->id,3,'0',STR_PAD_LEFT) }}</td>
          <td><div class="td-pair"><div class="av muted">{{ strtoupper(substr($vn->username??'?',0,2)) }}</div><span class="td-main">{{ $vn->username ?? '—' }}</span></div></td>
          <td class="td-muted">{{ ucfirst($wd->method->name ?? '—') }}</td>
          <td class="fw6">{{ $settings->base_currency_symbol }}{{ number_format($wd->amount,0) }}</td>
          <td class="td-muted">{{ $settings->base_currency_symbol }}{{ number_format($wd->total_charge,0) }}</td>
          <td class="fw6 text-green">{{ $settings->base_currency_symbol }}{{ number_format($wd->payable_amount,0) }}</td>
          <td class="td-muted">{{ $wd->created_at->format('d M Y') }}</td>
          <td>
            @if($wd->status==0)<span class="badge amber">Pending</span>
            @elseif($wd->status==1)<span class="badge green">Approved</span>
            @else<span class="badge red">Rejected</span>@endif
          </td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="{{ route('admin.witdraw.view_withdraw', $wd->id) }}" class="btn btn-secondary btn-xs"><i class="ti ti-eye"></i></a>
              @if($wd->status==0)
              <a href="{{ route('admin.witdraw.approve_withdraw', $wd->id) }}" class="btn btn-success btn-xs" onclick="return confirm('Approve?')"><i class="ti ti-check"></i></a>
              <a href="{{ route('admin.witdraw.decline_withdraw', $wd->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Reject?')"><i class="ti ti-x"></i></a>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-credit-card" style="font-size:32px;display:block;margin-bottom:10px"></i>No withdrawal requests</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $collection->links() }}<span class="pg-info">{{ $collection->firstItem() }}–{{ $collection->lastItem() }} of {{ $collection->total() }}</span></div>
</div>
@endsection
