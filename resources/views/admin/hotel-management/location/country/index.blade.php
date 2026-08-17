@extends('admin.layout')
@section('section','Property / Locations')
@section('page','Countries')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Locations</h2><p>Countries, states and cities</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.hotel_management.location.bulk_delete_country') }}"><i class="ti ti-trash"></i> Delete</button>
    <button onclick="document.getElementById('createCModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> Add Country</button>
  </div>
</div>
<div style="display:flex;gap:8px;margin-bottom:14px">
  <a href="{{ route('admin.hotel_management.location.countries',['language'=>$defaultLang->code]) }}" class="ftag active"><i class="ti ti-globe"></i> Countries</a>
  <a href="{{ route('admin.hotel_management.location.states',['language'=>$defaultLang->code]) }}" class="ftag"><i class="ti ti-map"></i> States</a>
  <a href="{{ route('admin.hotel_management.location.city',['language'=>$defaultLang->code]) }}" class="ftag"><i class="ti ti-map-pin"></i> Cities</a>
</div>
<div class="card">
  <div class="tbl-wrap"><table>
    <thead><tr>
      <th><input type="checkbox" onchange="document.querySelectorAll('.bulk-check').forEach(c=>c.checked=this.checked)"></th>
      <th>Name</th><th>Status</th><th>Actions</th>
    </tr></thead>
    <tbody>
      @forelse($countries as $country)
      <tr>
        <td><input type="checkbox" class="bulk-check" data-val="{{ $country->id }}"></td>
        <td class="td-main">{{ $country->name }}</td>
        <td>@if($country->status==1)<span class="badge green">Active</span>@else<span class="badge red">Inactive</span>@endif</td>
        <td>
          <div style="display:flex;gap:6px">
            <button class="btn btn-secondary btn-xs" onclick="openCEdit(this)"
              data-id="{{ $country->id }}" data-name="{{ $country->name }}" data-language_id="{{ $country->language_id }}"><i class="ti ti-edit"></i></button>
            <form action="{{ route('admin.hotel_management.location.delete_country',['id'=>$country->id]) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
          </div>
        </td>
      </tr>
      @empty<tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted)">No countries found</td></tr>@endforelse
    </tbody>
  </table></div>
</div>
<div id="createCModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:460px;max-height:85vh;overflow-y:auto">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:var(--navy2)">
      <span class="fw6">Add Country</span><button onclick="document.getElementById('createCModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.hotel-management.location.country.create')</div>
  </div>
</div>
<div id="editCModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:460px;max-height:85vh;overflow-y:auto">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:var(--navy2)">
      <span class="fw6">Edit Country</span><button onclick="document.getElementById('editCModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.hotel-management.location.country.edit')</div>
  </div>
</div>
@endsection
@section('script')<script>
function openCEdit(b){var m=document.getElementById('editCModal');m.style.display='flex';var f=function(n,v){var e=m.querySelector('[name="'+n+'"]');if(e)e.value=v;};f('id',b.dataset.id);f('name',b.dataset.name);f('language_id',b.dataset.language_id);}
</script>
<script>
(function() {
  document.addEventListener("submit", function(e) {
    var form = e.target;
    if (!form.closest || !form.closest("[id$=Modal]")) return;
    e.preventDefault();
    var btn = form.querySelector("button[type=submit]");
    var origHtml = btn ? btn.innerHTML : "";
    if (btn) { btn.disabled = true; btn.innerHTML = "Saving..."; }
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
        var msg = Object.keys(errors).map(function(k) {
          return Array.isArray(errors[k]) ? errors[k][0] : errors[k];
        }).join(", ");
        alert(msg || "An error occurred");
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