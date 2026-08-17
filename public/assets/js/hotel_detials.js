
/* ── Gallery photos ── */
const photos = [
    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=85',
    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=85',
    'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=85',
    'https://images.unsplash.com/photo-1522798514-97ceb8c4f1c8?auto=format&fit=crop&w=1200&q=85',
    'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1200&q=85',
];

let curPhoto = 0;

function openGallery(idx) {
    curPhoto = idx;
    document.getElementById('galleryModal').classList.add('open');
    document.body.style.overflow = 'hidden';
    renderModal();
}

function closeGallery() {
    document.getElementById('galleryModal').classList.remove('open');
    document.body.style.overflow = '';
}

function changePhoto(dir) {
    curPhoto = (curPhoto + dir + photos.length) % photos.length;
    renderModal();
}

function renderModal() {
    document.getElementById('gmMainImg').src = photos[curPhoto];
    document.getElementById('gmCount').textContent = `Photo ${curPhoto + 1} of ${photos.length}`;
    const thumbsEl = document.getElementById('gmThumbs');
    thumbsEl.innerHTML = photos.map((src, i) =>
        `<div class="gm-thumb${i === curPhoto ? ' active' : ''}" onclick="curPhoto=${i};renderModal()">
      <img src="${src}" alt="">
    </div>`
    ).join('');
}

// Keyboard nav
document.addEventListener('keydown', e => {
    if (!document.getElementById('galleryModal').classList.contains('open')) return;
    if (e.key === 'ArrowLeft') changePhoto(-1);
    if (e.key === 'ArrowRight') changePhoto(1);
    if (e.key === 'Escape') closeGallery();
});

/* ── Wishlist ── */
let wished = false;
function toggleWish() {
    wished = !wished;
    ['wishBtn', 'wishBtn2'].forEach(id => {
        const btn = document.getElementById(id);
        if (!btn) return;
        btn.classList.toggle('liked', wished);
        btn.classList.toggle('active', wished);
        btn.querySelector('i').className = wished ? 'fas fa-heart' : 'far fa-heart';
    });
}

/* ── Share ── */
function shareHotel() {
    if (navigator.share) {
        navigator.share({ title: 'Hotel Marina Bay, Mumbai', url: location.href });
    } else {
        navigator.clipboard?.writeText(location.href);
        alert('Link copied to clipboard!');
    }
}

/* ── Duration widget ── */
function pickDur(el, price) {
    document.querySelectorAll('.bw-dt').forEach(d => d.classList.remove('on'));
    el.classList.add('on');
    const fmt = n => Number(n).toLocaleString('en-IN');
    const activeLabel = el.querySelector('.bw-dt-lbl')?.textContent?.trim() || 'Slot';
    document.getElementById('widPrice').textContent = '₹' + fmt(price);
    document.getElementById('ctaPrice').textContent = fmt(price);
    const mobilePrice = document.getElementById('mobilePrice');
    const mobileDuration = document.getElementById('mobileDuration');
    if (mobilePrice) mobilePrice.textContent = '₹' + fmt(price);
    if (mobileDuration) mobileDuration.textContent = '/ ' + activeLabel.replace('Hrs','Hrs').replace('Full Day','Full Day');
}

function openMobileBookingPanel() {
    const widget = document.getElementById('bookingWidget');
    if (!widget) return;
    if (window.innerWidth <= 768) {
        widget.classList.remove('mobile-collapsed');
        document.body.classList.add('booking-panel-open');
    }
}

function closeMobileBookingPanel() {
    const widget = document.getElementById('bookingWidget');
    if (!widget) return;
    if (window.innerWidth <= 768) {
        widget.classList.add('mobile-collapsed');
        document.body.classList.remove('booking-panel-open');
    }
}

function toggleMobileBooking() {
    const widget = document.getElementById('bookingWidget');
    if (!widget) return;
    if (window.innerWidth <= 768 && widget.classList.contains('mobile-collapsed')) openMobileBookingPanel();
    else closeMobileBookingPanel();
}

function setMobileBookingInitialState() {
    const widget = document.getElementById('bookingWidget');
    if (!widget) return;
    if (window.innerWidth <= 768) {
        widget.classList.add('mobile-collapsed');
        document.body.classList.remove('booking-panel-open');
    } else {
        widget.classList.remove('mobile-collapsed');
        document.body.classList.remove('booking-panel-open');
    }
}
window.addEventListener('DOMContentLoaded', setMobileBookingInitialState);
window.addEventListener('load', setMobileBookingInitialState);
window.addEventListener('resize', setMobileBookingInitialState);

/* ── Tab navigation ── */
const sections = [
    { id: 'basicInfo', tab: 0 },
    { id: 'amenities', tab: 1 },
    { id: 'perks', tab: 2 },
    { id: 'policies', tab: 3 },
    { id: 'restrictions', tab: 4 },
];

function tabScroll(id, el) {
    const target = document.getElementById(id);
    if (target) {
        const offset = window.innerWidth <= 768 ? 118 : 64 + 46;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
    }
    document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

window.addEventListener('scroll', () => {
    const tabs = document.querySelectorAll('.tab-item');
    for (let i = sections.length - 1; i >= 0; i--) {
        const el = document.getElementById(sections[i].id);
        if (el && el.getBoundingClientRect().top <= 160) {
            tabs.forEach(t => t.classList.remove('active'));
            tabs[sections[i].tab]?.classList.add('active');
            break;
        }
    }
});

/* ── Amenities toggle ── */
function toggleAmenities() {
    const btn = document.getElementById('amBtn');
    btn.innerHTML = btn.textContent.includes('View all')
        ? '<i class="fas fa-chevron-up" style="font-size:.65rem;"></i> Show less'
        : '<i class="fas fa-chevron-down" style="font-size:.65rem;"></i> View all amenities (24)';
}


/* Final mobile menu + booking handlers */
function toggleSiteMenu(){document.getElementById('siteMobileMenu')?.classList.toggle('open');document.getElementById('siteMenuBackdrop')?.classList.toggle('open');document.body.classList.toggle('site-menu-open');}
function closeSiteMenu(){document.getElementById('siteMobileMenu')?.classList.remove('open');document.getElementById('siteMenuBackdrop')?.classList.remove('open');document.body.classList.remove('site-menu-open');}
function handleMobileBookNow(e){if(window.innerWidth<=768){e&&e.preventDefault();toggleMobileBooking();return false;} window.location.href='hotel-booking.html';}
document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeSiteMenu();closeMobileBookingPanel&&closeMobileBookingPanel();}});

/* ===== Add-ons total calculation ===== */
let selectedRoomPrice = 2499;
function formatINRValue(n){ return Number(n || 0).toLocaleString('en-IN'); }
function updateBookingTotal(){
    const addonChecks = document.querySelectorAll('.addon-check');
    let addonTotal = 0;
    addonChecks.forEach(chk => { if (chk.checked) addonTotal += Number(chk.dataset.price || 0); });
    const total = selectedRoomPrice + addonTotal;
    const roomEl = document.getElementById('summaryRoomPrice');
    const addonEl = document.getElementById('summaryAddonPrice');
    const totalEl = document.getElementById('summaryTotalPrice');
    const ctaEl = document.getElementById('ctaPrice');
    if(roomEl) roomEl.textContent = formatINRValue(selectedRoomPrice);
    if(addonEl) addonEl.textContent = formatINRValue(addonTotal);
    if(totalEl) totalEl.textContent = formatINRValue(total);
    if(ctaEl) ctaEl.textContent = formatINRValue(total);
}

// Patch existing duration picker to include add-on total in final CTA
const originalPickDurForAddons = window.pickDur;
window.pickDur = function(el, price){
    selectedRoomPrice = Number(price || 0);
    if (typeof originalPickDurForAddons === 'function') originalPickDurForAddons(el, price);
    updateBookingTotal();
};

document.addEventListener('DOMContentLoaded', updateBookingTotal);
window.addEventListener('load', updateBookingTotal);

/* =========================================================
   FINAL ROBUST MOBILE BOOKING PANEL HANDLER
========================================================= */
(function(){
  function widget(){ return document.getElementById('bookingWidget'); }
  window.openMobileBookingPanel = function(){
    const w = widget(); if(!w) return false;
    if(window.innerWidth <= 768){
      w.classList.remove('mobile-collapsed');
      document.body.classList.add('booking-panel-open');
      setTimeout(()=>{ try{ w.scrollTo({top:0,behavior:'smooth'}); }catch(e){} },40);
      return false;
    }
    return true;
  };
  window.closeMobileBookingPanel = function(){
    const w = widget(); if(!w) return false;
    if(window.innerWidth <= 768){
      w.classList.add('mobile-collapsed');
      document.body.classList.remove('booking-panel-open');
      return false;
    }
    return true;
  };
  window.toggleMobileBooking = function(){
    const w = widget(); if(!w) return false;
    if(window.innerWidth <= 768){
      if(w.classList.contains('mobile-collapsed')) return window.openMobileBookingPanel();
      return window.closeMobileBookingPanel();
    }
    return true;
  };
  window.handleMobileBookNow = function(e){
    if(window.innerWidth <= 768){ if(e) e.preventDefault(); return window.openMobileBookingPanel(); }
    window.location.href='hotel-booking.html';
    return true;
  };
  function setState(){
    const w = widget(); if(!w) return;
    if(window.innerWidth <= 768){
      w.classList.add('mobile-collapsed');
      document.body.classList.remove('booking-panel-open');
    }else{
      w.classList.remove('mobile-collapsed');
      document.body.classList.remove('booking-panel-open');
    }
  }
  document.addEventListener('DOMContentLoaded', function(){
    setState();
    const w = widget();
    const openBtn = document.getElementById('mobileBookOpenBtn');
    const toggle = w ? w.querySelector('.mobile-slot-toggle') : null;
    if(openBtn) openBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); window.openMobileBookingPanel(); });
    if(toggle) toggle.addEventListener('click', function(e){ e.preventDefault(); window.toggleMobileBooking(); });
    document.addEventListener('click', function(e){
      if(document.body.classList.contains('booking-panel-open') && w && !w.contains(e.target) && window.innerWidth <= 768){ window.closeMobileBookingPanel(); }
    });
  });
  window.addEventListener('load', setState);
  window.addEventListener('orientationchange', function(){ setTimeout(setState, 250); });
})();
