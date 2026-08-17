@extends('admin.layout')
@section('section','Hotel Management')
@section('page','Master Data — Perks, Policies & Restrictions')
@section('content')

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Hotel Master Data</h2>
    <p>Manage Perks, Policies and Restrictions that vendors can select when adding hotels</p>
  </div>
</div>

{{-- Tab nav --}}
<div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:18px">
  <button class="mdt-tab active" onclick="switchTab('perks',this)">
    <i class="fas fa-gem"></i> Perks <span class="mdt-count">{{ count($perks) }}</span>
  </button>
  <button class="mdt-tab" onclick="switchTab('policies',this)">
    <i class="fas fa-clipboard-list"></i> Policies <span class="mdt-count">{{ count($policies) }}</span>
  </button>
  <button class="mdt-tab" onclick="switchTab('restrictions',this)">
    <i class="fas fa-ban"></i> Restrictions <span class="mdt-count">{{ count($restrictions) }}</span>
  </button>
</div>

<style>
.mdt-tab{background:none;border:none;border-bottom:3px solid transparent;padding:10px 20px;font-size:13px;font-weight:700;color:var(--muted);cursor:pointer;display:inline-flex;align-items:center;gap:7px;margin-bottom:-2px;transition:all .15s}
.mdt-tab.active{color:var(--red);border-bottom-color:var(--red)}
.mdt-tab i{font-size:13px}
.mdt-count{background:var(--navy3);color:var(--muted);font-size:10px;font-weight:800;padding:2px 7px;border-radius:20px}
.mdt-tab.active .mdt-count{background:var(--red);color:#fff}
.mdt-panel{display:none}
.mdt-panel.active{display:block}
.master-table{width:100%}
.master-table th{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);padding:10px 14px;text-align:left;border-bottom:1px solid var(--border)}
.master-table td{padding:11px 14px;border-bottom:1px solid var(--border);font-size:13px;color:var(--text)}
.master-table tr:last-child td{border-bottom:none}
.master-table tr:hover td{background:var(--navy3)}
.icon-preview{font-size:18px;display:flex;align-items:center;justify-content:center;width:36px;height:36px;background:var(--navy3);border-radius:8px}
.emoji-preview{font-size:22px}
.type-badge{font-size:10px;font-weight:800;padding:3px 9px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em}
.badge-allowed{background:#dcfce7;color:#15803d}
.badge-limited{background:#fef3c7;color:#92400e}
.badge-not_allowed{background:#fee2e2;color:#dc2626}
</style>

{{-- ── PERKS PANEL ── --}}
<div id="panel-perks" class="mdt-panel active">
  <div class="card">
    <div class="card-hdr">
      <div class="card-title"><i class="fas fa-gem" style="color:var(--red)"></i> Perks</div>
      <button onclick="openModal('createPerkModal')" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Add Perk
      </button>
    </div>
    <div class="tbl-wrap">
      <table class="master-table">
        <thead><tr>
          <th style="width:50px">Icon</th>
          <th>Perk Name</th>
          <th style="width:80px">Status</th>
          <th style="width:100px">Actions</th>
        </tr></thead>
        <tbody>
          @forelse($perks as $p)
          <tr>
            <td><div class="icon-preview"><i class="{{ $p->icon }}" style="color:var(--red)"></i></div></td>
            <td class="td-main">{{ $p->title }}</td>
            <td><span class="badge {{ $p->status ? 'green' : 'warning' }} no-dot">{{ $p->status ? 'Active' : 'Hidden' }}</span></td>
            <td>
              <div style="display:flex;gap:5px">
                <button class="btn btn-secondary btn-xs" onclick="openEditPerk(this)"
                  data-id="{{ $p->id }}" data-icon="{{ $p->icon }}"
                  data-title="{{ $p->title }}" data-status="{{ $p->status }}">
                  <i class="ti ti-edit"></i>
                </button>
                <form action="{{ route('admin.hotel_management.master_data.perk.delete', $p->id) }}"
                      method="POST" onsubmit="return confirm('Delete this perk?')">
                  @csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted)">No perks yet. Add your first one.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- ── POLICIES PANEL ── --}}
<div id="panel-policies" class="mdt-panel">
  <div class="card">
    <div class="card-hdr">
      <div class="card-title"><i class="fas fa-clipboard-list" style="color:var(--red)"></i> Policies</div>
      <button onclick="openModal('createPolicyModal')" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Add Policy
      </button>
    </div>
    <div class="tbl-wrap">
      <table class="master-table">
        <thead><tr>
          <th style="width:50px">Icon</th>
          <th>Policy</th>
          <th>Default Description</th>
          <th style="width:80px">Status</th>
          <th style="width:100px">Actions</th>
        </tr></thead>
        <tbody>
          @forelse($policies as $p)
          <tr>
            <td><div class="icon-preview"><i class="{{ $p->icon }}" style="color:var(--red)"></i></div></td>
            <td class="td-main" style="font-weight:700">{{ $p->title }}</td>
            <td style="color:var(--muted);font-size:12px">{{ $p->description ?? '—' }}</td>
            <td><span class="badge {{ $p->status ? 'green' : 'warning' }} no-dot">{{ $p->status ? 'Active' : 'Hidden' }}</span></td>
            <td>
              <div style="display:flex;gap:5px">
                <button class="btn btn-secondary btn-xs" onclick="openEditPolicy(this)"
                  data-id="{{ $p->id }}" data-icon="{{ $p->icon }}"
                  data-title="{{ $p->title }}" data-description="{{ $p->description }}"
                  data-status="{{ $p->status }}">
                  <i class="ti ti-edit"></i>
                </button>
                <form action="{{ route('admin.hotel_management.master_data.policy.delete', $p->id) }}"
                      method="POST" onsubmit="return confirm('Delete this policy?')">
                  @csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No policies yet. Add your first one.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- ── RESTRICTIONS PANEL ── --}}
<div id="panel-restrictions" class="mdt-panel">
  <div class="card">
    <div class="card-hdr">
      <div class="card-title"><i class="fas fa-ban" style="color:var(--red)"></i> Restrictions</div>
      <button onclick="openModal('createRestrModal')" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Add Restriction
      </button>
    </div>
    <div class="tbl-wrap">
      <table class="master-table">
        <thead><tr>
          <th style="width:50px">Icon</th>
          <th>Restriction</th>
          <th style="width:140px">Default Type</th>
          <th style="width:80px">Status</th>
          <th style="width:100px">Actions</th>
        </tr></thead>
        <tbody>
          @forelse($restrictions as $r)
          <tr>
            <td><div class="icon-preview"><span class="emoji-preview">{{ $r->icon }}</span></div></td>
            <td class="td-main" style="font-weight:700">{{ $r->title }}</td>
            <td>
              <span class="type-badge badge-{{ $r->default_type }}">
                @if($r->default_type === 'allowed') ✅ Allowed
                @elseif($r->default_type === 'limited') ⚠️ Limited
                @else 🚫 Not Allowed @endif
              </span>
            </td>
            <td><span class="badge {{ $r->status ? 'green' : 'warning' }} no-dot">{{ $r->status ? 'Active' : 'Hidden' }}</span></td>
            <td>
              <div style="display:flex;gap:5px">
                <button class="btn btn-secondary btn-xs" onclick="openEditRestr(this)"
                  data-id="{{ $r->id }}" data-icon="{{ $r->icon }}"
                  data-title="{{ $r->title }}" data-type="{{ $r->default_type }}"
                  data-status="{{ $r->status }}">
                  <i class="ti ti-edit"></i>
                </button>
                <form action="{{ route('admin.hotel_management.master_data.restriction.delete', $r->id) }}"
                      method="POST" onsubmit="return confirm('Delete this restriction?')">
                  @csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No restrictions yet. Add your first one.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- ======= MODALS ======= --}}
@php
function mdtModal($id,$title,$body,$submitRoute){
  return '<div id="'.$id.'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
<div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:500px;max-width:96vw">
<div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
<span class="fw6">'.$title.'</span>
<button onclick="closeModal(\''.$id.'\')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
</div>
<form action="'.$submitRoute.'" method="POST">@csrf'.$body.'</form></div></div>';
}
@endphp

{{-- Create Perk Modal --}}
<div id="createPerkModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px;max-width:96vw">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6"><i class="fas fa-gem" style="color:var(--red);margin-right:6px"></i>Add Perk</span>
      <button onclick="closeModal('createPerkModal')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.master_data.perk.store') }}" method="POST">
      @csrf
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        <div class="fg">
          <label class="flabel">Icon Class *</label>
          <input type="text" name="icon" class="fc" placeholder="e.g. fas fa-wifi" required>
          <div class="fc-hint">Font Awesome class — e.g. <code>fas fa-swimming-pool</code>, <code>fas fa-spa</code></div>
        </div>
        <div class="fg">
          <label class="flabel">Perk Title *</label>
          <input type="text" name="title" class="fc" placeholder="e.g. Free Wi-Fi" required>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Save Perk</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal('createPerkModal')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Edit Perk Modal --}}
<div id="editPerkModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px;max-width:96vw">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Perk</span>
      <button onclick="closeModal('editPerkModal')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.master_data.perk.update') }}" method="POST">
      @csrf
      <input type="hidden" name="id" id="ep_id">
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        <div class="fg">
          <label class="flabel">Icon Class *</label>
          <input type="text" name="icon" id="ep_icon" class="fc" required>
        </div>
        <div class="fg">
          <label class="flabel">Perk Title *</label>
          <input type="text" name="title" id="ep_title" class="fc" required>
        </div>
        <div class="fg">
          <label class="flabel">Status</label>
          <select name="status" id="ep_status" class="fc">
            <option value="1">Active</option>
            <option value="0">Hidden</option>
          </select>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Update</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal('editPerkModal')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Create Policy Modal --}}
<div id="createPolicyModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:500px;max-width:96vw">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6"><i class="fas fa-clipboard-list" style="color:var(--red);margin-right:6px"></i>Add Policy</span>
      <button onclick="closeModal('createPolicyModal')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.master_data.policy.store') }}" method="POST">
      @csrf
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        <div class="fg">
          <label class="flabel">Icon Class *</label>
          <input type="text" name="icon" class="fc" placeholder="e.g. fas fa-sign-in-alt" required>
        </div>
        <div class="fg">
          <label class="flabel">Policy Name *</label>
          <input type="text" name="title" class="fc" placeholder="e.g. Check-in" required>
        </div>
        <div class="fg">
          <label class="flabel">Default Description <span style="font-weight:400;color:var(--muted)">(Hint shown to vendors)</span></label>
          <input type="text" name="description" class="fc" placeholder="e.g. Check-in from 12:00 PM onwards">
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Save Policy</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal('createPolicyModal')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Edit Policy Modal --}}
<div id="editPolicyModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:500px;max-width:96vw">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Policy</span>
      <button onclick="closeModal('editPolicyModal')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.master_data.policy.update') }}" method="POST">
      @csrf
      <input type="hidden" name="id" id="epo_id">
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        <div class="fg">
          <label class="flabel">Icon Class *</label>
          <input type="text" name="icon" id="epo_icon" class="fc" required>
        </div>
        <div class="fg">
          <label class="flabel">Policy Name *</label>
          <input type="text" name="title" id="epo_title" class="fc" required>
        </div>
        <div class="fg">
          <label class="flabel">Default Description</label>
          <input type="text" name="description" id="epo_desc" class="fc">
        </div>
        <div class="fg">
          <label class="flabel">Status</label>
          <select name="status" id="epo_status" class="fc">
            <option value="1">Active</option>
            <option value="0">Hidden</option>
          </select>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Update</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal('editPolicyModal')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Create Restriction Modal --}}
<div id="createRestrModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px;max-width:96vw">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6"><i class="fas fa-ban" style="color:var(--red);margin-right:6px"></i>Add Restriction</span>
      <button onclick="closeModal('createRestrModal')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.master_data.restriction.store') }}" method="POST">
      @csrf
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        <div style="display:grid;grid-template-columns:90px 1fr;gap:10px">
          <div class="fg">
            <label class="flabel">Emoji Icon *</label>
            <input type="text" name="icon" class="fc" placeholder="🚭" maxlength="10" required
              style="font-size:22px;text-align:center;padding:8px">
            <div class="fc-hint">Paste an emoji</div>
          </div>
          <div class="fg">
            <label class="flabel">Restriction Title *</label>
            <input type="text" name="title" class="fc" placeholder="e.g. No Smoking" required>
          </div>
        </div>
        <div class="fg">
          <label class="flabel">Default Type</label>
          <select name="default_type" class="fc">
            <option value="allowed">✅ Allowed</option>
            <option value="limited">⚠️ Limited</option>
            <option value="not_allowed" selected>🚫 Not Allowed</option>
          </select>
          <div class="fc-hint">Vendors can override this per hotel</div>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Save Restriction</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal('createRestrModal')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Edit Restriction Modal --}}
<div id="editRestrModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px;max-width:96vw">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span class="fw6">Edit Restriction</span>
      <button onclick="closeModal('editRestrModal')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <form action="{{ route('admin.hotel_management.master_data.restriction.update') }}" method="POST">
      @csrf
      <input type="hidden" name="id" id="er_id">
      <div style="padding:16px;display:flex;flex-direction:column;gap:12px">
        <div style="display:grid;grid-template-columns:90px 1fr;gap:10px">
          <div class="fg">
            <label class="flabel">Emoji *</label>
            <input type="text" name="icon" id="er_icon" class="fc" maxlength="10" required
              style="font-size:22px;text-align:center;padding:8px">
          </div>
          <div class="fg">
            <label class="flabel">Title *</label>
            <input type="text" name="title" id="er_title" class="fc" required>
          </div>
        </div>
        <div class="fg">
          <label class="flabel">Default Type</label>
          <select name="default_type" id="er_type" class="fc">
            <option value="allowed">✅ Allowed</option>
            <option value="limited">⚠️ Limited</option>
            <option value="not_allowed">🚫 Not Allowed</option>
          </select>
        </div>
        <div class="fg">
          <label class="flabel">Status</label>
          <select name="status" id="er_status" class="fc">
            <option value="1">Active</option>
            <option value="0">Hidden</option>
          </select>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn btn-primary" style="flex:1"><i class="ti ti-device-floppy"></i> Update</button>
          <button type="button" class="btn btn-secondary" onclick="closeModal('editRestrModal')">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection
@section('script')
<script>
function switchTab(tab, btn) {
  document.querySelectorAll('.mdt-tab').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.mdt-panel').forEach(p => p.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('panel-' + tab).classList.add('active');
}
function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function openEditPerk(b) {
  document.getElementById('ep_id').value     = b.dataset.id;
  document.getElementById('ep_icon').value   = b.dataset.icon;
  document.getElementById('ep_title').value  = b.dataset.title;
  document.getElementById('ep_status').value = b.dataset.status;
  openModal('editPerkModal');
}
function openEditPolicy(b) {
  document.getElementById('epo_id').value     = b.dataset.id;
  document.getElementById('epo_icon').value   = b.dataset.icon;
  document.getElementById('epo_title').value  = b.dataset.title;
  document.getElementById('epo_desc').value   = b.dataset.description;
  document.getElementById('epo_status').value = b.dataset.status;
  openModal('editPolicyModal');
}
function openEditRestr(b) {
  document.getElementById('er_id').value     = b.dataset.id;
  document.getElementById('er_icon').value   = b.dataset.icon;
  document.getElementById('er_title').value  = b.dataset.title;
  document.getElementById('er_type').value   = b.dataset.type;
  document.getElementById('er_status').value = b.dataset.status;
  openModal('editRestrModal');
}

// AJAX form submit for all modals
document.addEventListener('submit', function(e) {
  var form = e.target;
  if (!form.closest('[id$=Modal]')) return;
  e.preventDefault();
  var btn = form.querySelector('button[type=submit]');
  var orig = btn ? btn.innerHTML : '';
  if (btn) { btn.disabled = true; btn.innerHTML = 'Saving...'; }
  fetch(form.action, {
    method: 'POST', body: new FormData(form),
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === 'success') { window.location.reload(); }
    else {
      var errors = data.errors || {};
      alert(Object.values(errors).flat().join('\n'));
      if (btn) { btn.disabled = false; btn.innerHTML = orig; }
    }
  })
  .catch(() => { if (btn) { btn.disabled = false; btn.innerHTML = orig; } });
}, true);
</script>
@endsection
