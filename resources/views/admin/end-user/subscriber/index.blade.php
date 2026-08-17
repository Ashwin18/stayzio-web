@extends('admin.layout')
@section('section','People / Customers')
@section('page','Subscribers')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Email Subscribers</h2><p>{{ $subscribers->total() }} subscribers</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.user_management.bulk_delete_subscriber') }}"><i class="ti ti-trash"></i> Bulk Delete</button>
    <a href="{{ route('admin.user_management.mail_for_subscribers') }}" class="btn btn-primary"><i class="ti ti-mail"></i> Send Email</a>
  </div>
</div>
<div class="card">
  <form action="{{ route('admin.user_management.subscribers') }}" method="GET">
    <div class="filters">
      <input name="email" class="fc" placeholder="🔍  Search email…" value="{{ request('email') }}" style="width:250px">
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
    </div>
  </form>
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>#</th><th>Email</th><th>Subscribed</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($subscribers as $sub)
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $sub->id }}"></td>
        <td class="td-muted">{{ $loop->iteration }}</td>
        <td class="td-main">{{ $sub->email_id }}</td>
        <td class="td-muted">{{ \Carbon\Carbon::parse($sub->created_at)->format('d M Y') }}</td>
        <td><form action="{{ route('admin.user_management.subscriber.delete',['id'=>$sub->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form></td>
      </tr>
      @empty<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No subscribers found</td></tr>@endforelse
    </tbody>
  </table></div>
  <div class="pagination">{{ $subscribers->appends(['email'=>request('email')])->links() }}<span class="pg-info">{{ $subscribers->firstItem() }}–{{ $subscribers->lastItem() }} of {{ $subscribers->total() }}</span></div>
</div>
@endsection
