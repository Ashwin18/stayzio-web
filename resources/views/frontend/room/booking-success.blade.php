@extends('frontend.layout')
@section('pageHeading'){{ __('Booking Confirmed') }}@endsection
@section('content')
@include('frontend.room.booking-success-styles')
@include('frontend.room.booking-success-styles2')
@php
  $sym  = $basic->base_currency_symbol ?? '₹';
  $pos  = $basic->base_currency_symbol_position ?? 'left';
  $fmt  = fn($v) => $pos=='left' ? $sym.number_format($v,2) : number_format($v,2).$sym;
  $b    = $bookingInfo;
  $hour = $b->hour ?? '—';
  $checkInFmt  = $b->check_in_date  ? \Carbon\Carbon::parse($b->check_in_date)->format('D, d M Y')  : '—';
  $checkOutFmt = $b->check_out_date ? \Carbon\Carbon::parse($b->check_out_date)->format('D, d M Y') : $checkInFmt;
  $checkInTime = $b->check_in_time  ? \Carbon\Carbon::parse($b->check_in_time)->format('h:i A')     : '—';
  $checkOutTime= $b->check_out_time ? \Carbon\Carbon::parse($b->check_out_time)->format('h:i A')    : '—';
  $hotelImg    = $b->room && $b->room->feature_image
                  ? asset('assets/img/room/featureImage/'.$b->room->feature_image)
                  : null;
  $hotelTitle  = optional($hotelContent)->title ?? 'Hotel';
  $cityName    = optional($cities)->name ?? '';
  $countryName = optional($countries)->name ?? '';
  $services    = json_decode($b->service_details ?? '[]', true) ?? [];
  $serviceTotal= collect($services)->sum('price') ?? 0;
  $roomPrice   = max(0, $b->grand_total - ($b->tax ?? 0) - $serviceTotal + ($b->discount ?? 0));
@endphp
@include('frontend.room.booking-success-body')
<script src="{{ asset('assets/front/js/booking-confirm.js') }}"></script>
@endsection
