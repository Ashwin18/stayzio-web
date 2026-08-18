@extends('vendors.layout')
@section('section', 'Properties')
@section('page', 'Register New Property')

@section('content')
<style>
.pf-card{background:var(--navy2,#fff);border:1px solid var(--border,#e8eaed);border-radius:14px;padding:24px;margin-bottom:16px}
.pf-section-title{font-size:15px;font-weight:800;color:var(--text,#0c0c0e);margin-bottom:4px;display:flex;align-items:center;gap:8px}
.pf-section-sub{font-size:11px;color:var(--muted,#64748b);margin-bottom:16px}
.pf-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.pf-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
.pf-fg{margin-bottom:0}
.pf-label{font-size:11px;font-weight:700;color:var(--muted,#64748b);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px}
.pf-label .req{color:#00B8B8}
.pf-input{width:100%;padding:9px 12px;background:var(--navy3,#f8fafc);border:1.5px solid var(--border,#e8eaed);border-radius:8px;color:var(--text,#0c0c0e);font-size:13px;font-family:'Poppins',sans-serif;outline:none;transition:.15s}
.pf-input:focus{border-color:#00B8B8;background:#fff;color:#0c0c0e!important}
.pf-input{color:#f1f5f9}
.pf-input::placeholder{color:#64748b}
.pf-price-input{color:#00B8B8!important}
.pf-price-input:focus{background:#fff;color:#00B8B8!important}
select.pf-input{color:#f1f5f9}
select.pf-input:focus{color:#0c0c0e!important}
.pf-input::placeholder{color:var(--muted,#94a3b8)}
textarea.pf-input{min-height:80px;resize:vertical}
.pf-radio-group{display:flex;gap:10px;margin-top:4px}
.pf-radio{display:flex;align-items:center;gap:6px;padding:8px 16px;border:1.5px solid var(--border,#e8eaed);border-radius:8px;cursor:pointer;font-size:12px;font-weight:600;color:var(--text,#0c0c0e);transition:.15s;background:var(--navy3,#f8fafc)}
.pf-radio:has(input:checked){border-color:#00B8B8;color:#00B8B8;background:rgba(0,184,184,.06)}
.pf-radio input{accent-color:#00B8B8}
.pf-check-group{display:flex;flex-wrap:wrap;gap:10px;margin-top:4px}
.pf-check{display:flex;align-items:center;gap:7px;padding:8px 14px;border:1.5px solid var(--border,#e8eaed);border-radius:8px;cursor:pointer;font-size:12px;font-weight:600;transition:.15s;background:var(--navy3,#f8fafc)}
.pf-check:has(input:checked){border-color:#059669;color:#059669;background:rgba(5,150,105,.05)}
.pf-check input{accent-color:#059669}
.pf-file-wrap{position:relative;display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border:1.5px dashed var(--border,#e8eaed);border-radius:8px;cursor:pointer;font-size:12px;color:var(--muted,#64748b);transition:.15s;background:var(--navy3,#f8fafc)}
.pf-file-wrap:hover{border-color:#00B8B8;color:#00B8B8}
.pf-file-wrap input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer}
.pf-hint{font-size:10px;color:var(--muted,#94a3b8);margin-top:4px}
.pf-price-card{background:rgba(0,184,184,.05);border:1.5px solid rgba(0,184,184,.18);border-radius:10px;padding:14px;text-align:center}
.pf-price-label{font-size:10px;font-weight:700;color:var(--muted,#64748b);text-transform:uppercase;margin-bottom:6px}
.pf-price-input{width:100%;padding:10px;background:#fff;border:1.5px solid var(--border,#e8eaed);border-radius:8px;font-size:16px;font-weight:700;color:#00B8B8;text-align:center;font-family:'Poppins',sans-serif}
.pf-price-input:focus{border-color:#00B8B8;outline:none}
.pf-step-num{width:28px;height:28px;background:#00B8B8;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:900;flex-shrink:0}

.pf-file-preview{display:flex;align-items:center;gap:10px;padding:10px 14px;background:rgba(5,150,105,.06);border:1.5px solid rgba(5,150,105,.2);border-radius:8px}
.pf-file-name{font-size:12px;font-weight:700;color:var(--text,#f1f5f9);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.pf-file-size{font-size:10px;color:var(--muted,#64748b)}
.pf-file-thumb{width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid var(--border,#e8eaed)}
.pf-file-view{font-size:11px;color:#2563eb;font-weight:700;text-decoration:none;padding:4px 10px;background:rgba(37,99,235,.08);border-radius:6px}
.pf-file-view:hover{background:rgba(37,99,235,.15)}
.pf-file-delete{background:none;border:none;color:#dc2626;cursor:pointer;font-size:16px;padding:4px}
.pf-file-delete:hover{color:#b91c1c}
@media(max-width:768px){
.pf-grid,.pf-grid-3{grid-template-columns:1fr}
.pf-fg[style*="grid-column:span 2"]{grid-column:span 1!important}
.pf-radio-group{flex-wrap:wrap}
.pf-submit-row{flex-direction:column}
.pf-submit-row .btn{width:100%;justify-content:center}
}

/* Force dark text on all form inputs */
.pf-input,.pf-input:focus,.pf-price-input,
input[type="text"],input[type="number"],input[type="email"],input[type="tel"],
textarea,select{color:#1a1612!important;background:#f8f6f1!important}
input:focus,textarea:focus,select:focus{color:#1a1612!important;background:#fff!important;border-color:#00B8B8!important}
input::placeholder,textarea::placeholder{color:#94a3b8!important}
.pf-price-input,.pf-price-input:focus{color:#00B8B8!important;background:#fff!important}
</style>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>Register New Property</h2>
    <p>Fill in your hotel details to get listed on StayZio</p>
  </div>
  <a href="{{ route('vendor.properties.index') }}" class="btn btn-secondary">
    <i class="ti ti-arrow-left"></i> My Properties
  </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-3">
  <strong>Please fix the following errors:</strong>
  <ul class="mb-0 mt-1" style="font-size:12px">
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<form method="POST" action="{{ route('vendor.properties.store') }}" enctype="multipart/form-data">
@csrf

{{-- Section 1: Hotel Information --}}
<div class="pf-card">
  <div class="pf-section-title"><span class="pf-step-num">1</span> Hotel Information</div>
  <div class="pf-section-sub">Basic details about your property</div>
  <div class="pf-grid">
    <div class="pf-fg">
      <label class="pf-label">Hotel Name <span class="req">*</span></label>
      <input type="text" name="hotel_name" class="pf-input" placeholder="e.g. Grand Stay Inn" value="{{ old('hotel_name') }}" required>
    </div>
    <div class="pf-fg">
      <label class="pf-label">City <span class="req">*</span></label>
      <input type="text" name="city" class="pf-input" placeholder="e.g. Chennai" value="{{ old('city') }}" required>
    </div>
    <div class="pf-fg" style="grid-column:span 2">
      <label class="pf-label">Full Address <span class="req">*</span></label>
      <textarea name="address" class="pf-input" placeholder="Complete address with landmark" required>{{ old('address') }}</textarea>
    </div>
    <div class="pf-fg">
      <label class="pf-label">Pincode <span class="req">*</span></label>
      <input type="tel" name="pincode" class="pf-input" placeholder="600001" value="{{ old('pincode') }}" required pattern="[1-9][0-9]{5}" maxlength="6" minlength="6" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" title="Enter valid 6-digit pincode">
    </div>
    <div class="pf-fg">
      <label class="pf-label">Mobile Number <span class="req">*</span></label>
      <input type="tel" name="mobile_number" class="pf-input" placeholder="9876543210" value="{{ old('mobile_number') }}" required pattern="[6-9][0-9]{9}" maxlength="10" minlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)" title="Enter valid 10-digit mobile number starting with 6-9">
    </div>
    <div class="pf-fg">
      <label class="pf-label">Reception Number</label>
      <input type="tel" name="reception_number" class="pf-input" placeholder="9876543210" value="{{ old('reception_number') }}" pattern="[0-9]{10,12}" maxlength="12" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,12)" title="Enter valid phone number">
    </div>
    <div class="pf-fg">
      <label class="pf-label">Property Type <span class="req">*</span></label>
      <div class="pf-radio-group">
        <label class="pf-radio"><input type="radio" name="property_type" value="owned" {{ old('property_type','owned')=='owned'?'checked':'' }} required> Owned</label>
        <label class="pf-radio"><input type="radio" name="property_type" value="leased" {{ old('property_type')=='leased'?'checked':'' }}> Leased</label>
      </div>
    </div>
  </div>
</div>

{{-- Section 2: Room & Pricing --}}
<div class="pf-card">
  <div class="pf-section-title"><span class="pf-step-num">2</span> Room & Pricing</div>
  <div class="pf-section-sub">Room type, availability and hourly rates</div>
  <div class="pf-grid">
    <div class="pf-fg">
      <label class="pf-label">Room Type <span class="req">*</span></label>
      <select name="room_type" class="pf-input" required>
        <option value="" disabled {{ old('room_type') ? '' : '' }}>Select room type</option>
        @foreach($roomCategories ?? [] as $cat)
          <option value="{{ $cat->name }}" {{ old('room_type','Standard Room') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <div style="font-size:10.5px;color:var(--muted);margin-top:4px">Managed by admin</div>
    </div>
    <div class="pf-fg">
      <label class="pf-label">Total Rooms Available <span class="req">*</span></label>
      <input type="number" name="total_rooms" class="pf-input" placeholder="5" value="{{ old('total_rooms') }}" required min="1">
    </div>
  </div>
  <div class="pf-grid-3" style="margin-top:14px">
    <div class="pf-price-card">
      <div class="pf-price-label">3 Hours Rate (₹) <span class="req">*</span></div>
      <input type="number" name="price_3hrs" class="pf-price-input" placeholder="1000" value="{{ old('price_3hrs') }}" required min="0">
    </div>
    <div class="pf-price-card">
      <div class="pf-price-label">6 Hours Rate (₹) <span class="req">*</span></div>
      <input type="number" name="price_6hrs" class="pf-price-input" placeholder="1500" value="{{ old('price_6hrs') }}" required min="0">
    </div>
    <div class="pf-price-card">
      <div class="pf-price-label">Full Day Rate (₹) <span class="req">*</span></div>
      <input type="number" name="price_fullday" class="pf-price-input" placeholder="2500" value="{{ old('price_fullday') }}" required min="0">
    </div>
  </div>
</div>

{{-- Section 3: Perks & Amenities --}}
<div class="pf-card">
  <div class="pf-section-title"><span class="pf-step-num">3</span> Perks & Amenities</div>
  <div class="pf-section-sub">Select amenities available in your rooms (managed by admin)</div>
  <div class="pf-check-group">
    @forelse($perks ?? [] as $perk)
    <label class="pf-check">
      <input type="checkbox" name="perks[]" value="{{ $perk->id }}" {{ in_array($perk->id, old('perks', [])) ? 'checked' : '' }}>
      @if($perk->icon)<i class="{{ $perk->icon }}"></i>@endif {{ $perk->title }}
    </label>
    @empty
    <span style="font-size:12px;color:var(--muted)">No perks configured yet.</span>
    @endforelse
  </div>
</div>

{{-- Restrictions --}}
<div class="pf-card">
  <div class="pf-section-title"><i class="ti ti-alert-triangle" style="color:#d97706;font-size:18px"></i> Restrictions</div>
  <div class="pf-section-sub">Select restrictions that apply to your property</div>
  <div class="pf-check-group">
    @forelse($restrictions ?? [] as $restriction)
    <label class="pf-check">
      <input type="checkbox" name="restrictions[]" value="{{ $restriction->id }}" {{ in_array($restriction->id, old('restrictions', [])) ? 'checked' : '' }}>
      {{ $restriction->icon ?? '' }} {{ $restriction->title }}
    </label>
    @empty
    <span style="font-size:12px;color:var(--muted)">No restrictions configured yet.</span>
    @endforelse
  </div>
</div>

{{-- Section 4: Policies --}}
<div class="pf-card">
  <div class="pf-section-title"><span class="pf-step-num">4</span> Policies</div>
  <div class="pf-section-sub">Guest policies and rules for your property</div>
  <div class="pf-grid">
    @foreach([
      ['allow_same_city_couples', 'Allow Same City Couples?'],
      ['allow_outstation_couples', 'Allow Outstation Couples?'],
      ['allow_smoking_drinking', 'Allow Smoking / Drinking?'],
      ['food_facility', 'Food Facility Available?'],
    ] as [$field, $label])
    <div class="pf-fg">
      <label class="pf-label">{{ $label }} <span class="req">*</span></label>
      <div class="pf-radio-group">
        <label class="pf-radio"><input type="radio" name="{{ $field }}" value="yes" {{ old($field)=='yes'?'checked':'' }} required> Yes</label>
        <label class="pf-radio"><input type="radio" name="{{ $field }}" value="no" {{ old($field)=='no'?'checked':'' }}> No</label>
      </div>
    </div>
    @endforeach
    <div class="pf-fg" style="grid-column:span 2">
      <label class="pf-label">Cancellation Policy Acceptance <span class="req">*</span></label>
      <label class="pf-check" style="max-width:fit-content">
        <input type="radio" name="cancellation_policy_acceptance" value="yes" {{ old('cancellation_policy_acceptance')=='yes'?'checked':'' }} required>
        I accept StayZio's standard cancellation policy
      </label>
    </div>
  </div>
</div>

{{-- Section 5: Owner / Manager Details --}}
<div class="pf-card">
  <div class="pf-section-title"><span class="pf-step-num">5</span> Owner / Manager Details</div>
  <div class="pf-section-sub">Primary contact for this property</div>
  <div class="pf-grid">
    <div class="pf-fg">
      <label class="pf-label">Owner or Manager Name <span class="req">*</span></label>
      <input type="text" name="owner_name" class="pf-input" placeholder="Full name" value="{{ old('owner_name') }}" required>
    </div>
    <div class="pf-fg">
      <label class="pf-label">Owner / Manager Contact Number <span class="req">*</span></label>
      <input type="tel" name="owner_contact" class="pf-input" placeholder="9876543210" value="{{ old('owner_contact') }}" required pattern="[6-9][0-9]{9}" maxlength="10" minlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)" title="Enter valid 10-digit mobile number starting with 6-9">
    </div>
    <div class="pf-fg">
      <label class="pf-label">Owner / Manager Email <span class="req">*</span></label>
      <input type="email" name="owner_email" class="pf-input"
             placeholder="owner@example.com"
             value="{{ old('owner_email', optional(Auth::guard('vendor')->user())->email) }}"
             maxlength="255" required>
      <div class="pf-hint">Property approval and operational communication will be sent to this email.</div>
    </div>
  </div>
</div>


{{-- Section 7: GST & Bank Details --}}
<div class="pf-card">
  <div class="pf-section-title"><span class="pf-step-num">6</span> GST & Bank Details</div>
  <div class="pf-section-sub">Required for commission payouts and tax compliance</div>

  <div class="pf-grid">
    <div class="pf-fg">
      <label class="pf-label">GSTIN <span class="req">*</span></label>
      <input type="text" name="gstin" class="pf-input" placeholder="22AAAAA0000A1Z5" value="{{ old('gstin') }}" required maxlength="15" style="text-transform:uppercase">
    </div>
    <div class="pf-fg">
      <label class="pf-label">GST Certificate <span class="req">*</span></label>
      <div id="gst-upload-area">
        <div class="pf-file-wrap">
          <i class="ti ti-upload"></i> Upload GST Certificate
          <input type="file" name="gst_certificate" id="gst_certificate_input" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(this,'gst')">
        </div>
        <div class="pf-hint">PDF, JPG or PNG (max 5MB)</div>
      </div>
      <div id="gst-preview" style="display:none;margin-top:8px">
        <div class="pf-file-preview">
          <i class="ti ti-file-check" style="font-size:20px;color:#059669"></i>
          <div style="flex:1;min-width:0">
            <div id="gst-filename" class="pf-file-name"></div>
            <div id="gst-filesize" class="pf-file-size"></div>
          </div>
          <img id="gst-thumb" src="" class="pf-file-thumb" style="display:none">
          <a id="gst-view-btn" href="#" target="_blank" class="pf-file-view" style="display:none">View</a>
          <button type="button" onclick="removeFile('gst')" class="pf-file-delete" title="Remove & re-upload">
            <i class="ti ti-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div style="border-top:1px solid var(--border,#e8eaed);margin:16px 0;padding-top:16px">
    <div style="font-size:12px;font-weight:700;color:var(--text,#0c0c0e);margin-bottom:12px">
      <i class="ti ti-building-bank" style="color:#00B8B8"></i> Bank Account Details
    </div>
    <div class="pf-grid">
      <div class="pf-fg">
        <label class="pf-label">Bank Name <span class="req">*</span></label>
        <input type="text" name="bank_name" class="pf-input" placeholder="e.g. State Bank of India" value="{{ old('bank_name') }}" required>
      </div>
      <div class="pf-fg">
        <label class="pf-label">Account Holder Name <span class="req">*</span></label>
        <input type="text" name="account_holder_name" class="pf-input" placeholder="As per bank records" value="{{ old('account_holder_name') }}" required>
      </div>
      <div class="pf-fg">
        <label class="pf-label">Account Number <span class="req">*</span></label>
        <input type="text" name="account_number" class="pf-input" placeholder="1234567890" value="{{ old('account_number') }}" required>
      </div>
      <div class="pf-fg">
        <label class="pf-label">IFSC Code <span class="req">*</span></label>
        <input type="text" name="ifsc_code" class="pf-input" placeholder="SBIN0001234" value="{{ old('ifsc_code') }}" required maxlength="11" minlength="11" pattern="[A-Z]{4}0[A-Z0-9]{6}" style="text-transform:uppercase" oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,11)" title="IFSC format: 4 letters + 0 + 6 alphanumeric (e.g. SBIN0001234)">
        <div class="pf-hint">Format: 4 letters + 0 + 6 characters (e.g. SBIN0001234)</div>
      </div>
      <div class="pf-fg">
        <label class="pf-label">Branch Name</label>
        <input type="text" name="branch_name" class="pf-input" placeholder="e.g. Anna Nagar Branch" value="{{ old('branch_name') }}">
      </div>
      <div class="pf-fg">
        <label class="pf-label">Cancelled Cheque <span class="req">*</span></label>
        <div id="cheque-upload-area">
          <div class="pf-file-wrap">
            <i class="ti ti-upload"></i> Upload Cancelled Cheque
            <input type="file" name="cancelled_cheque" id="cancelled_cheque_input" accept=".pdf,.jpg,.jpeg,.png" required onchange="previewFile(this,'cheque')">
          </div>
          <div class="pf-hint">PDF, JPG or PNG (max 5MB)</div>
        </div>
        <div id="cheque-preview" style="display:none;margin-top:8px">
          <div class="pf-file-preview">
            <i class="ti ti-file-check" style="font-size:20px;color:#059669"></i>
            <div style="flex:1;min-width:0">
              <div id="cheque-filename" class="pf-file-name"></div>
              <div id="cheque-filesize" class="pf-file-size"></div>
            </div>
            <img id="cheque-thumb" src="" class="pf-file-thumb" style="display:none">
            <a id="cheque-view-btn" href="#" target="_blank" class="pf-file-view" style="display:none">View</a>
            <button type="button" onclick="removeFile('cheque')" class="pf-file-delete" title="Remove & re-upload">
              <i class="ti ti-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Submit --}}
<div class="pf-submit-row" style="display:flex;gap:10px;margin-top:8px;margin-bottom:32px">
  <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-size:14px">
    <i class="ti ti-send"></i> Submit for Review
  </button>
  <a href="{{ route('vendor.properties.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>
@endsection

@section('script')
<script>
function previewFile(input, type) {
  var file = input.files[0];
  if (!file) return;
  if (file.size > 5 * 1024 * 1024) {
    alert("File size must be less than 5MB");
    input.value = "";
    return;
  }
  document.getElementById(type + "-upload-area").style.display = "none";
  document.getElementById(type + "-preview").style.display = "block";
  document.getElementById(type + "-filename").textContent = file.name;
  document.getElementById(type + "-filesize").textContent = (file.size / 1024).toFixed(1) + " KB";

  var thumb = document.getElementById(type + "-thumb");
  var viewBtn = document.getElementById(type + "-view-btn");
  if (file.type.startsWith("image/")) {
    var reader = new FileReader();
    reader.onload = function(e) {
      thumb.src = e.target.result;
      thumb.style.display = "block";
      viewBtn.href = e.target.result;
      viewBtn.style.display = "inline-block";
    };
    reader.readAsDataURL(file);
  } else {
    thumb.style.display = "none";
    viewBtn.style.display = "none";
  }
}

function removeFile(type) {
  var inputId = type === "gst" ? "gst_certificate_input" : "cancelled_cheque_input";
  document.getElementById(inputId).value = "";
  document.getElementById(type + "-preview").style.display = "none";
  document.getElementById(type + "-upload-area").style.display = "block";
  document.getElementById(type + "-thumb").src = "";
  document.getElementById(type + "-thumb").style.display = "none";
  document.getElementById(type + "-view-btn").style.display = "none";
}

document.querySelector("form").addEventListener("submit", function(e) {
  var phones = [
    {name: "mobile_number", label: "Mobile Number", req: true},
    {name: "owner_contact", label: "Owner Contact", req: true}
  ];
  for (var i = 0; i < phones.length; i++) {
    var inp = document.querySelector("[name='" + phones[i].name + "']");
    if (!inp) continue;
    var v = inp.value.trim();
    if (!v && !phones[i].req) continue;
    if (v && (v.length !== 10 || !/^[6-9][0-9]{9}$/.test(v))) {
      e.preventDefault();
      alert(phones[i].label + " must be a valid 10-digit Indian mobile number starting with 6-9");
      inp.focus();
      return;
    }
  }
  var ifsc = document.querySelector("[name='ifsc_code']");
  if (ifsc && ifsc.value.trim() && !/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc.value.trim().toUpperCase())) {
    e.preventDefault();
    alert("IFSC Code must be in format: 4 letters + 0 + 6 alphanumeric (e.g. SBIN0001234)");
    ifsc.focus();
    return;
  }
  var pin = document.querySelector("[name='pincode']");
  if (pin && pin.value.trim() && !/^[1-9][0-9]{5}$/.test(pin.value.trim())) {
    e.preventDefault();
    alert("Pincode must be a valid 6-digit number");
    pin.focus();
    return;
  }
});
</script>
@endsection
