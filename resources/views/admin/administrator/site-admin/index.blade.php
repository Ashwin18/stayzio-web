@extends('admin.layout')
@section('section','Staff & Roles')
@section('page','Registered Admins')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Staff & Roles</h2><p>Manage admin accounts and permissions</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.admin_management.role_permissions') }}" class="btn btn-secondary"><i class="ti ti-shield"></i> Manage Roles</a>
    <a href="{{ route('admin.admin_management.registered_admins') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Admin</a>
  </div>
</div>
<div class="two-col-eq">
  <div class="card">
    <div class="card-hdr"><div class="card-title">Registered Admins</div></div>
    <div class="tbl-wrap"><table>
      <thead><tr><th>#</th><th>Admin</th><th>Role</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($admins as $a)
        <tr>
          <td class="td-muted">{{ $a->id }}</td>
          <td><div class="td-pair"><div class="av {{ $loop->first?'red':'muted' }}">{{ strtoupper(substr($a->first_name??'A',0,1)) }}</div><div><div class="td-main">{{ $a->first_name }} {{ $a->last_name }}</div><div class="td-sub">{{ $a->email }}</div></div></div></td>
          <td>@if(is_null($a->role_id))<span class="badge red no-dot">Super Admin</span>@else<span class="badge blue no-dot">{{ optional($a->role)->name??'Staff' }}</span>@endif</td>
          <td><div style="display:flex;gap:5px">
            <a href="{{ route('admin.admin_management.edit_admin',$a->id) }}" class="btn btn-secondary btn-xs"><i class="ti ti-edit"></i></a>
            @if(!$loop->first)<form action="{{ route('admin.admin_management.delete_admin',$a->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>@endif
          </div></td>
        </tr>
        @empty<tr><td colspan="4" style="text-align:center;padding:24px;color:var(--muted)">No admins found</td></tr>@endforelse
      </tbody>
    </table></div>
  </div>
  <div class="card">
    <div class="card-hdr"><div class="card-title">Roles</div><a href="{{ route('admin.admin_management.role_permissions') }}" class="btn btn-secondary btn-sm"><i class="ti ti-plus"></i> New Role</a></div>
    <div class="tbl-wrap"><table>
      <thead><tr><th>#</th><th>Role Name</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($roles??[] as $role)
        <tr>
          <td class="td-muted">{{ $loop->iteration }}</td>
          <td class="td-main">{{ $role->name }}</td>
          <td><div style="display:flex;gap:5px">
            <a href="{{ route('admin.admin_management.role.permissions',$role->id) }}" class="btn btn-info btn-xs"><i class="ti ti-shield"></i></a>
            <button class="btn btn-secondary btn-xs" onclick="openRoleEdit(this)" data-id="{{ $role->id }}" data-name="{{ $role->name }}"><i class="ti ti-edit"></i></button>
            <form action="{{ route('admin.admin_management.delete_role',['id'=>$role->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete role?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div></td>
        </tr>
        @empty<tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">No roles yet</td></tr>@endforelse
      </tbody>
    </table></div>
  </div>
</div>
{{-- Create Role Modal --}}
<div id="createRoleModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:400px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Create Role</span><button onclick="document.getElementById('createRoleModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.administrator.role-permission.create')</div>
  </div>
</div>
{{-- Edit Role Modal --}}
<div id="editRoleModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:400px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Role</span><button onclick="document.getElementById('editRoleModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.administrator.role-permission.edit')</div>
  </div>
</div>
@endsection
@section('script')<script>
function openRoleEdit(b){var m=document.getElementById('editRoleModal');m.style.display='flex';var e=m.querySelector('[name="id"]');if(e)e.value=b.dataset.id;var n=m.querySelector('[name="name"]');if(n)n.value=b.dataset.name;}
</script>@endsection
