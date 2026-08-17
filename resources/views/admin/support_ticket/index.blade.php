@extends('admin.layout')
@section('section','Support')
@section('page','Support Tickets')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Support Tickets</h2><p>{{ $collection->total() }} total tickets</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.support_ticket.setting') }}" class="btn btn-secondary"><i class="ti ti-settings"></i> Settings</a>
  </div>
</div>
<div style="display:flex;gap:8px;margin-bottom:14px">
  <a href="{{ route('admin.support_tickets') }}" class="ftag {{ !request('status') ? 'active' : '' }}"><i class="ti ti-list"></i> All</a>
  <a href="{{ route('admin.support_tickets', ['status' => 1]) }}" class="ftag {{ request('status')==1 ? 'active' : '' }}" style="color:var(--amber)">Pending</a>
  <a href="{{ route('admin.support_tickets', ['status' => 2]) }}" class="ftag {{ request('status')==2 ? 'active' : '' }}" style="color:var(--red2)">Open</a>
  <a href="{{ route('admin.support_tickets', ['status' => 3]) }}" class="ftag {{ request('status')==3 ? 'active' : '' }}" style="color:var(--green)">Closed</a>
</div>
<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Subject</th><th>From</th><th>Type</th><th>Created</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($collection as $tk)
        <tr>
          <td class="td-muted">#TK-{{ str_pad($tk->id,3,'0',STR_PAD_LEFT) }}</td>
          <td>
            <div class="td-main">{{ Str::limit($tk->subject,60) }}</div>
            <div class="td-sub">{{ Str::limit(strip_tags($tk->details),80) }}</div>
          </td>
          <td><div class="td-main">{{ $tk->name }}</div><div class="td-sub">{{ $tk->email }}</div></td>
          <td><span class="badge blue no-dot">{{ ucfirst($tk->type ?? 'General') }}</span></td>
          <td class="td-muted">{{ $tk->created_at->diffForHumans() }}</td>
          <td>
            @if($tk->status==1)<span class="badge amber">Pending</span>
            @elseif($tk->status==2)<span class="badge red">Open</span>
            @else<span class="badge green">Closed</span>@endif
          </td>
          <td>
            <div style="display:flex;gap:5px">
              <a href="{{ route('admin.support_tickets.message', $tk->id) }}" class="btn btn-primary btn-xs"><i class="ti ti-message-circle"></i> Reply</a>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-message-circle" style="font-size:32px;display:block;margin-bottom:10px"></i>No tickets found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $collection->links() }}<span class="pg-info">{{ $collection->firstItem() }}–{{ $collection->lastItem() }} of {{ $collection->total() }}</span></div>
</div>
@endsection
