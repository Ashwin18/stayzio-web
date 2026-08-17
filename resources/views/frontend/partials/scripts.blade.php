<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/front/js/vendors/bootstrap.min.js') }}"></script>
<!-- Dependencies needed for script.js to function -->

<script src="{{ asset('assets/front/js/vendors/datatables.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/nouislider.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/select2.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/lazysizes.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/svg-loader.min.js') }}"></script>
<script src="{{ asset('assets/front/js/floating-whatsapp.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/aos.min.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/mouse-hover-move.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/leaflet.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/leaflet.markercluster.js') }}"></script>
<script src="{{ asset('assets/front/js/vendors/leaflet.fullscreen.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/front/js/jquery-syotimer.min.js') }}"></script>
<script src="{{ asset('assets/front/js/push-notification.js') }}"></script>
<script src="{{ asset('assets/front/js/script.js') }}?v=1367"></script>
<script src="{{ asset('assets/front/js/main.js') }}"></script>
<script src="{{ asset('stayzio/js/script.js') }}?v=17182035"></script>
<script src="{{ asset('stayzio/js/user_script.js') }}?v=2002"></script>

<script>
  'use strict';
  const baseURL = "{{ url('/') }}";
  const read_more = "Read More";
  const read_less = "Read Less";
  const show_more = "{{ __('Show More') . '+' }}";
  const show_less = "{{ __('Show Less') . '-' }}";
  var vapid_public_key = "{!! env('VAPID_PUBLIC_KEY') !!}";
  var googleApiStatus = {{ $basicInfo->google_map_api_key_status }};
  @if ($basicInfo->time_format == 24)
    var timePicker = true;
    var timeFormate = "HH:mm";
  @elseif ($basicInfo->time_format == 12)
    var timePicker = false;
    var timeFormate = "hh:mm A";
  @endif
</script>

<script>
(function () {
    function hideStayzioLoader() {
        var loader = document.getElementById('stayzio-loader');
        if (loader) {
            loader.classList.add('hide');
            setTimeout(function () {
                loader.style.display = 'none';
            }, 450);
        }
    }

    window.addEventListener('load', function () {
        setTimeout(hideStayzioLoader, 450);
    });

    setTimeout(hideStayzioLoader, 1800);
})();
</script>



@yield('script')