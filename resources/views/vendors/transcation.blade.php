@extends('vendors.layout')
@section('section','Finance')
@section('page','Transactions')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Transactions</h2><p>{{ $transcations->total() }} total transactions</p></div>
</div>
<div class="card">
  <form action="{{ route('vendor.transcation') }}" method="GET">
    <div class="filters">
      <input name="transcation_id" class="fc" placeholder="Transaction ID…" value="{{ request('transcation_id') }}" style="width:200px">
      <div class="filter-spacer"></div>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <a href="{{ route('vendor.transcation') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
  <div class="tbl-wrap"><table>
    <thead><tr><th>#</th><th>Transaction ID</th><th>Customer</th><th>Gateway</th><th>Amount</th><th>Date</th><th>Status</th></tr></thead>
    <tbody>
      @forelse($transcations as $t)
      <tr>
        <td class="td-muted">{{ $loop->iteration }}</td>
        <td class="td-main">{{ $t->transcation_id??'—' }}</td>
        <td>{{ optional($t->user)->name??$t->booking_name??'—' }}</td>
        <td><span class="badge blue no-dot">{{ ucfirst($t->payment_method??'—') }}</span></td>
        <td class="fw6 text-green">{{ $t->currency_symbol??'₹' }}{{ number_format($t->amount??0,0) }}</td>
        <td class="td-muted">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</td>
        <td><span class="badge green">Success</span></td>
      </tr>
      @empty
      <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)">No transactions found</td></tr>
      @endforelse
    </tbody>
  </table></div>
  <div class="pagination">{{ $transcations->appends(request()->all())->links() }}<span class="pg-info">{{ $transcations->firstItem() }}–{{ $transcations->lastItem() }} of {{ $transcations->total() }}</span></div>
</div>
@endsection
