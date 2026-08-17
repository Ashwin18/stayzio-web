@extends('admin.layout')
@section('section','Settings')
@section('page','Languages')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Languages</h2><p>Manage platform languages</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.settings.language_management') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add Language</a>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr><th>#</th><th>Language</th><th>Code</th><th>Direction</th><th>Default</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($languages as $lang)
      <tr>
        <td class="td-muted">{{ $loop->iteration }}</td>
        <td class="td-main">{{ $lang->name }}</td>
        <td><span class="badge muted no-dot">{{ $lang->code }}</span></td>
        <td>@if($lang->direction==1)<span class="badge amber no-dot">RTL</span>@else<span class="badge blue no-dot">LTR</span>@endif</td>
        <td>
          @if($lang->is_default==1)<span class="badge green">Default</span>
          @else
          <form action="{{ route('admin.settings.language_management.make_default_language',['id'=>$lang->id]) }}" method="POST" style="display:inline">@csrf<button type="submit" class="btn btn-secondary btn-xs">Set Default</button></form>
          @endif
        </td>
        <td>
          <div style="display:flex;gap:5px">
            <a href="{{ route('admin.settings.language_management.edit_admin_keyword',$lang->id) }}" class="btn btn-info btn-xs" title="Admin Keywords"><i class="ti ti-key"></i></a>
            <a href="{{ route('admin.settings.language_management.edit_front_keyword',$lang->id) }}" class="btn btn-info btn-xs" title="Frontend Keywords"><i class="ti ti-world"></i></a>
            <button class="btn btn-secondary btn-xs" onclick="openLangEdit(this)"
              data-id="{{ $lang->id }}" data-name="{{ $lang->name }}" data-code="{{ $lang->code }}" data-direction="{{ $lang->direction }}"><i class="ti ti-edit"></i></button>
            @if($lang->is_default!=1)
            <form action="{{ route('admin.settings.language_management.delete', $lang->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete language?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
            @endif
          </div>
        </td>
      </tr>
      @empty<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No languages found</td></tr>@endforelse
    </tbody>
  </table></div>
</div>
{{-- Edit Modal --}}
<div id="editLangModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:460px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Language</span><button onclick="document.getElementById('editLangModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.language.edit')</div>
  </div>
</div>
@endsection
@section('script')<script>
function openLangEdit(b){var m=document.getElementById('editLangModal');m.style.display='flex';var f=function(n,v){var e=m.querySelector('[name="'+n+'"]');if(e)e.value=v;};f('id',b.dataset.id);f('name',b.dataset.name);f('code',b.dataset.code);f('direction',b.dataset.direction);}
</script>@endsection
