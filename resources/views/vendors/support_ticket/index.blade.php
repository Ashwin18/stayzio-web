@extends('vendors.layout')
@section('section','Support')
@section('page','Tickets')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Support Tickets</h2></div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.support_ticket.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> New Ticket</a>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr><th>#</th><th>Subject</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($tickets??[] as $t)
      <tr>
        <td class="td-muted">#{{ str_pad($t->id,3,'0',STR_PAD_LEFT) }}</td>
        <td class="td-main">{{ $t->subject }}</td>
        <td>
          @if($t->status==2)<span class="badge red">Open</span>
          @elseif($t->status==3)<span class="badge muted">Closed</span>
          @else<span class="badge amber">Pending</span>@endif
        </td>
        <td class="td-muted">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('vendor.support_tickets.message',$t->id) }}" class="btn btn-secondary btn-xs"><i class="ti ti-eye"></i></a>
            <form action="{{ route('vendor.support_tickets.delete',$t->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-message-circle" style="font-size:32px;display:block;margin-bottom:10px"></i>No tickets yet</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>
@endsection
