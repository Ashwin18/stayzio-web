@extends('vendors.layout')
@section('section','Support')
@section('page','Ticket #{{ $ticket->id }}')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Ticket #{{ str_pad($ticket->id,3,'0',STR_PAD_LEFT) }}</h2><p>{{ $ticket->subject }}</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.support_tickets') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:14px">
  <div class="card">
    <div class="card-hdr"><div class="card-title">Conversation</div></div>
    <div class="card-body" style="max-height:400px;overflow-y:auto">
      @foreach($ticket->messages as $msg)
      <div style="background:{{ $msg->type==3 ? '#eff6ff' : ($msg->type==2 ? '#fff0f0' : 'var(--navy3)') }};border-radius:10px;padding:12px 14px;margin-bottom:10px;border:1px solid {{ $msg->type==3 ? '#dbeafe' : ($msg->type==2 ? '#fef2f2' : 'var(--border)') }}">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
          <span style="font-size:12px;font-weight:600">{{ $msg->type==2 ? 'Admin' : ($msg->type==3 ? 'You' : 'Customer') }}</span>
          @if($msg->type==2)<span class="badge red no-dot" style="font-size:9px;padding:1px 5px">Admin</span>@endif
          <span style="font-size:11px;color:var(--muted);margin-left:auto">{{ $msg->created_at->diffForHumans() }}</span>
        </div>
        <div style="font-size:13px;line-height:1.6">{!! $msg->reply !!}</div>
        @if($msg->file)
        <a href="{{ asset('assets/admin/img/support-ticket/'.$msg->file) }}" target="_blank" class="btn btn-secondary btn-xs" style="margin-top:8px"><i class="ti ti-paperclip"></i> Attachment</a>
        @endif
      </div>
      @endforeach
    </div>
    @if($ticket->status != 3)
    <div style="border-top:1px solid var(--border);padding:14px 16px">
      <form action="{{ route('vendor.support_ticket.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="fg" style="margin-bottom:10px">
          <textarea name="reply" class="fc" rows="3" placeholder="Type your reply…" required></textarea>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
          <input type="file" name="file" class="fc" accept=".zip" style="flex:1">
          <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i> Reply</button>
        </div>
      </form>
    </div>
    @endif
  </div>
  <div class="card">
    <div class="card-hdr"><div class="card-title">Ticket Info</div></div>
    <div class="card-body">
      <div class="stat-row"><span class="stat-lbl">Ticket ID</span><span class="stat-val">#{{ str_pad($ticket->id,3,'0',STR_PAD_LEFT) }}</span></div>
      <div class="stat-row"><span class="stat-lbl">Subject</span><span class="stat-val">{{ $ticket->subject }}</span></div>
      <div class="stat-row"><span class="stat-lbl">Created</span><span class="stat-val">{{ $ticket->created_at->format('d M Y') }}</span></div>
      <div class="stat-row"><span class="stat-lbl">Status</span><span class="stat-val">
        @if($ticket->status==2)<span class="badge red">Open</span>
        @elseif($ticket->status==3)<span class="badge muted">Closed</span>
        @else<span class="badge amber">Pending</span>@endif
      </span></div>
    </div>
  </div>
</div>
@endsection
