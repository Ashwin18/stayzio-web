@extends('vendors.layout')
@section('section','Finance')
@section('page','Subscription Log')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Subscription Log</h2>
    <p>Your plan purchase history</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.plan.extend.index') }}" class="btn btn-primary"><i class="ti ti-package"></i> Buy / Extend Plan</a>
  </div>
</div>

{{-- Active plan summary --}}
@if(isset($current_membership) && $current_membership)
@php
  $pkg = \App\Models\Package::find($current_membership->package_id);
  $exp = \Carbon\Carbon::parse($current_membership->expire_date);
  $daysLeft = now()->diffInDays($exp, false);
@endphp
<div style="display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:10px;margin-bottom:16px">
  <i class="ti ti-circle-check" style="font-size:22px;color:var(--green);flex-shrink:0"></i>
  <div style="flex:1">
    <div style="font-size:13px;font-weight:700;color:var(--text)">Active: {{ optional($pkg)->title ?? 'Plan' }}</div>
    <div style="font-size:11px;color:var(--muted)">
      {{ optional($pkg)->term ? ucfirst(optional($pkg)->term).' plan' : '' }}
      · Expires: {{ optional($pkg)->term === 'lifetime' ? 'Lifetime' : $exp->format('d M Y') }}
      @if($daysLeft > 0 && $daysLeft <= 30 && optional($pkg)->term !== 'lifetime')
        <span style="color:var(--amber);font-weight:600">· {{ $daysLeft }} days left</span>
      @endif
    </div>
  </div>
  <a href="{{ route('vendor.plan.extend.index') }}" class="btn btn-success btn-sm">Extend</a>
</div>
@endif

<div class="card">
  <form action="{{ url()->current() }}" method="GET">
    <div class="filters">
      <input class="fc" type="text" name="search" placeholder="🔍  Search transaction ID…" value="{{ request('search') }}" style="width:220px">
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <div class="filter-spacer"></div>
      <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th>Transaction ID</th><th>Package</th><th>Amount</th><th>Method</th><th>Start</th><th>Expires</th><th>Status</th><th>Receipt</th>
      </tr></thead>
      <tbody>
        @forelse($memberships as $m)
        @php $pkg = \App\Models\Package::find($m->package_id); @endphp
        <tr>
          <td class="td-main" style="font-size:12px;font-family:monospace">{{ Str::limit($m->transaction_id,30) }}</td>
          <td>
            <div class="td-main">{{ optional($pkg)->title ?? 'N/A' }}</div>
            <div class="td-sub">{{ optional($pkg)->term ? ucfirst(optional($pkg)->term) : '—' }}</div>
          </td>
          <td class="fw6">
            @if($m->price == 0) <span class="badge green no-dot">Free</span>
            @else {{ $settings->base_currency_symbol ?? '₹' }}{{ number_format($m->price, 0) }}@endif
          </td>
          <td><span class="badge blue no-dot">{{ ucfirst($m->payment_method ?? '—') }}</span></td>
          <td class="td-muted">
            @if(\Carbon\Carbon::parse($m->start_date)->year == 9999)
              <span class="badge muted no-dot">Not activated</span>
            @else
              {{ \Carbon\Carbon::parse($m->start_date)->format('d M Y') }}
            @endif
          </td>
          <td class="td-muted">
            @if(optional($pkg)->term === 'lifetime') <span class="badge green no-dot">Lifetime</span>
            @elseif(\Carbon\Carbon::parse($m->start_date)->year == 9999) —
            @else {{ \Carbon\Carbon::parse($m->expire_date)->format('d M Y') }}
            @endif
          </td>
          <td>
            @if($m->status==1)<span class="badge green">Active</span>
            @elseif($m->status==0)<span class="badge amber">Pending</span>
            @else<span class="badge red">Rejected</span>@endif
          </td>
          <td>
            @if(!empty($m->receipt))
              <a href="{{ asset('assets/img/membership/receipt/'.$m->receipt) }}" target="_blank" class="btn btn-secondary btn-xs" title="View Receipt"><i class="ti ti-receipt"></i></a>
            @else <span class="td-muted">—</span>@endif
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--muted)">
          <i class="ti ti-receipt-off" style="font-size:36px;display:block;margin-bottom:10px"></i>No subscription records found
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $memberships->appends(request()->query())->links() }}</div>
</div>
@endsection
