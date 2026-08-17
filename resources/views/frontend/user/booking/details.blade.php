@extends('frontend.layout')
@section('pageHeading'){{ __('Booking Details') }}@endsection
@section('content')
@php
  $b   = $bookingInfo;
  $sym = isset($basic) && $basic ? ($basic->base_currency_symbol ?? '₹') : '₹';
  $pos = isset($basic) && $basic ? ($basic->base_currency_symbol_position ?? 'left') : 'left';
  $fmt = fn($v) => $pos=='left' ? $sym.number_format($v,2) : number_format($v,2).$sym;
  $hourVal = $b->hour ?? '';
  $isFullDay = ($hourVal == 'Full Day' || $hourVal == '24' || intval($hourVal) >= 24);
  $checkInDt   = $b->check_in_date  ? \Carbon\Carbon::parse($b->check_in_date) : null;
  $checkOutDt  = $b->check_out_date ? \Carbon\Carbon::parse($b->check_out_date) : null;
  $checkInTime = $b->check_in_time  ? \Carbon\Carbon::parse($b->check_in_time)->format('h:i A') : '—';
  $checkOutTime= $b->check_out_time ? \Carbon\Carbon::parse($b->check_out_time)->format('h:i A') : '—';
  $bookedAt    = \Carbon\Carbon::parse($b->created_at);
  $nights      = ($isFullDay && $checkInDt && $checkOutDt) ? $checkInDt->diffInDays($checkOutDt) : 0;
  if ($b->payment_status==1) { $stColor='#16a34a'; $stLabel='Paid'; }
  elseif ($b->payment_status==2) { $stColor='#e31e24'; $stLabel='Rejected'; }
  else { $stColor='#d97706'; $stLabel='Pending'; }
  $services     = json_decode($b->service_details ?? '[]', true) ?? [];
  $serviceTotal = is_array($services) ? collect($services)->sum('price') : 0;
  $roomPrice    = max(0, $b->grand_total - ($b->tax??0) - $serviceTotal + ($b->discount??0));
  $hotelObj    = isset($hotel) ? $hotel : null;
  $hotelContentObj = isset($hotelContent) ? $hotelContent : null;
  $hotelLogo   = $hotelObj ? ($hotelObj->logo ?? null) : null;
  $hotelImage  = $hotelLogo ? asset('assets/img/hotel/logo/'.$hotelLogo) : null;
  $hotelName   = $hotelContentObj ? ($hotelContentObj->title ?? 'Hotel') : 'Hotel';
  $hotelAddr   = $hotelContentObj ? ($hotelContentObj->address ?? '') : '';
  $hotelRating = $hotelObj ? ($hotelObj->average_rating ?? 0) : 0;
@endphp
@include('frontend.user.booking.booking-details-styles-v2')
@include('frontend.user.booking.booking-details-body-v2')
@endsection
