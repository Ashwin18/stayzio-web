@extends('vendors.layout')
@section('section','Support')
@section('page','New Ticket')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Create Support Ticket</h2></div>
  <div class="page-hdr-actions">
    <a href="{{ route('vendor.support_tickets') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
  </div>
</div>
<div style="max-width:700px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('vendor.support_ticket.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Subject *</label>
          <input type="text" name="subject" class="fc" placeholder="Brief summary of your issue" required value="{{ old('subject') }}">
          @error('subject')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
        </div>
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Description *</label>
          <textarea name="description" class="fc" rows="5" placeholder="Describe your issue in detail…" required>{{ old('description') }}</textarea>
          @error('description')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
        </div>
        <div class="fg" style="margin-bottom:16px">
          <label class="flabel">Attachment (ZIP only, optional)</label>
          <input type="file" name="file" class="fc" accept=".zip">
          @error('file')<span style="font-size:11px;color:#dc2626">{{ $message }}</span>@enderror
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-send"></i> Submit Ticket</button>
      </form>
    </div>
  </div>
</div>
@endsection
