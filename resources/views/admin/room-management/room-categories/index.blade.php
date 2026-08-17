@extends('admin.layout')
@section('section','Property / Rooms')
@section('page','Room Categories')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Room Categories</h2><p>{{ count($categories) }} categories</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.room_management.categories.bulk_delete') }}"><i class="ti ti-trash"></i> Delete</button>
    <button onclick="document.getElementById('createRCModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> Add Category</button>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>Name</th><th>Serial</th><th>Status</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($categories as $cat)
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $cat->id }}"></td>
        <td class="td-main">{{ Str::limit($cat->name,60) }}</td>
        <td class="td-muted">{{ $cat->serial_number }}</td>
        <td>@if($cat->status==1)<span class="badge green">Active</span>@else<span class="badge red">Inactive</span>@endif</td>
        <td>
          <div style="display:flex;gap:6px">
            <button class="btn btn-secondary btn-xs" onclick="openRCEdit(this)"
              data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" data-status="{{ $cat->status }}"
              data-language_id="{{ $cat->language_id }}" data-serial_number="{{ $cat->serial_number }}">
              <i class="ti ti-edit"></i>
            </button>
            <form action="{{ route('admin.room_management.categories.delete', $cat->id) }}" method="POST"
                  style="display:inline" onsubmit="return confirm('Delete?')">
              @csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No room categories found</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>
{{-- Create Modal --}}
<div id="createRCModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:460px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Add Room Category</span>
      <button onclick="document.getElementById('createRCModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.room_management.categories.store') }}" method="POST">
      @csrf
      <div style="padding:16px">
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Language</label>
          <select name="language_id" class="fc">
            @foreach($langs as $lang)<option value="{{ $lang->id }}">{{ $lang->name }}</option>@endforeach
          </select>
        </div>
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Name *</label>
          <input type="text" name="name" class="fc" placeholder="Enter Category Name" required>
        </div>
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Status *</label>
          <select name="status" class="fc" required>
            <option value="">Select Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Serial Number *</label>
          <input type="number" name="serial_number" class="fc" placeholder="Enter Serial Number" required>
          <div class="fc-hint">Higher serial number = shown later in list</div>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Save</button>
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('createRCModal').style.display='none'">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
{{-- Edit Modal --}}
<div id="editRCModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:460px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Room Category</span>
      <button onclick="document.getElementById('editRCModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.room_management.categories.update') }}" method="POST">
      @csrf
      <div style="padding:16px">
        <input type="hidden" name="id" id="rc_edit_id">
        <input type="hidden" name="language_id" id="rc_edit_lang">
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Name *</label>
          <input type="text" name="name" id="rc_edit_name" class="fc" required>
        </div>
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Status *</label>
          <select name="status" id="rc_edit_status" class="fc" required>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Serial Number *</label>
          <input type="number" name="serial_number" id="rc_edit_serial" class="fc" required>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Update</button>
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('editRCModal').style.display='none'">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('script')<script>
function openRCEdit(b){
  document.getElementById('editRCModal').style.display='flex';
  document.getElementById('rc_edit_id').value=b.dataset.id;
  document.getElementById('rc_edit_lang').value=b.dataset.language_id;
  document.getElementById('rc_edit_name').value=b.dataset.name;
  document.getElementById('rc_edit_status').value=b.dataset.status;
  document.getElementById('rc_edit_serial').value=b.dataset.serial_number;
}
</script><script>
(function() {
  document.addEventListener("submit", function(e) {
    var form = e.target;
    if (!form.closest || !form.closest("[id$=Modal]")) return;
    e.preventDefault();
    var btn = form.querySelector("button[type=submit]");
    var origHtml = btn ? btn.innerHTML : "";
    if (btn) { btn.disabled = true; btn.innerHTML = "Saving..."; }
    var errDiv = form.closest("[id$=Modal]").querySelector(".modal-errors");
    if (errDiv) errDiv.style.display = "none";
    fetch(form.action, {
      method: "POST",
      body: new FormData(form),
      headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.status === "success") {
        window.location.reload();
      } else {
        var errors = data.errors || {};
        var html = Object.keys(errors).map(function(k) {
          var msg = Array.isArray(errors[k]) ? errors[k][0] : errors[k];
          return "<p style=\"margin:3px 0;color:#f87171;font-size:12px\">" + msg + "</p>";
        }).join("");
        if (errDiv) { errDiv.innerHTML = html; errDiv.style.display = "block"; }
        if (btn) { btn.disabled = false; btn.innerHTML = origHtml; }
      }
    })
    .catch(function() {
      if (btn) { btn.disabled = false; btn.innerHTML = origHtml; }
    });
  }, true);
})();
</script>
@endsection