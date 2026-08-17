@extends('admin.layout')
@section('section','Support')
@section('page','Ticket #{{ $ticket->id }}')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Ticket #{{ str_pad($ticket->id,3,'0',STR_PAD_LEFT) }}</h2><p>{{ $ticket->subject }}</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.support_tickets') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
    @if($ticket->status != 3)
    <form action="{{ route('admin.support_ticket.close', $ticket->id) }}" method="POST" style="display:inline">@csrf
      <button type="submit" class="btn btn-warn"><i class="ti ti-lock"></i> Close Ticket</button>
    </form>
    @endif
  </div>
</div>

<div class="two-col">
  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card">
      <div class="card-hdr"><div class="card-title">Conversation</div></div>
      <div class="card-body" style="max-height:420px;overflow-y:auto">
        @foreach($ticket->messages as $msg)
        <div class="msg-bubble {{ $msg->type == 2 ? 'admin-reply' : '' }}" style="margin-bottom:10px">
          <div class="msg-meta">
            @php
              if($msg->type == 2) {
                $sender = App\Models\Admin::find($msg->user_id);
                $senderName = optional($sender)->username ?? 'Admin';
              } elseif($msg->type == 3) {
                $sender = App\Models\Vendor::find($msg->user_id);
                $senderName = optional($sender)->username ?? 'Vendor';
              } else {
                $sender = App\Models\User::find($msg->user_id);
                $senderName = optional($sender)->name ?? 'Customer';
              }
            @endphp
            <div class="av {{ $msg->type == 2 ? 'red' : 'muted' }}" style="width:26px;height:26px;font-size:11px">
              {{ strtoupper(substr($senderName, 0, 1)) }}
            </div>
            <span class="msg-author">{{ $senderName }}</span>
            <span class="msg-time">{{ $msg->created_at->diffForHumans() }}</span>
            @if($msg->type == 2)<span class="badge red no-dot" style="font-size:9px">Admin</span>@endif
            @if($msg->type == 3)<span class="badge blue no-dot" style="font-size:9px">Vendor</span>@endif
          </div>
          <div class="msg-text">{!! $msg->reply !!}</div>
          @if($msg->file)
          <a href="{{ asset('assets/admin/img/support-ticket/'.$msg->file) }}" target="_blank"
             class="btn btn-secondary btn-xs" style="margin-top:8px"><i class="ti ti-paperclip"></i> Attachment</a>
          @endif
        </div>
        @endforeach
      </div>

      @if($ticket->status != 3)
      <div style="border-top:1px solid var(--border);padding:14px">
        <form action="{{ route('admin.support_ticket.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="fg" style="margin-bottom:10px">
            <label class="flabel">Your reply</label>
            <textarea name="reply" class="fc" rows="4" placeholder="Type your reply…" required></textarea>
            @error('reply')<span class="fs11 text-red">{{ $message }}</span>@enderror
          </div>
          <div class="fg" style="margin-bottom:12px">
            <label class="flabel">Attachment (optional — zip only)</label>
            <input type="file" name="file" class="fc" accept=".zip">
          </div>
          <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-send"></i> Send Reply</button>
        </form>
      </div>
      @endif
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:14px">
    <div class="card">
      <div class="card-hdr"><div class="card-title">Ticket Info</div></div>
      <div class="card-body">
        <div class="stat-row"><span class="stat-lbl">Ticket ID</span><span class="stat-val">#{{ str_pad($ticket->id,3,'0',STR_PAD_LEFT) }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Subject</span><span class="stat-val">{{ $ticket->subject }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Created</span><span class="stat-val">{{ $ticket->created_at->format('d M Y') }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Status</span><span class="stat-val">
          @if($ticket->status==1)<span class="badge amber">Pending</span>
          @elseif($ticket->status==2)<span class="badge red">Open</span>
          @else<span class="badge green">Closed</span>@endif
        </span></div>
        <div class="stat-row"><span class="stat-lbl">Name</span><span class="stat-val">{{ $ticket->name }}</span></div>
        <div class="stat-row"><span class="stat-lbl">Email</span><span class="stat-val">{{ $ticket->email }}</span></div>
      </div>
    </div>
  </div>
</div>
@endsection
