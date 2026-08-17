@extends('admin.layout')
@section('section', 'Property Management')
@section('page', 'Review Property')

@section('content')
@php $badge = $property->status_badge; @endphp

<style>
.rv-card{background:#ffffff;border:1px solid #e8e2d9;border-radius:14px;padding:24px;margin-bottom:14px;box-shadow:0 1px 4px rgba(0,0,0,.04)}
.rv-title{font-size:15px;font-weight:800;color:#1a1612;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.rv-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
.rv-field{padding:10px 14px;background:#f8f6f1;border:1px solid #e8e2d9;border-radius:8px}
.rv-field-label{font-size:9px;font-weight:700;color:#6b6560;text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px}
.rv-field-value{font-size:13px;font-weight:700;color:#1a1612}
.rv-yes{color:#059669;font-weight:800}
.rv-no{color:#dc2626;font-weight:800}
.rv-doc-link{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:rgba(37,99,235,.06);border:1px solid rgba(37,99,235,.2);border-radius:6px;font-size:11px;color:#2563eb;font-weight:600;text-decoration:none}
.rv-price{background:rgba(227,30,36,.04);border:1.5px solid rgba(227,30,36,.15);border-radius:10px;padding:14px;text-align:center}
.rv-price-val{font-size:22px;font-weight:900;color:#e31e24}
.rv-price-lbl{font-size:10px;font-weight:700;color:#6b6560;text-transform:uppercase;margin-top:4px}
.rv-drop-zone{border:2px dashed #d5d0c8;border-radius:12px;padding:28px;text-align:center;cursor:pointer;transition:.2s;background:#faf8f5}
.rv-drop-zone:hover{border-color:#e31e24;background:rgba(227,30,36,.02)}
.rv-img-preview-card{display:flex;align-items:center;gap:14px;padding:12px 16px;background:#faf8f5;border:1.5px solid #e8e2d9;border-radius:10px}
.rv-img-thumb-lg{width:100px;height:70px;object-fit:cover;border-radius:8px;border:1px solid #e8e2d9;flex-shrink:0}
.rv-img-name{font-size:12px;font-weight:700;color:#1a1612;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.rv-img-size{font-size:10px;color:#6b6560;margin-top:2px}
.rv-img-btn{font-size:11px;font-weight:700;text-decoration:none;padding:5px 12px;border-radius:6px;border:1.5px solid;cursor:pointer;display:inline-flex;align-items:center;gap:4px;background:none}
.rv-img-btn-view{color:#2563eb;border-color:rgba(37,99,235,.2)}
.rv-img-btn-del{color:#dc2626;border-color:rgba(220,38,38,.2)}
.rv-gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px}
.rv-gallery-item{border-radius:10px;overflow:hidden;border:1.5px solid #e8e2d9;background:#faf8f5}
.rv-gallery-item img{width:100%;height:100px;object-fit:cover;display:block}
.rv-gallery-item-bar{display:flex;align-items:center;justify-content:space-between;padding:5px 8px;background:#fff}
.rv-gallery-item-name{font-size:10px;color:#1a1612;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1}
.rv-gallery-item-actions{display:flex;gap:2px}
.rv-gallery-item-actions a,.rv-gallery-item-actions button{background:none;border:none;cursor:pointer;padding:2px 4px;font-size:14px;border-radius:4px}
.rv-gallery-item-actions a{color:#2563eb}
.rv-gallery-item-actions button{color:#dc2626}
.rv-progress-bar{width:100%;height:4px;background:#e8e2d9;border-radius:4px;overflow:hidden}
.rv-progress-fill{height:100%;background:#e31e24;border-radius:4px;transition:width .3s}
.rv-spinner{width:14px;height:14px;border:2px solid #e8e2d9;border-top:2px solid #e31e24;border-radius:50%;animation:rvspin .8s linear infinite;display:inline-block}
@keyframes rvspin{to{transform:rotate(360deg)}}
@media(max-width:768px){.rv-grid{grid-template-columns:1fr}}
</style>

<div class="page-hdr">
  <div class="page-hdr-left">
    <h2>{{ $property->hotel_name }}</h2>
    <p>Submitted by {{ $property->vendor_name }} ({{ $property->vendor_email }}) on {{ $property->created_at->format('d M Y, h:i A') }}</p>
  </div>
  <div style="display:flex;align-items:center;gap:10px">
    <span style="padding:6px 16px;font-size:12px;font-weight:700;border-radius:20px;background:{{ $badge['bg'] }};color:{{ $badge['color'] }}">{{ $badge['label'] }}</span>
    <a href="{{ route('admin.property_submissions.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>

@if(Session::has('success'))
<div class="alert alert-success mb-3">{{ Session::get('success') }}</div>
@endif

<div class="rv-card">
  <div class="rv-title"><i class="ti ti-building" style="color:#e31e24"></i> Hotel Information</div>
  <div class="rv-grid">
    <div class="rv-field"><div class="rv-field-label">Hotel Name</div><div class="rv-field-value">{{ $property->hotel_name }}</div></div>
    <div class="rv-field"><div class="rv-field-label">City</div><div class="rv-field-value">{{ $property->city }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Pincode</div><div class="rv-field-value">{{ $property->pincode }}</div></div>
    <div class="rv-field" style="grid-column:span 2"><div class="rv-field-label">Address</div><div class="rv-field-value">{{ $property->address }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Property Type</div><div class="rv-field-value">{{ ucfirst($property->property_type) }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Mobile</div><div class="rv-field-value">{{ $property->mobile_number }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Reception</div><div class="rv-field-value">{{ $property->reception_number ?? '—' }}</div></div>
  </div>
</div>

<div class="rv-card">
  <div class="rv-title"><i class="ti ti-bed" style="color:#e31e24"></i> Room & Pricing</div>
  <div class="rv-grid" style="margin-bottom:14px">
    <div class="rv-field"><div class="rv-field-label">Room Type</div><div class="rv-field-value">{{ $property->room_type }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Total Rooms</div><div class="rv-field-value">{{ $property->total_rooms }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Amenities</div><div class="rv-field-value">{{ implode(', ', $property->amenities_list) ?: '—' }}</div></div>
  </div>
  <div class="rv-grid">
    <div class="rv-price"><div class="rv-price-val">₹{{ number_format($property->price_3hrs) }}</div><div class="rv-price-lbl">3 Hours</div></div>
    <div class="rv-price"><div class="rv-price-val">₹{{ number_format($property->price_6hrs) }}</div><div class="rv-price-lbl">6 Hours</div></div>
    <div class="rv-price"><div class="rv-price-val">₹{{ number_format($property->price_fullday) }}</div><div class="rv-price-lbl">Full Day</div></div>
  </div>
</div>

<div class="rv-card">
  <div class="rv-title"><i class="ti ti-shield-check" style="color:#e31e24"></i> Policies</div>
  <div class="rv-grid">
    @foreach([['allow_same_city_couples','Same City Couples'],['allow_outstation_couples','Outstation Couples'],['allow_smoking_drinking','Smoking / Drinking'],['food_facility','Food Facility'],['cancellation_policy_acceptance','Cancellation Policy']] as [$field,$label])
    <div class="rv-field"><div class="rv-field-label">{{ $label }}</div><div class="rv-field-value {{ $property->$field==='yes'?'rv-yes':'rv-no' }}">{{ $property->$field==='yes'?'✅ Yes':'❌ No' }}</div></div>
    @endforeach
  </div>
</div>

<div class="rv-card">
  <div class="rv-title"><i class="ti ti-users" style="color:#e31e24"></i> Owner & Manager</div>
  <div class="rv-grid">
    <div class="rv-field"><div class="rv-field-label">Owner Name</div><div class="rv-field-value">{{ $property->owner_name }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Owner Contact</div><div class="rv-field-value">{{ $property->owner_contact }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Owner Email</div><div class="rv-field-value">{{ $property->owner_email }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Manager Name</div><div class="rv-field-value">{{ $property->manager_name ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Manager Number</div><div class="rv-field-value">{{ $property->manager_number ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Manager Email</div><div class="rv-field-value">{{ $property->manager_email ?? '—' }}</div></div>
  </div>
</div>

<div class="rv-card">
  <div class="rv-title"><i class="ti ti-receipt-tax" style="color:#e31e24"></i> GST & Bank Details</div>
  <div class="rv-grid">
    <div class="rv-field"><div class="rv-field-label">GSTIN</div><div class="rv-field-value">{{ $property->gstin ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">GST Certificate</div><div class="rv-field-value">@if($property->gst_certificate)<a href="{{ asset('assets/img/'.$property->gst_certificate) }}" target="_blank" class="rv-doc-link"><i class="ti ti-file-download"></i> View</a>@else — @endif</div></div>
    <div class="rv-field"><div class="rv-field-label">Bank Name</div><div class="rv-field-value">{{ $property->bank_name ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Account Holder</div><div class="rv-field-value">{{ $property->account_holder_name ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Account Number</div><div class="rv-field-value">{{ $property->account_number ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">IFSC Code</div><div class="rv-field-value">{{ $property->ifsc_code ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Branch</div><div class="rv-field-value">{{ $property->branch_name ?? '—' }}</div></div>
    <div class="rv-field"><div class="rv-field-label">Cancelled Cheque</div><div class="rv-field-value">@if($property->cancelled_cheque)<a href="{{ asset('assets/img/'.$property->cancelled_cheque) }}" target="_blank" class="rv-doc-link"><i class="ti ti-file-download"></i> View</a>@else — @endif</div></div>
  </div>
</div>

@if($property->status === 'pending' || $property->status === 'approved')
<div class="rv-card" style="border-color:rgba(5,150,105,.3);background:rgba(5,150,105,.02)">
  <div class="rv-title"><i class="ti ti-settings" style="color:#059669"></i> Admin Actions — Approve & Make Live</div>
  <form method="POST" action="{{ route('admin.property_submissions.approve', $property->id) }}" enctype="multipart/form-data" id="approveForm">
    @csrf
    <div style="margin-bottom:16px">
      <label style="font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;display:block;margin-bottom:5px">Hotel Description <span style="color:#e31e24">*</span></label>
      <textarea name="description" rows="4" required placeholder="Write an appealing description..." style="width:100%;padding:10px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px;font-family:'Poppins',sans-serif;resize:vertical">{{ old('description') }}</textarea>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
      <div>
        <div style="font-size:13px;font-weight:800;color:#1a1612;margin-bottom:8px"><i class="ti ti-photo-star" style="color:#e31e24"></i> Hotel Main Image <span style="color:#e31e24">*</span></div>
        <div id="main-upload-area" class="rv-drop-zone" onclick="document.getElementById('main_image_input').click()">
          <i class="ti ti-cloud-upload" style="font-size:28px;color:#6b6560"></i>
          <div style="font-size:12px;font-weight:700;color:#1a1612;margin-top:4px">Upload cover photo</div>
          <div style="font-size:10px;color:#94a3b8;margin-top:2px">JPG/PNG · Max 10MB</div>
          <input type="file" name="main_image" id="main_image_input" accept="image/*" style="display:none" onchange="handleMainImage(this)">
        </div>
        <div id="main-preview" style="display:none;margin-top:8px">
          <div class="rv-img-preview-card">
            <img id="main-thumb" src="" class="rv-img-thumb-lg">
            <div style="flex:1;min-width:0"><div id="main-filename" class="rv-img-name"></div><div id="main-filesize" class="rv-img-size"></div></div>
            <div style="display:flex;gap:6px">
              <a id="main-view-btn" href="#" target="_blank" class="rv-img-btn rv-img-btn-view"><i class="ti ti-eye"></i></a>
              <button type="button" onclick="removeMainImage()" class="rv-img-btn rv-img-btn-del"><i class="ti ti-trash"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div style="font-size:13px;font-weight:800;color:#1a1612;margin-bottom:8px"><i class="ti ti-photos" style="color:#e31e24"></i> Gallery Images</div>
        <div id="gallery-upload-area" class="rv-drop-zone" onclick="document.getElementById('gallery_images_input').click()">
          <i class="ti ti-photo-plus" style="font-size:28px;color:#6b6560"></i>
          <div style="font-size:12px;font-weight:700;color:#1a1612;margin-top:4px">Select multiple images</div>
          <div style="font-size:10px;color:#94a3b8;margin-top:2px">Rooms, Lobby, Exterior · Max 10MB each</div>
          <input type="file" name="gallery_images[]" id="gallery_images_input" multiple accept="image/*" style="display:none" onchange="handleGallery(this)">
        </div>
        <div id="gallery-preview" style="display:none;margin-top:8px">
          <div id="gallery-count" style="font-size:11px;font-weight:700;color:#6b6560;margin-bottom:6px"></div>
          <div id="gallery-grid" class="rv-gallery-grid"></div>
          <div style="margin-top:8px;display:flex;gap:6px">
            <button type="button" onclick="document.getElementById('gallery_images_input').click()" class="rv-img-btn rv-img-btn-view"><i class="ti ti-plus"></i> Add</button>
            <button type="button" onclick="clearGallery()" class="rv-img-btn rv-img-btn-del"><i class="ti ti-trash"></i> Clear</button>
          </div>
        </div>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;margin-bottom:16px">
      <div><label style="font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;display:block;margin-bottom:5px">Latitude</label><input type="text" name="latitude" value="13.0827" style="width:100%;padding:8px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px"></div>
      <div><label style="font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;display:block;margin-bottom:5px">Longitude</label><input type="text" name="longitude" value="80.2707" style="width:100%;padding:8px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px"></div>
      <div><label style="font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;display:block;margin-bottom:5px">Stars</label><select name="stars" style="width:100%;padding:8px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px"><option value="3" selected>3</option><option value="2">2</option><option value="4">4</option><option value="5">5</option></select></div>
      <div><label style="font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;display:block;margin-bottom:5px">Commission %</label><input type="number" name="commission" value="10" min="0" max="100" style="width:100%;padding:8px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px"></div>
    </div>

    <div id="upload-progress-bar" style="display:none;margin-bottom:16px">
      <div style="font-size:12px;font-weight:700;color:#1a1612;margin-bottom:6px;display:flex;align-items:center;gap:6px"><span class="rv-spinner"></span> Uploading & creating property...</div>
      <div class="rv-progress-bar" style="height:6px"><div id="form-progress-fill" class="rv-progress-fill" style="width:0%"></div></div>
      <div id="form-progress-text" style="font-size:10px;color:#6b6560;margin-top:4px">Preparing...</div>
    </div>

    <button type="submit" class="btn btn-primary" onclick="return confirm('Approve and make live?')"><i class="ti ti-check"></i> Approve & Make Live</button>
  </form>

  <div style="border-top:1px solid #e8e2d9;margin-top:16px;padding-top:16px">
    <form method="POST" action="{{ route('admin.property_submissions.reject', $property->id) }}" style="display:flex;gap:10px;align-items:flex-end">
      @csrf
      <div style="flex:1"><label style="font-size:11px;font-weight:700;color:#6b6560;text-transform:uppercase;display:block;margin-bottom:5px">Rejection Reason</label><input type="text" name="rejection_reason" placeholder="e.g. Incomplete data..." style="width:100%;padding:8px;border:1.5px solid #e8e2d9;border-radius:8px;background:#f8f6f1;color:#1a1612;font-size:12px"></div>
      <button type="submit" class="btn btn-secondary" style="color:#dc2626" onclick="return confirm('Reject?')"><i class="ti ti-x"></i> Reject</button>
    </form>
  </div>
</div>
@endif

@if($property->status === 'live' && $property->hotel_id)
<div style="padding:12px 16px;background:rgba(5,150,105,.06);border:1px solid rgba(5,150,105,.2);border-radius:10px;font-size:12px;color:#059669;display:flex;align-items:center;gap:8px">
  <i class="ti ti-check"></i><strong>LIVE</strong> — Hotel #{{ $property->hotel_id }}, Room #{{ $property->room_id }}
  <a href="{{ url('admin/hotel-management/hotels') }}" style="margin-left:auto;color:#059669;font-weight:700">View →</a>
</div>
@endif

@if($property->status === 'rejected')
<div style="padding:12px 16px;background:rgba(220,38,38,.06);border:1px solid rgba(220,38,38,.2);border-radius:10px;font-size:12px;color:#dc2626">
  <strong>Rejected:</strong> {{ $property->rejection_reason ?? 'No reason' }}
</div>
@endif
@endsection

@section('script')
<script>
function handleMainImage(input){var f=input.files[0];if(!f)return;if(f.size>10*1024*1024){alert("Max 10MB");input.value="";return;}document.getElementById("main-upload-area").style.display="none";document.getElementById("main-preview").style.display="block";document.getElementById("main-filename").textContent=f.name;document.getElementById("main-filesize").textContent=fmtSz(f.size);var r=new FileReader();r.onload=function(e){document.getElementById("main-thumb").src=e.target.result;document.getElementById("main-view-btn").href=e.target.result;};r.readAsDataURL(f);}
function removeMainImage(){document.getElementById("main_image_input").value="";document.getElementById("main-preview").style.display="none";document.getElementById("main-upload-area").style.display="block";}
var gFiles=[];
function handleGallery(input){var fl=Array.from(input.files).filter(function(f){return f.size<=10*1024*1024&&f.type.startsWith("image/");});fl.forEach(function(f){gFiles.push(f);});renderG();document.getElementById("gallery-preview").style.display="block";document.getElementById("gallery-upload-area").style.display="none";}
function renderG(){var g=document.getElementById("gallery-grid");g.innerHTML="";document.getElementById("gallery-count").textContent=gFiles.length+" image"+(gFiles.length!==1?"s":"");gFiles.forEach(function(f,i){var d=document.createElement("div");d.className="rv-gallery-item";d.innerHTML='<div style="height:80px;background:#f0ebe4;display:flex;align-items:center;justify-content:center"><span class="rv-spinner"></span></div><div class="rv-gallery-item-bar"><span class="rv-gallery-item-name">'+f.name+'</span></div>';g.appendChild(d);var r=new FileReader();r.onload=(function(el,idx,file){return function(e){el.innerHTML='<img src="'+e.target.result+'"><div class="rv-gallery-item-bar"><span class="rv-gallery-item-name">'+file.name+'</span><div class="rv-gallery-item-actions"><a href="'+e.target.result+'" target="_blank"><i class="ti ti-eye"></i></a><button type="button" onclick="rmG('+idx+')"><i class="ti ti-x"></i></button></div></div>';};})(d,i,f);r.readAsDataURL(f);});syncG();}
function rmG(i){gFiles.splice(i,1);if(!gFiles.length){document.getElementById("gallery-preview").style.display="none";document.getElementById("gallery-upload-area").style.display="block";}renderG();}
function clearGallery(){gFiles=[];document.getElementById("gallery-preview").style.display="none";document.getElementById("gallery-upload-area").style.display="block";}
function syncG(){try{var dt=new DataTransfer();gFiles.forEach(function(f){dt.items.add(f);});document.getElementById("gallery_images_input").files=dt.files;}catch(e){}}
document.getElementById("approveForm").addEventListener("submit",function(){var b=document.getElementById("upload-progress-bar");b.style.display="block";var fl=document.getElementById("form-progress-fill"),tx=document.getElementById("form-progress-text"),p=0;setInterval(function(){p+=Math.random()*12;if(p>90)p=90;fl.style.width=p+"%";tx.textContent=p<30?"Uploading images...":p<60?"Creating records...":"Setting up inventory...";},500);});
function fmtSz(b){return b<1024?b+" B":b<1048576?(b/1024).toFixed(1)+" KB":(b/1048576).toFixed(1)+" MB";}
</script>
@endsection
