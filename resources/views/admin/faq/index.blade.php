@extends('admin.layout')
@section('section','CMS / Pages')
@section('page','FAQs')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>FAQs</h2><p>Frequently asked questions</p></div>
  <div class="page-hdr-actions">
    <button class="btn btn-danger btn-sm bulk-delete d-none" data-href="{{ route('admin.pages.faq.bulk_delete') }}"><i class="ti ti-trash"></i> Delete</button>
    <button onclick="document.getElementById('createFaqModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> Add FAQ</button>
  </div>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox" onchange="document.querySelectorAll('.rc').forEach(c=>c.checked=this.checked)"></th>
          <th>#</th><th>Question</th><th>Serial</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($faqs as $faq)
        @php $fc = $faq->faq_contents->where('language_id', $defaultLang->id)->first() ?? $faq->faq_contents->first(); @endphp
        <tr>
          <td><input type="checkbox" class="rc bulk-check" data-val="{{ $faq->id }}"></td>
          <td class="td-muted">{{ $faq->id }}</td>
          <td>
            <div class="td-main">{{ Str::limit($fc->question ?? '—', 80) }}</div>
            <div class="td-sub">{{ Str::limit(strip_tags($fc->answer ?? ''), 100) }}</div>
          </td>
          <td class="td-muted">{{ $faq->serial_number }}</td>
          <td>@if($faq->status == 1)<span class="badge green">Active</span>@else<span class="badge red">Inactive</span>@endif</td>
          <td>
            <div style="display:flex;gap:6px">
              <button class="btn btn-secondary btn-xs editFaqBtn"
                data-id="{{ $faq->id }}"
                data-question="{{ $fc->question ?? '' }}"
                data-answer="{{ $fc->answer ?? '' }}"
                data-status="{{ $faq->status }}"
                data-serial="{{ $faq->serial_number }}"
                onclick="openFaqEdit(this)">
                <i class="ti ti-edit"></i>
              </button>
              <form action="{{ route('admin.pages.faq.delete', $faq->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this FAQ?')">@csrf<button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button></form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-help" style="font-size:32px;display:block;margin-bottom:10px"></i>No FAQs found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Create Modal --}}
<div id="createFaqModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:520px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-weight:600">Add FAQ</span>
      <button onclick="document.getElementById('createFaqModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.faq.create')</div>
  </div>
</div>

{{-- Edit Modal --}}
<div id="editFaqModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:520px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-weight:600">Edit FAQ</span>
      <button onclick="document.getElementById('editFaqModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <div style="padding:16px">@include('admin.faq.edit')</div>
  </div>
</div>
@endsection

@section('script')
<script>
function openFaqEdit(btn) {
  var modal = document.getElementById('editFaqModal');
  modal.style.display = 'flex';
  if(modal.querySelector('[name="id"]'))       modal.querySelector('[name="id"]').value       = btn.dataset.id;
  if(modal.querySelector('[name="question"]')) modal.querySelector('[name="question"]').value = btn.dataset.question;
  if(modal.querySelector('[name="answer"]'))   modal.querySelector('[name="answer"]').value   = btn.dataset.answer;
  if(modal.querySelector('[name="status"]'))   modal.querySelector('[name="status"]').value   = btn.dataset.status;
  if(modal.querySelector('[name="serial_number"]')) modal.querySelector('[name="serial_number"]').value = btn.dataset.serial;
}
</script>
@endsection
