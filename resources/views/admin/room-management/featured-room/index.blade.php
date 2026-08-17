@extends('admin.layout')
@section('section','Property / Rooms')
@section('page','Featured Rooms')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Featured Rooms</h2><p>{{ $orders->total() }} featured room requests</p></div>
</div>

{{-- Status tabs --}}
<div style="display:flex;gap:8px;margin-bottom:14px">
  <a href="{{ route('admin.room_management.featured_room.all_request', ['language'=>$defaultLang->code]) }}" class="ftag {{ request()->routeIs('admin.room_management.featured_room.all_request') ? 'active' : '' }}">All</a>
  <a href="{{ route('admin.room_management.featured_room.pending_request', ['language'=>$defaultLang->code]) }}" class="ftag" style="color:var(--amber)"><i class="ti ti-clock"></i> Pending</a>
  <a href="{{ route('admin.room_management.featured_room.approved_request', ['language'=>$defaultLang->code]) }}" class="ftag" style="color:var(--green)"><i class="ti ti-circle-check"></i> Approved</a>
  <a href="{{ route('admin.room_management.featured_room.rejected_request', ['language'=>$defaultLang->code]) }}" class="ftag" style="color:#f87171"><i class="ti ti-circle-x"></i> Rejected</a>
</div>

<div class="card">
  <form id="searchForm" action="{{ route('admin.room_management.featured_room.all_request') }}" method="GET">
    <input type="hidden" name="language" value="{{ $defaultLang->code }}">
    <div class="filters">
      <input name="order_no" class="fc" placeholder="Order number…" value="{{ request('order_no') }}" style="width:150px">
      <input name="title" class="fc" placeholder="Room title…" value="{{ request('title') }}" style="width:160px">
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
      <a href="{{ route('admin.room_management.featured_room.all_request', ['language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>

  <div class="tbl-wrap">
    <table>
      <thead>
        <tr><th>#</th><th>Room</th><th>Vendor</th><th>Package</th><th>Amount</th><th>Method</th><th>Duration</th><th>Payment</th><th>Order Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
        @php
          $room = $order->room;
          $rc = $room ? \App\Models\RoomContent::where('room_id', $room->id)->where('language_id', $defaultLang->id)->first() : null;
          $vn = optional($room)->vendor;
          $hotelTitle = $room && $room->hotel ? optional(\App\Models\HotelContent::where('hotel_id', $room->hotel->id)->where('language_id', $defaultLang->id)->first())->title : null;
        @endphp
        <tr>
          <td class="td-main">#{{ $order->order_number }}</td>
          <td>
            <div class="td-main">{{ optional($rc)->title ?? '—' }}</div>
            <div class="td-sub">{{ $hotelTitle ?? '' }}</div>
          </td>
          <td class="td-muted">{{ $vn->username ?? 'Admin' }}</td>
          <td><span class="badge purple no-dot">{{ $order->package_name ?? '—' }}</span></td>
          <td class="fw6">{{ $order->currency_symbol }}{{ number_format($order->total_amount ?? 0) }}</td>
          <td><span class="badge blue no-dot">{{ ucfirst($order->payment_method ?? '—') }}</span></td>
          <td>
            @if($order->order_status == 'apporved')
              <div class="td-main">{{ $order->days }} days</div>
              <div class="td-sub">{{ \Carbon\Carbon::parse($order->start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</div>
            @else
              <span class="td-muted">{{ $order->days }} days</span>
            @endif
          </td>
          <td>
            @if($order->payment_status=='completed')<span class="badge green">Paid</span>
            @elseif($order->payment_status=='pending')<span class="badge amber">Pending</span>
            @else<span class="badge red">Rejected</span>@endif
          </td>
          <td>
            @if($order->order_status=='pending')
              <form id="osf-{{ $order->id }}" action="{{ route('admin.room_management.featured_room.update_order_status', $order->id) }}" method="POST">
                @csrf
                <select name="order_status" class="fc" style="width:110px;padding:4px 8px;font-size:11px" onchange="document.getElementById('osf-{{ $order->id }}').submit()">
                  <option value="pending" selected>Pending</option>
                  <option value="apporved">Approved</option>
                  <option value="rejected">Rejected</option>
                </select>
              </form>
            @elseif($order->order_status=='apporved')
              <span class="badge green">Approved</span>
            @else
              <span class="badge red">Rejected</span>
            @endif
          </td>
          <td>
            <div style="display:flex;gap:5px">
              @if($order->attachment)
              <button onclick="document.getElementById('receipt-{{ $order->id }}').style.display='flex'" class="btn btn-secondary btn-xs" title="Receipt"><i class="ti ti-receipt"></i></button>
              @endif
              <button onclick="document.getElementById('detail-{{ $order->id }}').style.display='flex'" class="btn btn-secondary btn-xs" title="Details"><i class="ti ti-eye"></i></button>
              @if($order->invoice)
              <a href="{{ asset('assets/file/invoices/room-feature/'.$order->invoice) }}" target="_blank" class="btn btn-info btn-xs" title="Invoice"><i class="ti ti-file-invoice"></i></a>
              @endif
              <form action="{{ route('admin.room_management.featured_room.delete', $order->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this request?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
            </div>
          </td>
        </tr>

        {{-- Receipt Modal --}}
        @if($order->attachment)
        <div id="receipt-{{ $order->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:999;align-items:center;justify-content:center">
          <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px">
            <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
              <span class="fw6">Payment Receipt</span>
              <button onclick="document.getElementById('receipt-{{ $order->id }}').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
            </div>
            <div style="padding:16px;text-align:center">
              <img src="{{ asset('assets/admin/img/room-feature/'.$order->attachment) }}" style="max-width:100%;border-radius:8px">
            </div>
          </div>
        </div>
        @endif

        {{-- Details Modal --}}
        <div id="detail-{{ $order->id }}" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:999;align-items:center;justify-content:center">
          <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:500px">
            <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
              <span class="fw6">Feature Request Details</span>
              <button onclick="document.getElementById('detail-{{ $order->id }}').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
            </div>
            <div style="padding:16px">
              <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px">Member details</div>
              <div class="stat-row"><span class="stat-lbl">Name</span><span class="stat-val">{{ $order->name ?? 'Admin' }}</span></div>
              <div class="stat-row"><span class="stat-lbl">Email</span><span class="stat-val">{{ $order->email ?? '—' }}</span></div>
              <div class="stat-row"><span class="stat-lbl">Phone</span><span class="stat-val">{{ $order->phone ?? '—' }}</span></div>
              <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin:14px 0 10px">Payment details</div>
              <div class="stat-row"><span class="stat-lbl">Feature price</span><span class="stat-val fw6 text-green">{{ $order->currency_symbol }}{{ number_format($order->total_amount ?? 0) }}</span></div>
              <div class="stat-row"><span class="stat-lbl">Method</span><span class="stat-val">{{ ucfirst($order->payment_method ?? '—') }}</span></div>
              <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin:14px 0 10px">Feature details</div>
              <div class="stat-row"><span class="stat-lbl">Room title</span><span class="stat-val">{{ optional($rc)->title ?? '—' }}</span></div>
              <div class="stat-row"><span class="stat-lbl">Total days</span><span class="stat-val">{{ $order->days }}</span></div>
              <div class="stat-row"><span class="stat-lbl">Start date</span><span class="stat-val">{{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}</span></div>
              <div class="stat-row"><span class="stat-lbl">Expire date</span><span class="stat-val">{{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</span></div>
            </div>
            <div style="padding:0 16px 16px">
              <button onclick="document.getElementById('detail-{{ $order->id }}').style.display='none'" class="btn btn-secondary btn-block">Close</button>
            </div>
          </div>
        </div>

        @empty
        <tr><td colspan="10" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-star" style="font-size:32px;display:block;margin-bottom:10px"></i>No featured room requests found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">
    {{ $orders->appends(['order_no'=>request('order_no'),'title'=>request('title'),'payment_status'=>request('payment_status'),'order_status'=>request('order_status'),'language'=>$defaultLang->code])->links() }}
    <span class="pg-info">{{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
  </div>
</div>
@endsection
