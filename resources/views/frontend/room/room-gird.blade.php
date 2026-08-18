@extends('frontend.detaillayout')

@section('pageHeading')
@if (!empty($pageHeading->rooms_page_title))
{{ $pageHeading->rooms_page_title }}
@else
{{ __('Rooms') }}
@endif
@endsection

@section('metaKeywords')
@if (!empty($seoInfo))
{{ $seoInfo->meta_keyword_rooms }}
@endif
@endsection

@section('metaDescription')
@if (!empty($seoInfo))
{{ $seoInfo->meta_description_rooms }}
@endif
@endsection

@section('content')

<div class="main-layout">

 @include('frontend.room.side-bar')

<section class="results-panel">

  {{-- Single mobile filter toggle --}}
  <div class="mobile-filter-row">
    <button class="mobile-filter-btn" onclick="openMobileSidebar()" id="mobileFilterBtn" type="button">
      <i class="fas fa-sliders-h"></i>
      <span>Filters</span>
      @if(request()->has('couple_friendly') || request()->has('ratings') || request()->has('category') || request()->has('amenitie') || request()->has('filter_city') || request()->has('hour'))
        <span class="mobile-filter-count">!</span>
      @endif
    </button>
  </div>

  <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar()"></div>

  <div class="sort-bar">
        <div class="sort-left">
            <span class="sort-label">
                {{ __('Sort by:') }}
            </span>

            <a
                href="{{ request()->fullUrlWithQuery(['sort' => 'new']) }}"
                class="sort-btn {{ request()->sort == 'new' ? 'active' : '' }}"
            >
                {{ __('Newest') }}
            </a>

            <a
                href="{{ request()->fullUrlWithQuery(['sort' => 'old']) }}"
                class="sort-btn {{ request()->sort == 'old' ? 'active' : '' }}"
            >
                {{ __('Oldest') }}
            </a>

            <a
                href="{{ request()->fullUrlWithQuery(['sort' => 'starhigh']) }}"
                class="sort-btn {{ request()->sort == 'starhigh' ? 'active' : '' }}"
            >
                {{ __('Rating') }}
            </a>

            <a
                href="{{ request()->fullUrlWithQuery(['sort' => 'nearest']) }}"
                class="sort-btn {{ request()->sort == 'nearest' ? 'active' : '' }}"
            >
                {{ __('Distance') }}
            </a>
        </div>
    </div>

    <div class="hotels-list">
        @foreach($featured_contents as $room)
            @include('frontend.room.partials.room-card', [
                'room' => $room,
                'featured' => true
            ])
        @endforeach

        @foreach($room_contents as $room)
            @include('frontend.room.partials.room-card', [
                'room' => $room,
                'featured' => false
            ])
        @endforeach
    </div>

    <div class="pagination-wrapper mt-4">
        {{ $room_contents->links() }}
    </div>

</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resultsPanel = document.querySelector('.hotels-list');

    if (resultsPanel) {
        resultsPanel.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('hotel-card-thumb-img')) {
                const clickedThumb = e.target;
                const cardContainer = clickedThumb.closest('.hotel-card');
                const mainImg = cardContainer.querySelector('.hotel-card-main-img');

                if (mainImg) {
                    mainImg.src = clickedThumb.src;
                }
            }

            const clickedDtab = e.target.closest('.dtab');
            if (clickedDtab) {
                const tabsContainer = clickedDtab.closest('.duration-tabs');

                if (tabsContainer) {
                    tabsContainer.querySelectorAll('.dtab').forEach(tab => {
                        tab.classList.remove('active');
                    });

                    clickedDtab.classList.add('active');
                }
            }
        });
    }
});
</script>

<script>
function openMobileSidebar() {
  var sidebar = document.querySelector('.sidebar');
  var backdrop = document.getElementById('sidebarBackdrop');
  if (sidebar) sidebar.classList.add('open');
  if (backdrop) backdrop.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeMobileSidebar() {
  var sidebar = document.querySelector('.sidebar');
  var backdrop = document.getElementById('sidebarBackdrop');
  if (sidebar) sidebar.classList.remove('open');
  if (backdrop) backdrop.classList.remove('show');
  document.body.style.overflow = '';
}
</script>

<!-- Map JS -->
@if ($basicInfo->google_map_api_key_status == 1)
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ $basicInfo->google_map_api_key }}&libraries=places&callback=initMap"
  async defer></script>
<script src="{{ asset('assets/front/js/api-search-2.js') }}"></script>
@endif
<script src="{{ asset('assets/front/js/map-room.js') }}"></script>
<script>
"use strict";
var featured_contents = {!! json_encode($featured_contents) !!};
var room_contents = {!! json_encode($room_contents) !!};
var searchUrl = "{{ route('frontend.search_room') }}";
var getStateUrl = "{{ route('frontend.hotels.get-state') }}";
var getCityUrl = "{{ route('frontend.hotels.get-city') }}";
var getAddress = "{{ route('frontend.rooms.get-address') }}";
</script>
<script src="{{ asset('assets/front/js/room-search.js') }}"></script>
@endsection
