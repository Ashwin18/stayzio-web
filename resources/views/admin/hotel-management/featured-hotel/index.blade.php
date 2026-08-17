@extends('admin.layout')
@section('section','Property / Hotels')
@section('page','Featured Hotels')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Featured Hotels</h2><p>{{ $orders->total() }} featured hotel requests</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.hotel_management.featured_hotel.charge') }}" class="btn btn-secondary">
      <i class="ti ti-cash"></i> Manage Charges
    </a>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:14px">
  <a href="{{ route('admin.hotel_management.featured_hotel.all_request', ['language'=>$defaultLang->code]) }}" class="ftag {{ request()->routeIs('admin.hotel_management.featured_hotel.all_request') ? 'active' : '' }}">All</a>
  <a href="{{ route('admin.hotel_management.featured_hotel.pending_request', ['language'=>$defaultLang->code]) }}" class="ftag" style="color:var(--amber)"><i class="ti ti-clock"></i> Pending</a>
  <a href="{{ route('admin.hotel_management.featured_hotel.approved_request', ['language'=>$defaultLang->code]) }}" class="ftag" style="color:var(--green)"><i class="ti ti-circle-check"></i> Approved</a>
  <a href="{{ route('admin.hotel_management.featured_hotel.rejected_request', ['language'=>$defaultLang->code]) }}" class="ftag" style="color:#f87171"><i class="ti ti-circle-x"></i> Rejected</a>
</div>

<div class="card">
  <form id="searchForm" action="{{ route('admin.hotel_management.featured_hotel.all_request', ['language'=>$defaultLang->code]) }}" method="GET">
    <div class="filters">
      <input name="order_no" class="fc" placeholder="Order number…" value="{{ request('order_no') }}" style="width:150px">
      <input name="title" class="fc" placeholder="Hotel title…" value="{{ request('title') }}" style="width:160px">
      <select name="payment_status" class="fc" onchange="document.getElementById('searchForm').submit()">
        <option value="">All Payment</option>
        <option value="completed" {{ request('payment_status')=='completed'?'selected':'' }}>Completed</option>
        <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Pending</option>
        <option value="rejected" {{ request('payment_status')=='rejected'?'selected':'' }}>Rejected</option>
      </select>
      <select name="order_status" class="fc" onchange="document.getElementById('searchForm').submit()">
        <option value="">All Orders</option>
        <option value="pending" {{ request('order_status')=='pending'?'selected':'' }}>Pending</option>
        <option value="apporved" {{ request('order_status')=='apporved'?'selected':'' }}>Approved</option>
        <option value="rejected" {{ request('order_status')=='rejected'?'selected':'' }}>Rejected</option>
      </select>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i> Search</button>
      <a href="{{ route('admin.hotel_management.featured_hotel.all_request', ['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>

  <div class="tbl-wrap"><table>
    <thead><tr><th>#</th><th>Hotel</th><th>Vendor</th><th>Amount</th><th>Method</th><th>Duration</th><th>Payment</th><th>Order Status</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($orders as $order)
      @php
        $hotel = optional($order->hotel);
        $hc    = optional($hotel->hotel_contents)->first();
        $vn    = optional($hotel->vendor);
      @endphp
      <tr>
        <td class="td-main">#{{ $order->order_number }}</td>
        <td>
          <div class="td-main">{{ optional($hc)->title ?? '—' }}</div>
        </td>
        <td class="td-muted">{{ $vn->username ?? 'Admin' }}</td>
        <td class="fw6">{{ $order->currency_symbol }}{{ number_format($order->total ?? 0) }}</td>
        <td><span class="badge blue no-dot">{{ ucfirst($order->payment_method ?? '—') }}</span></td>
        <td>
          <div class="td-main">{{ $order->days ?? 0 }} days</div>
          @if($order->start_date)<div class="td-sub">{{ \Carbon\Carbon::parse($order->start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</div>@endif
        </td>
        <td>
          @if($order->payment_status=='completed')<span class="badge green">Paid</span>
          @elseif($order->payment_status=='pending')<span class="badge amber">Pending</span>
          @else<span class="badge red">Rejected</span>@endif
        </td>
        <td>
          @if($order->order_status=='pending')
          <form id="ohsf-{{ $order->id }}" action="{{ route('admin.hotel_management.featured_hotel.update_order_status', $order->id) }}" method="POST">
            @csrf
            <select name="order_status" class="fc" style="width:110px;padding:4px 8px;font-size:11px"
                    onchange="document.getElementById('ohsf-{{ $order->id }}').submit()">
              <option value="pending" selected>Pending</option>
              <option value="apporved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </form>
          @elseif($order->order_status=='apporved')<span class="badge green">Approved</span>
          @else<span class="badge red">Rejected</span>@endif
        </td>
        <td>
          <div style="display:flex;gap:5px">
            @if($order->attachment)
            <button onclick="document.getElementById('hreceipt-{{ $order->id }}').style.display='flex'" class="btn btn-secondary btn-xs"><i class="ti ti-receipt"></i></button>
            @endif
            <button onclick="document.getElementById('hdetail-{{ $order->id }}').style.display='flex'" class="btn btn-secondary btn-xs"><i class="ti ti-eye"></i></button>
            <form action="{{ route('admin.hotel_management.featured_hotel.delete', $order->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>

      {{-- Receipt Modal --}}
      @if($order->attachment)
      <div id="hreceipt-{{ $order->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center">
        <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px">
          <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <span class="fw6">Payment Receipt</span>
            <button onclick="document.getElementById('hreceipt-{{ $order->id }}').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
          </div>
          <div style="padding:16px;text-align:center">
            <img src="{{ asset('assets/admin/img/hotel-feature/'.$order->attachment) }}" style="max-width:100%;border-radius:8px">
          </div>
        </div>
      </div>
      @endif

      {{-- Details Modal --}}
      <div id="hdetail-{{ $order->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:9999;align-items:center;justify-content:center">
        <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:500px">
          <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <span class="fw6">Feature Request Details</span>
            <button onclick="document.getElementById('hdetail-{{ $order->id }}').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
          </div>
          <div style="padding:16px">
            <div class="stat-row"><span class="stat-lbl">Hotel</span><span class="stat-val">{{ optional($hc)->title ?? '—' }}</span></div>
            <div class="stat-row"><span class="stat-lbl">Vendor</span><span class="stat-val">{{ $vn->username ?? 'Admin' }}</span></div>
            <div class="stat-row"><span class="stat-lbl">Feature Price</span><span class="stat-val fw6 text-green">{{ $order->currency_symbol }}{{ number_format($order->total ?? 0) }}</span></div>
            <div class="stat-row"><span class="stat-lbl">Method</span><span class="stat-val">{{ ucfirst($order->payment_method ?? '—') }}</span></div>
            <div class="stat-row"><span class="stat-lbl">Total Days</span><span class="stat-val">{{ $order->days }}</span></div>
            <div class="stat-row"><span class="stat-lbl">Start Date</span><span class="stat-val">{{ $order->start_date ? \Carbon\Carbon::parse($order->start_date)->format('d M Y') : '—' }}</span></div>
            <div class="stat-row"><span class="stat-lbl">Expire Date</span><span class="stat-val">{{ $order->end_date ? \Carbon\Carbon::parse($order->end_date)->format('d M Y') : '—' }}</span></div>
          </div>
          <div style="padding:0 16px 16px">
            <button onclick="document.getElementById('hdetail-{{ $order->id }}').style.display='none'" class="btn btn-secondary btn-block">Close</button>
          </div>
        </div>
      </div>

      @empty
      <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-star" style="font-size:32px;display:block;margin-bottom:10px"></i>No featured hotel requests found</td></tr>
      @endforelse
    </tbody>
  </table></div>
  <div class="pagination">
    {{ $orders->appends(request()->all())->links() }}
    <span class="pg-info">{{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
  </div>
</div>
@endsection
