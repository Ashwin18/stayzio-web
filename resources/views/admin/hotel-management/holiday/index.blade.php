@extends('admin.layout')
@section('section','Property / Hotels')
@section('page','Holidays & Blackouts')

@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Holidays & Blackouts</h2><p>Block dates for specific vendors or all hotels</p></div>
  <div class="page-hdr-actions">
    <button onclick="document.getElementById('addHolidayModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> Add Holiday</button>
  </div>
</div>

{{-- Vendor filter --}}
<div class="card" style="margin-bottom:14px">
  <div class="card-body" style="padding:12px 16px">
    <form action="{{ route('admin.hotel_management.hotel.holiday') }}" method="GET" id="daySearch" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
      <input type="hidden" name="language" value="{{ $defaultLang->code }}">
      <div class="fg" style="min-width:220px">
        <label class="flabel">Filter by Vendor</label>
        <select name="vendor_id" class="fc" onchange="document.getElementById('daySearch').submit()">
          <option value="admin" @selected(request('vendor_id') == 'admin')>Admin (all)</option>
          @foreach($vendors as $v)
          <option value="{{ $v->id }}" @selected($v->id == request('vendor_id'))>{{ $v->username }}</option>
          @endforeach
        </select>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table>
      <thead><tr><th>#</th><th>Date</th><th>Hotel</th><th>Vendor</th><th>Reason</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($holidays ?? [] as $h)
        <tr>
          <td class="td-muted">{{ $loop->iteration }}</td>
          <td class="td-main">{{ \Carbon\Carbon::parse($h->date)->format('d M Y') }}</td>
          <td class="td-muted">{{ optional($h->hotel?->hotel_contents->first())->title ?? 'All Hotels' }}</td>
          <td class="td-muted">{{ $h->vendor?->username ?? 'Admin' }}</td>
          <td class="td-muted">{{ $h->note ?? '—' }}</td>
          <td>
            <form action="{{ route('admin.hotel_management.hotel.holiday.delete', $h->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Remove this holiday?')">
              @csrf
              <button class="btn btn-danger btn-xs"><i class="ti ti-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)"><i class="ti ti-calendar-off" style="font-size:32px;display:block;margin-bottom:10px"></i>No holidays / blackouts set</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add Holiday Modal --}}
<div id="addHolidayModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:999;align-items:center;justify-content:center">
  <div style="background:var(--navy2);border:1px solid var(--border);border-radius:12px;width:480px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
      <span style="font-weight:600">Add Holiday / Blackout Date</span>
      <button onclick="document.getElementById('addHolidayModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px"><i class="ti ti-x"></i></button>
    </div>
    <form action="{{ route('admin.hotel_management.hotel.holiday.store') }}" method="POST">
      @csrf
      <input type="hidden" name="language" value="{{ $defaultLang->code }}">
      <div style="padding:16px">
        <div class="form-row c2" style="margin-bottom:14px">
          <div class="fg"><label class="flabel">Vendor</label>
            <select name="vendor_id" class="fc">
              <option value="admin">Admin (all)</option>
              @foreach($vendors as $v)<option value="{{ $v->id }}">{{ $v->username }}</option>@endforeach
            </select>
          </div>
          <div class="fg"><label class="flabel">Hotel (optional)</label>
            <select name="hotel_id" class="fc"><option value="">All Hotels</option>
              @foreach($hotels ?? [] as $h)<option value="{{ $h->id }}">{{ optional($h->hotel_contents->first())->title }}</option>@endforeach
            </select>
          </div>
          <div class="fg"><label class="flabel">Date *</label><input type="date" name="date" class="fc" required></div>
          <div class="fg"><label class="flabel">Reason / Note</label><input type="text" name="note" class="fc" placeholder="e.g. National holiday"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-device-floppy"></i> Save Holiday</button>
      </div>
    </form>
  </div>
</div>
@endsection
