@extends('vendors.layout')
@section('section','Bookings')
@section('page','Calendar View')
@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
@endsection
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Booking Calendar</h2><p>Visual overview of all bookings by date</p></div>
  <div class="page-hdr-actions">
    <div class="fg" style="flex-direction:row;align-items:center;gap:8px">
      <label class="flabel" style="white-space:nowrap">Filter room</label>
      <select id="roomFilter" class="fc" style="width:200px" onchange="loadCalendar()">
        <option value="">All Rooms</option>
        @foreach($rooms??[] as $r)
        @php $rc = $r->room_content->first(); @endphp
        <option value="{{ $r->id }}">{{ optional($rc)->name??'Room #'.$r->id }}</option>
        @endforeach
      </select>
    </div>
    <a href="{{ route('vendor.room_bookings.all_bookings',['language'=>$defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-list"></i> List View</a>
    <button onclick="document.getElementById('newBookingModal').style.display='flex'" class="btn btn-primary"><i class="ti ti-plus"></i> New Booking</button>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:10px">
  <span style="display:flex;align-items:center;gap:5px;font-size:11px"><span style="width:10px;height:10px;border-radius:2px;background:#6ee7b7;display:inline-block"></span>Paid</span>
  <span style="display:flex;align-items:center;gap:5px;font-size:11px"><span style="width:10px;height:10px;border-radius:2px;background:var(--amber);display:inline-block"></span>Pending</span>
  <span style="display:flex;align-items:center;gap:5px;font-size:11px"><span style="width:10px;height:10px;border-radius:2px;background:#f87171;display:inline-block"></span>Rejected</span>
</div>

<div class="card">
  <div class="card-body" style="padding:16px">
    <div id="vendor-calendar" style="color:var(--text)"></div>
  </div>
</div>

{{-- Event detail modal --}}
<div id="calModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--white);border:1px solid var(--border);border-radius:12px;width:420px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
      <span style="font-weight:600">Booking Details</span>
      <button onclick="document.getElementById('calModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <div style="padding:16px" id="calModalBody"></div>
  </div>
</div>

{{-- Room selection modal for new booking --}}
<div id="newBookingModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center">
  <div style="background:var(--white);border:1px solid var(--border);border-radius:12px;width:420px">
    <div style="padding:14px 16px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
      <span style="font-weight:600">Select a Room to Book</span>
      <button onclick="document.getElementById('newBookingModal').style.display='none'" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px">&times;</button>
    </div>
    <div style="padding:16px">
      <form action="{{ route('vendor.room_bookings.booking_form') }}" method="GET">
        <input type="hidden" name="language" value="{{ $defaultLang->code }}">
        <div class="fg" style="margin-bottom:14px">
          <label class="flabel">Room *</label>
          <select name="room_id" class="fc" required>
            <option value="">Select a room…</option>
            @foreach($rooms??[] as $r)
            @php $rc = $r->room_content->first(); @endphp
            <option value="{{ $r->id }}">{{ optional($rc)->name ?? 'Room #'.$r->id }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-calendar-plus"></i> Continue to Booking</button>
      </form>
    </div>
  </div>
</div>

@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
var calInstance = null;
function loadCalendar(){
  var roomId = document.getElementById('roomFilter').value;
  var url = '{{ route("vendor.room_bookings.calendar_data") }}?language={{ $defaultLang->code }}' + (roomId ? '&room_id='+roomId : '');

  fetch(url,{headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})
    .then(function(r){return r.json();})
    .then(function(data){
      if(calInstance) calInstance.destroy();
      var events = [];
      (data||[]).forEach(function(b){
        var color = b.payment_status==1 ? '#6ee7b7' : (b.payment_status==2 ? '#f87171' : '#f59e0b');
        events.push({
          id: b.id,
          title: (b.room_name||'Room') + ' — ' + (b.booking_name||'Guest'),
          start: b.check_in_date_time,
          end: b.check_out_date_time,
          backgroundColor: color,
          borderColor: color,
          textColor: '#0f172a',
          extendedProps: b
        });
      });
      calInstance = new FullCalendar.Calendar(document.getElementById('vendor-calendar'),{
        initialView:'dayGridMonth',
        headerToolbar:{left:'prev,next today',center:'title',right:'dayGridMonth,timeGridWeek,listWeek'},
        height:600,
        events:events,
        eventClick:function(info){
          var p=info.event.extendedProps;
          document.getElementById('calModalBody').innerHTML=
            '<div class="stat-row"><span class="stat-lbl">Guest</span><span class="stat-val">'+(p.booking_name||'—')+'</span></div>'+
            '<div class="stat-row"><span class="stat-lbl">Room</span><span class="stat-val">'+(p.room_name||p.title||'—')+'</span></div>'+
            '<div class="stat-row"><span class="stat-lbl">Check-in</span><span class="stat-val">'+(p.check_in_date||p.start||'—')+'</span></div>'+
            '<div class="stat-row"><span class="stat-lbl">Duration</span><span class="stat-val">'+(p.hour||'—')+' hr</span></div>'+
            '<div class="stat-row"><span class="stat-lbl">Amount</span><span class="stat-val" style="color:var(--green)">{{ $currencyInfo->base_currency_symbol ?? "₹" }}'+(p.grand_total||'—')+'</span></div>'+
            '<div class="stat-row"><span class="stat-lbl">Status</span><span class="stat-val">'+(p.payment_status==1?'<span class="badge green">Paid</span>':(p.payment_status==2?'<span class="badge red">Rejected</span>':'<span class="badge amber">Pending</span>'))+'</span></div>';
          document.getElementById('calModal').style.display='flex';
        },
        eventDisplay:'block',
        dayMaxEvents:4,
        views:{timeGridWeek:{slotMinTime:'06:00:00',slotMaxTime:'24:00:00'}}
      });
      calInstance.render();
      document.querySelectorAll('.fc-toolbar-title,.fc-col-header-cell-cushion,.fc-daygrid-day-number').forEach(function(el){el.style.color='var(--text)';});
    });
}
loadCalendar();
</script>
<style>
.fc{color:var(--text)!important;background:transparent!important}
.fc-theme-standard td,.fc-theme-standard th,.fc-theme-standard .fc-scrollgrid{border-color:var(--border)!important}
.fc-button{background:var(--surface)!important;border-color:var(--border)!important;color:var(--muted)!important}
.fc-button-active,.fc-button:hover{background:var(--navy4)!important;color:var(--text)!important}
.fc-daygrid-day-number,.fc-col-header-cell-cushion,.fc-toolbar-title{color:var(--text)!important}
.fc-daygrid-day.fc-day-today{background:#eff6ff!important}
.fc-event{cursor:pointer;font-size:10.5px!important;padding:1px 4px!important}
.fc-list-event-title,.fc-list-event-time{color:var(--text)!important}
.fc-list-day-cushion{background:var(--surface)!important}
</style>
@endsection
