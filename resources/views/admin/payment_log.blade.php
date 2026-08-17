@extends('admin.layout')
@section('section','Finance')
@section('page','Subscriptions')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Subscriptions</h2><p>Vendor plan subscription log</p></div>
</div>
<div class="card">
  <form action="{{ url()->current() }}" method="GET">
    <div class="filters">
      <input type="text" name="search" class="fc" placeholder="Search transaction ID…" value="{{ request('search') }}" style="width:200px">
      <input type="text" name="username" class="fc" placeholder="Search username…" value="{{ request('username') }}" style="width:180px">
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>Transaction ID</th><th>Vendor</th><th>Package</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th><th>Receipt</th></tr></thead>
      <tbody>
        @forelse($memberships as $m)
        @php $vn = \App\Models\Vendor::find($m->vendor_id); $pkg = \App\Models\Package::find($m->package_id); @endphp
        <tr>
          <td class="td-main">#{{ $m->transaction_id }}</td>
          <td>
            @if($vn)<a href="{{ route('admin.vendor_management.vendor_details', ['id' => $vn->id, 'language' => $defaultLang->code]) }}" style="color:var(--blue)">{{ $vn->username }}</a>@else—@endif
          </td>
          <td><span class="badge purple no-dot">{{ $pkg->title ?? 'N/A' }}</span></td>
          <td class="fw6">{{ $settings->base_currency_symbol }}{{ number_format($m->price,0) }}</td>
          <td><span class="badge blue no-dot">{{ ucfirst($m->payment_method) }}</span></td>
          <td>
            @if($m->status==1)<span class="badge green">Active</span>
            @elseif($m->status==0)<span class="badge amber">Pending</span>
            @else<span class="badge red">Expired</span>@endif
          </td>
          <td class="td-muted">{{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}</td>
          <td>@if($m->receipt)<a href="{{ asset('assets/admin/img/payment-receipt/'.$m->receipt) }}" target="_blank" class="btn btn-secondary btn-xs"><i class="ti ti-receipt"></i></a>@else—@endif</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-receipt" style="font-size:32px;display:block;margin-bottom:10px"></i>No subscriptions found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $memberships->links() }}<span class="pg-info">{{ $memberships->firstItem() }}–{{ $memberships->lastItem() }} of {{ $memberships->total() }}</span></div>
</div>
@endsection
