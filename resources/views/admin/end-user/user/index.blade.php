@extends('admin.layout')
@section('section','People')
@section('page','Customers')
@section('content')

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Customers</h2>
    <p>{{ $users->total() }} registered users on the platform</p>
  </div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.user_management.push_notification.notification_for_visitors',['language'=>$defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-bell"></i> Push Notification</a>
    <a href="{{ route('admin.user_management.mail_for_subscribers',['language'=>$defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-mail"></i> Mail Subscribers</a>
    <a href="{{ route('admin.user_management.registered_user.create',['language'=>$defaultLang->code]) }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Customer</a>
  </div>
</div>

<div class="card">
  <form action="{{ route('admin.user_management.registered_users') }}" method="GET">
    <div class="filters">
      <input name="info" class="fc" placeholder="🔍  Search name or email…" value="{{ request('info') }}" style="width:250px">
      <select name="status" class="fc" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="1" {{ request('status')=='1'?'selected':'' }}>Active</option>
        <option value="0" {{ request('status')=='0'?'selected':'' }}>Inactive</option>
      </select>
      <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search"></i></button>
      <div class="filter-spacer"></div>
      <a href="{{ route('admin.user_management.registered_users') }}" class="btn btn-secondary btn-sm"><i class="ti ti-x"></i></a>
    </div>
  </form>
  <div class="tbl-wrap">
    <table>
      <thead><tr>
        <th><input type="checkbox"></th><th>#</th><th>Customer</th><th>Phone</th><th>City</th><th>Joined</th><th>Status</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($users as $u)
        <tr>
          <td><input type="checkbox" class="rc" value="{{ $u->id }}"></td>
          <td class="td-muted">#U-{{ str_pad($u->id,4,'0',STR_PAD_LEFT) }}</td>
          <td>
            <div class="td-pair">
              <div class="av muted" style="font-size:13px">{{ strtoupper(substr($u->name,0,2)) }}</div>
              <div>
                <div class="td-main">{{ $u->name }}</div>
                <div class="td-sub">{{ $u->email }}</div>
              </div>
            </div>
          </td>
          <td class="td-muted">{{ $u->phone??'—' }}</td>
          <td class="td-muted">{{ $u->city??'—' }}</td>
          <td class="td-muted">{{ $u->created_at->format('d M Y') }}</td>
          <td>
            <form action="{{ route('admin.user_management.user.update_account_status',['id'=>$u->id]) }}" method="POST" style="display:inline">
              @csrf
              <input type="hidden" name="account_status" value="{{ $u->status?0:1 }}">
              <button type="submit" class="btn {{ $u->status?'btn-success':'btn-warn' }} btn-xs" onclick="return confirm('Change account status?')">
                {{ $u->status?'Active':'Inactive' }}
              </button>
            </form>
          </td>
          <td>
            <div style="display:flex;gap:4px">
              <a href="{{ route('admin.user_management.registered_user.view',['id'=>$u->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="View"><i class="ti ti-eye"></i></a>
              <a href="{{ route('admin.user_management.registered_user.edit',['id'=>$u->id,'language'=>$defaultLang->code]) }}" class="btn btn-secondary btn-xs" title="Edit"><i class="ti ti-edit"></i></a>
              <form action="{{ route('admin.user_management.user.delete',$u->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete customer?')">@csrf
                <button class="btn btn-danger btn-xs" title="Delete"><i class="ti ti-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:48px;color:var(--sz-muted)">
          <i class="ti ti-user-off" style="font-size:36px;display:block;margin-bottom:10px"></i>No customers found
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination">{{ $users->appends(request()->query())->links() }}<span class="pg-info">{{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</span></div>
</div>
@endsection
