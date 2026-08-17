@extends('admin.layout')
@section('section','Property / Hotels')
@section('page','Amenities')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Amenities</h2><p>{{ count($amenities) }} amenities</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.hotel_management.amenitie.bulk_delete') }}"><i class="ti ti-trash"></i> Delete</button>
    <button onclick="document.getElementById('createAmModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> Add Amenity</button>
  </div>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>Icon</th><th>Name</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($amenities as $am)
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $am->id }}"></td>
        <td><i class="{{ $am->icon }}" style="font-size:20px;color:var(--muted)"></i></td>
        <td class="td-main">{{ $am->title ?? '—' }}</td>
        <td>
          <div style="display:flex;gap:6px">
            <button class="btn btn-secondary btn-xs" onclick="openAmEdit(this)"
              data-id="{{ $am->id }}" data-icon="{{ $am->icon }}"
              data-title="{{ $am->title }}" data-language_id="{{ $am->language_id }}">
              <i class="ti ti-edit"></i>
            </button>
            <form action="{{ route('admin.hotel_management.amenitie.delete', $am->id) }}" method="POST"
                  style="display:inline" onsubmit="return confirm('Delete?')">
              @csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted)">No amenities found</td></tr>
      @endforelse
    </tbody>
  </table></div>
</div>

{{-- Create Modal --}}
<div id="createAmModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Add Amenity</span>
      <button onclick="document.getElementById('createAmModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.amenitie.store', ['language' => request()->input('language')]) }}" method="POST">
      @csrf
      <div style="padding:16px">
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Language</label>
          <select name="language_id" class="fc">
            @foreach($langs as $lang)
            <option value="{{ $lang->id }}" {{ $lang->is_default ? 'selected' : '' }}>{{ $lang->name }}</option>
            @endforeach
          </select>
          <div class="fc-hint">Select which language this amenity belongs to</div>
        </div>
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Icon Class *</label>
          <input type="text" name="icon" id="new_am_icon" class="fc" placeholder="e.g. fas fa-wifi" required>
          <div class="fc-hint">Enter a Font Awesome class — e.g. <code>fas fa-wifi</code>, <code>fas fa-swimming-pool</code></div>
        </div>
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Title *</label>
          <input type="text" name="title" class="fc" placeholder="Enter Amenity Title" required>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Save</button>
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('createAmModal').style.display='none'">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Edit Modal --}}
<div id="editAmModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Amenity</span>
      <button onclick="document.getElementById('editAmModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.amenitie.update') }}" method="POST">
      @csrf
      <div style="padding:16px">
        <input type="hidden" name="id" id="am_edit_id">
        <input type="hidden" name="language_id" id="am_edit_lang">
        <div class="fg" style="margin-bottom:12px">
          <label class="flabel">Icon Class *</label>
          <input type="text" name="icon" id="am_edit_icon" class="fc" required>
          <div id="am_icon_preview" style="margin-top:8px;font-size:22px;color:var(--muted)"></div>
        </div>
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Title *</label>
          <input type="text" name="title" id="am_edit_title" class="fc" required>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Update</button>
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('editAmModal').style.display='none'">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('script')<script>
function openAmEdit(b){
  document.getElementById('editAmModal').style.display='flex';
  document.getElementById('am_edit_id').value=b.dataset.id;
  document.getElementById('am_edit_lang').value=b.dataset.language_id;
  document.getElementById('am_edit_icon').value=b.dataset.icon;
  document.getElementById('am_edit_title').value=b.dataset.title;
  document.getElementById('am_icon_preview').innerHTML='<i class="'+b.dataset.icon+'"></i>';
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