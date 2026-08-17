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

{{-- Mobile filter toggle --}}
<div class="d-lg-none mb-3" style="padding:0 1rem">
  <button onclick="document.getElementById('sidebar').classList.add('open')" style="padding:8px 16px;background:#e31e24;color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer" id="szMobileFilter">
    <i class="fas fa-sliders-h"></i> Filters
  </button>
</div>

<section class="results-panel">

  {{-- Mobile filter toggle (visible only on mobile via CSS) --}}
  <div style="display:none" class="mobile-filter-row">
    <button class="mobile-filter-btn" onclick="openMobileSidebar()" id="mobileFilterBtn">
      <i class="fas fa-sliders-h" style="color:#e31e24"></i>
      <span>Filters</span>
      @if(request()->has('couple_friendly') || request()->has('ratings') || request()->has('category') || request()->has('amenitie'))
        <span style="background:#e31e24;color:#fff;border-radius:50%;width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;font-size:10px;font-weight:700">!</span>
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
    // Target the main container that holds all the hotel cards
    const resultsPanel = document.querySelector('.hotels-list');

    if (resultsPanel) {
        resultsPanel.addEventListener('click', function(e) {
            
            // --- HANDLE GALLERY IMAGE SWAP ---
            if (e.target && e.target.classList.contains('hotel-card-thumb-img')) {
                const clickedThumb = e.target;
                const cardContainer = clickedThumb.closest('.hotel-card');
                const mainImg = cardContainer.querySelector('.hotel-card-main-img');
                
                if (mainImg) {
                    mainImg.src = clickedThumb.src;
                }
            }

            // --- HANDLE HOUR TAB ACTIVATION ---
            const clickedDtab = e.target.closest('.dtab');
            if (clickedDtab) {
                // Find the specific tabs container for this card only
                const tabsContainer = clickedDtab.closest('.duration-tabs');
                
                if (tabsContainer) {
                    // Remove 'active' class from all tabs inside this specific card
                    tabsContainer.querySelectorAll('.dtab').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    // Add 'active' class to the clicked tab
                    clickedDtab.classList.add('active');
                }
            }

        });
    }
});
</script>
<!-- Mobile sidebar toggle -->
<script>
function openMobileSidebar() {
  document.querySelector('.sidebar').classList.add('open');
  document.getElementById('sidebarBackdrop').classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeMobileSidebar() {
  document.querySelector('.sidebar').classList.remove('open');
  document.getElementById('sidebarBackdrop').classList.remove('show');
  document.body.style.overflow = '';
}
// Show mobile filter button on mobile
(function(){
  if (window.innerWidth <= 768) {
    var el = document.querySelector('.mobile-filter-row');
    if (el) el.style.display = 'block';
  }
})();
window.addEventListener('resize', function(){
  var el = document.querySelector('.mobile-filter-row');
  if (el) el.style.display = window.innerWidth <= 768 ? 'block' : 'none';
});
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
