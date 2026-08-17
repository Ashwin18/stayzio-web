@extends('admin.layout')
@section('section','Bookings')
@section('page','Coupons')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Coupons</h2><p>Discount coupon management</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.room_management.coupon.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Coupon</a>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Code</th><th>Discount</th><th>Min Amount</th><th>Max Uses</th><th>Used</th><th>Expiry</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($coupons as $c)
        <tr>
          <td class="td-muted">{{ $c->id }}</td>
          <td><span style="font-family:monospace;background:var(--navy3);padding:3px 8px;border-radius:5px;font-weight:700;color:var(--text)">{{ $c->code }}</span></td>
          <td class="fw6 text-green">
            @if($c->discount_type=='percentage'){{ $c->discount }}%@else{{ $settings->base_currency_symbol }}{{ number_format($c->discount,0) }}@endif
          </td>
          <td class="td-muted">{{ $settings->base_currency_symbol }}{{ number_format($c->minimum_amount ?? 0,0) }}</td>
          <td class="td-muted">{{ $c->max_uses ?? '∞' }}</td>
          <td>{{ $c->used_times ?? 0 }}</td>
          <td class="td-muted">{{ $c->end_date ?? 'No expiry' }}</td>
          <td>@if($c->status)<span class="badge green">Active</span>@else<span class="badge red">Inactive</span>@endif</td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="{{ route('admin.room_management.coupon.edit', $c->id) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.room_management.coupon.destroy', $c->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete coupon?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-ticket" style="font-size:32px;display:block;margin-bottom:10px"></i>No coupons found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
