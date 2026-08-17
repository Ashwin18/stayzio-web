@extends('admin.layout')
@section('section','People')
@section('page','Push Notifications')
@section('content')
<div class="page-hdr">
  <div class="page-hdr-left"><h2>Push Notifications</h2><p>Broadcast messages to all customers</p></div>
  <div class="page-hdr-actions">
    <a href="{{ route('admin.user_management.push_notification.settings', ['language' => $defaultLang->code]) }}" class="btn btn-secondary"><i class="ti ti-settings"></i> FCM Settings</a>
  </div>
</div>
<div class="two-col-eq">
  <div class="card">
    <div class="card-hdr"><div class="card-title"><i class="ti ti-bell" style="color:var(--amber)"></i>  Send Notification</div></div>
    <div class="card-body">
      <form action="{{ route('admin.user_management.push_notification.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-row" style="margin-bottom:14px">
          <div class="fg"><label class="flabel">Title *</label><input type="text" name="title" class="fc" placeholder="Notification title" required>
            @error('title')<span class="fs11 text-red">{{ $message }}</span>@enderror
          </div>
          <div class="fg"><label class="flabel">Message</label><textarea name="message" class="fc" rows="4" placeholder="Write the notification message…"></textarea></div>
          <div class="fg"><label class="flabel">Button label *</label><input type="text" name="btn_name" class="fc" placeholder="e.g. Book Now" required></div>
          <div class="fg"><label class="flabel">Button URL *</label><input type="url" name="btn_url" class="fc" placeholder="https://…" required></div>
          <div class="fg"><label class="flabel">Icon image (optional)</label><input type="file" name="icon" class="fc" accept="image/*"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="ti ti-send"></i> Send to All Customers</button>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-hdr"><div class="card-title">Notification Tips</div></div>
    <div class="card-body">
      <div style="display:flex;flex-direction:column;gap:12px">
        <div style="background:var(--navy3);border-radius:8px;padding:12px">
          <div style="font-size:12px;font-weight:600;margin-bottom:6px;color:var(--amber)"><i class="ti ti-bulb"></i>  Keep it short</div>
          <div class="fs11 text-muted">Titles under 50 characters and messages under 150 characters perform best on mobile.</div>
        </div>
        <div style="background:var(--navy3);border-radius:8px;padding:12px">
          <div style="font-size:12px;font-weight:600;margin-bottom:6px;color:var(--green)"><i class="ti ti-clock"></i>  Best time to send</div>
          <div class="fs11 text-muted">Weekday evenings (6–9 PM IST) see 3× higher click-through rates for hotel promotions.</div>
        </div>
        <div style="background:var(--navy3);border-radius:8px;padding:12px">
          <div style="font-size:12px;font-weight:600;margin-bottom:6px;color:var(--blue)"><i class="ti ti-target"></i>  CTA matters</div>
          <div class="fs11 text-muted">Use action verbs: "Book Now", "Grab Offer", "Explore". Avoid "Click Here".</div>
        </div>
        <div style="background:var(--red-bg);border:1px solid rgba(227,30,36,.2);border-radius:8px;padding:12px">
          <div style="font-size:12px;font-weight:600;margin-bottom:6px;color:var(--red2)"><i class="ti ti-alert-triangle"></i>  FCM setup required</div>
          <div class="fs11 text-muted">Ensure your FCM Server Key is configured in <a href="{{ route('admin.user_management.push_notification.settings', ['language' => $defaultLang->code]) }}" style="color:var(--blue)">Push Notification Settings</a> before sending.</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
