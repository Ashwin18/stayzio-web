@extends('admin.layout')
@section('section','Finance')
@section('page','Transactions')

@section('content')

{{-- Tab switcher --}}
<div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:20px">
  <button onclick="switchTab('transactions')" id="tab-transactions"
    style="padding:12px 24px;border:none;background:none;font-family:'Poppins',sans-serif;font-size:13px;font-weight:700;color:var(--red);border-bottom:2px solid var(--red);margin-bottom:-2px;cursor:pointer">
    <i class="ti ti-arrows-exchange"></i> Transactions
  </button>
  <button onclick="switchTab('subscriptions')" id="tab-subscriptions"
    style="padding:12px 24px;border:none;background:none;font-family:'Poppins',sans-serif;font-size:13px;font-weight:700;color:var(--muted);border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer">
    <i class="ti ti-package"></i> Subscriptions
  </button>
</div>

{{-- TRANSACTIONS PANEL --}}
<div id="panel-transactions">
  <div class="page-hdr">
    <div class="page-hdr-left"><h2>Transactions</h2><p>All platform financial activity — {{ $transcations->total() }} records</p></div>
  </div>
  <div class="card">
    <form action="" method="GET">
      <div class="filters">
        <input type="text" name="transcation_id" class="fc" placeholder="🔍  Transaction ID…" value="{{ request('transcation_id') }}" style="width:220px">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
        <div class="filter-spacer"></div>
        <a href="{{ route('admin.transcation') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
      </div>
    </form>
    <div class="tbl-wrap">
      <table>
        <thead><tr><th>Transaction ID</th><th>Vendor</th><th>Type</th><th>Method</th><th>Pre Balance</th><th>Amount</th><th>After Balance</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($transcations as $t)
          @php $vn = $t->vendor()->first(); @endphp
          <tr>
            <td class="td-main">#{{ $t->transcation_id }}</td>
            <td>
              @if($vn)<a href="{{ route('admin.vendor_management.vendor_details',['id'=>$vn->id,'language'=>$defaultLang->code]) }}" style="color:var(--blue);font-weight:500;text-decoration:none">{{ $vn->username }}</a>
              @else<span class="badge green no-dot">Platform</span>@endif
            </td>
            <td>
              @if($t->transcation_type=='room_booking')<span class="badge blue no-dot">Room Booking</span>
              @elseif($t->transcation_type=='withdraw')<span class="badge amber no-dot">Withdrawal</span>
              @elseif($t->transcation_type=='room_feature')<span class="badge purple no-dot">Room Feature</span>
              @else<span class="badge muted no-dot">{{ ucfirst(str_replace('_',' ',$t->transcation_type)) }}</span>@endif
            </td>
            <td class="td-muted">{{ ucfirst($t->payment_method) }}</td>
            <td class="td-muted">{{ $t->currency_symbol }}{{ number_format($t->pre_balance,0) }}</td>
            <td class="fw6">{{ $t->currency_symbol }}{{ number_format($t->grand_total,0) }}</td>
            <td class="fw6 text-green">{{ $t->currency_symbol }}{{ number_format($t->after_balance,0) }}</td>
            <td class="td-muted">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</td>
            <td>
              @if(in_array($t->payment_status,['completed','paid']))<span class="badge green">Completed</span>
              @else<span class="badge amber">Pending</span>@endif
            </td>
          </tr>
          @empty
          <tr><td colspan="9" style="text-align:center;padding:48px;color:var(--muted)">
            <i class="ti ti-receipt-off" style="font-size:36px;display:block;margin-bottom:10px"></i>No transactions found
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">{{ $transcations->appends(request()->query())->links() }}
      @if($transcations->total() > 0)
      <span class="pg-info">{{ $transcations->firstItem() }}–{{ $transcations->lastItem() }} of {{ $transcations->total() }}</span>
      @endif
    </div>
  </div>
</div>

{{-- SUBSCRIPTIONS PANEL --}}
<div id="panel-subscriptions" style="display:none">
  <div class="page-hdr">
    <div class="page-hdr-left"><h2>Subscriptions</h2><p>Vendor plan subscription log — {{ $memberships->total() }} records</p></div>
  </div>
  <div class="card">
    <form action="{{ url()->current() }}" method="GET">
      <div class="filters">
        <input type="text" name="search" class="fc" placeholder="🔍  Transaction ID…" value="{{ request('search') }}" style="width:200px">
        <input type="text" name="username" class="fc" placeholder="Vendor username…" value="{{ request('username') }}" style="width:180px">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
        <div class="filter-spacer"></div>
        <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
      </div>
    </form>
    <div class="tbl-wrap">
      <table>
        <thead><tr><th>Transaction ID</th><th>Vendor</th><th>Package</th><th>Amount</th><th>Method</th><th>Start</th><th>Expires</th><th>Status</th><th>Receipt</th></tr></thead>
        <tbody>
          @forelse($memberships as $m)
          @php $vn = \App\Models\Vendor::find($m->vendor_id); $pkg = \App\Models\Package::find($m->package_id); @endphp
          <tr>
            <td class="td-main">#{{ $m->transaction_id }}</td>
            <td>
              @if($vn)<a href="{{ route('admin.vendor_management.vendor_details',['id'=>$vn->id,'language'=>$defaultLang->code]) }}" style="color:var(--blue);font-weight:500;text-decoration:none">{{ $vn->username }}</a>@else—@endif
            </td>
            <td><span class="badge purple no-dot">{{ $pkg->title??'N/A' }}</span></td>
            <td class="fw6">{{ $settings->base_currency_symbol }}{{ number_format($m->price,0) }}</td>
            <td><span class="badge blue no-dot">{{ ucfirst($m->payment_method) }}</span></td>
            <td class="td-muted">{{ $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('d M Y') : '—' }}</td>
            <td class="td-muted">
              @if($m->expire_date && \Carbon\Carbon::parse($m->expire_date)->year >= 9999)
                <span class="badge green no-dot">Lifetime</span>
              @else
                {{ $m->expire_date ? \Carbon\Carbon::parse($m->expire_date)->format('d M Y') : '—' }}
              @endif
            </td>
            <td>
              @if($m->status==1)<span class="badge green">Active</span>
              @elseif($m->status==0)<span class="badge amber">Pending</span>
              @else<span class="badge red">Expired</span>@endif
            </td>
            <td>
              @if($m->receipt)
                <a href="{{ asset('assets/admin/img/payment-receipt/'.$m->receipt) }}" target="_blank" class="btn btn-secondary btn-xs"><i class="ti ti-receipt"></i></a>
              @else—@endif
            </td>
          </tr>
          @empty
          <tr><td colspan="9" style="text-align:center;padding:48px;color:var(--muted)">
            <i class="ti ti-receipt-off" style="font-size:36px;display:block;margin-bottom:10px"></i>No subscriptions found
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination">{{ $memberships->appends(request()->query())->links() }}
      @if($memberships->total() > 0)
      <span class="pg-info">{{ $memberships->firstItem() }}–{{ $memberships->lastItem() }} of {{ $memberships->total() }}</span>
      @endif
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
function switchTab(tab) {
  var isTrans = tab === 'transactions';
  document.getElementById('panel-transactions').style.display  = isTrans ? '' : 'none';
  document.getElementById('panel-subscriptions').style.display = isTrans ? 'none' : '';
  document.getElementById('tab-transactions').style.color      = isTrans ? 'var(--red)' : 'var(--muted)';
  document.getElementById('tab-transactions').style.borderBottomColor  = isTrans ? 'var(--red)' : 'transparent';
  document.getElementById('tab-subscriptions').style.color     = isTrans ? 'var(--muted)' : 'var(--red)';
  document.getElementById('tab-subscriptions').style.borderBottomColor = isTrans ? 'transparent' : 'var(--red)';
}
</script>
@endsection