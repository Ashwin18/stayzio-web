@extends('admin.layout')
@section('section','Notifications')
@section('page','All Notifications')

@section('content')
@php
  $typeLabels = [
    'vendor_registered' => ['label'=>'Vendor Registered',  'color'=>'blue',   'icon'=>'ti-building-store'],
    'hotel_submitted'   => ['label'=>'Hotel Submitted',     'color'=>'amber',  'icon'=>'ti-building-plus'],
    'booking_confirmed' => ['label'=>'Booking Confirmed',   'color'=>'green',  'icon'=>'ti-calendar-check'],
    'booking_cancelled' => ['label'=>'Booking Cancelled',   'color'=>'red',    'icon'=>'ti-calendar-x'],
    'feature_request'   => ['label'=>'Feature Request',     'color'=>'purple', 'icon'=>'ti-crown'],
    'support_ticket'    => ['label'=>'Support Ticket',      'color'=>'blue',   'icon'=>'ti-help-circle'],
    'payment_failed'    => ['label'=>'Payment Failed',      'color'=>'red',    'icon'=>'ti-alert-triangle'],
    'low_inventory'     => ['label'=>'Low Inventory',       'color'=>'amber',  'icon'=>'ti-alert-circle'],
  ];
  $colors = [
    'blue'   => ['bg'=>'#eff6ff','border'=>'#bfdbfe','text'=>'#1d4ed8'],
    'green'  => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','text'=>'#15803d'],
    'red'    => ['bg'=>'#fff1f1','border'=>'#fecaca','text'=>'#dc2626'],
    'amber'  => ['bg'=>'#fffbeb','border'=>'#fde68a','text'=>'#d97706'],
    'purple' => ['bg'=>'#faf5ff','border'=>'#e9d5ff','text'=>'#7c3aed'],
  ];
@endphp

<style>
.notif-filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
.notif-filter{padding:6px 14px;border-radius:20px;border:1.5px solid var(--border);background:var(--navy3);font-size:12px;font-weight:700;color:var(--muted);text-decoration:none;transition:.15s}
.notif-filter.active,.notif-filter:hover{border-color:var(--red);color:var(--red);background:rgba(227,30,36,.06)}
.notif-row{display:flex;align-items:flex-start;gap:14px;padding:14px 18px;border-bottom:1px solid var(--border);transition:.15s;text-decoration:none}
.notif-row:hover{background:var(--navy3)}
.notif-row.unread{background:rgba(227,30,36,.03)}
.notif-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.notif-body{flex:1;min-width:0}
.notif-title{font-size:13px;font-weight:700;color:var(--text);margin-bottom:3px;display:flex;align-items:center;gap:8px}
.notif-msg{font-size:12px;color:var(--muted);line-height:1.5;margin-bottom:4px}
.notif-time{font-size:11px;color:var(--muted)}
.notif-unread-dot{width:7px;height:7px;background:var(--red);border-radius:50%;flex-shrink:0;margin-top:4px}
.notif-actions{display:flex;gap:6px;flex-shrink:0}
.notif-empty{text-align:center;padding:60px 20px;color:var(--muted)}
.notif-empty i{font-size:40px;display:block;margin-bottom:12px;opacity:.4}
</style>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Notifications</h2>
    <p>All admin alerts and action items</p>
  </div>
  <div class="page-hdr-actions">
    <form method="POST" action="{{ route('admin.notifications.clear_read') }}" style="display:inline">
      @csrf
      <button type="submit" class="btn btn-secondary btn-sm">
        <i class="ti ti-trash"></i> Clear Read
      </button>
    </form>
  </div>
</div>

{{-- Type filters --}}
<div class="notif-filters">
  <a href="{{ route('admin.notifications.index') }}" class="notif-filter {{ !$type ? 'active' : '' }}">All</a>
  @foreach($typeLabels as $key => $tl)
  <a href="{{ route('admin.notifications.index', ['type' => $key]) }}"
     class="notif-filter {{ $type == $key ? 'active' : '' }}">
    <i class="ti {{ $tl['icon'] }}"></i> {{ $tl['label'] }}
  </a>
  @endforeach
</div>

<div class="card">
  @if($notifications->count() === 0)
    <div class="notif-empty">
      <i class="ti ti-bell-off"></i>
      No notifications found
    </div>
  @else
    @foreach($notifications as $n)
    @php
      $tl = $typeLabels[$n->type] ?? ['label'=>ucfirst($n->type),'color'=>'blue','icon'=>'ti-bell'];
      $c  = $colors[$tl['color']] ?? $colors['blue'];
    @endphp
    <div class="notif-row {{ !$n->is_read ? 'unread' : '' }}">
      {{-- Icon --}}
      <div class="notif-icon" style="background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }}">
        <i class="ti {{ $tl['icon'] }}" style="color:{{ $c['text'] }}"></i>
      </div>

      {{-- Body --}}
      <div class="notif-body">
        <div class="notif-title">
          {{ $n->title }}
          @if(!$n->is_read)
            <span style="font-size:10px;background:var(--red);color:#fff;padding:2px 7px;border-radius:20px;font-weight:800">NEW</span>
          @endif
          <span style="font-size:10px;background:{{ $c['bg'] }};color:{{ $c['text'] }};border:1px solid {{ $c['border'] }};padding:2px 7px;border-radius:20px;font-weight:700">
            {{ $tl['label'] }}
          </span>
        </div>
        <div class="notif-msg">{{ $n->message }}</div>
        <div class="notif-time"><i class="ti ti-clock" style="font-size:10px"></i> {{ $n->created_at->diffForHumans() }}</div>
      </div>

      {{-- Actions --}}
      <div class="notif-actions">
        @if($n->action_url)
        <a href="{{ route('admin.notifications.read', $n->id) }}"
           class="btn btn-primary btn-xs" style="font-size:11px;padding:5px 10px">
          <i class="ti ti-arrow-right"></i> Take Action
        </a>
        @endif
        <form method="POST" action="{{ route('admin.notifications.destroy', $n->id) }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-secondary btn-xs" style="font-size:11px;padding:5px 10px">
            <i class="ti ti-x"></i>
          </button>
        </form>
      </div>
    </div>
    @endforeach

    {{-- Pagination --}}
    <div style="padding:14px 18px">
      {{ $notifications->appends(['type' => $type])->links() }}
    </div>
  @endif
</div>
@endsection
