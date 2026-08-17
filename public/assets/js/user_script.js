
/* ── Section switcher ── */
const sections = ['profile', 'bookings', 'wishlist', 'wallet', 'referral', 'help'];
function showSection(name, navEl) {
    sections.forEach(s => {
        const el = document.getElementById('sec-' + s);
        if (el) el.style.display = s === name ? '' : 'none';
    });
    document.querySelectorAll('.pc-nav-item').forEach(n => n.classList.remove('active'));
    if (navEl) navEl.classList.add('active');
}

/* ── Edit field ── */
function editField(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.removeAttribute('readonly');
    el.focus();
    el.select();
}

/* ── Copy referral code ── */
function copyReferral() {
    navigator.clipboard?.writeText('ASHW24F2B7').catch(() => { });
    showToast('Referral code copied!');
}

/* ── Toast ── */
function showToast(msg) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

/* ── Save profile ── */
function saveProfile() {
    showToast('Profile saved successfully!');
}

/* ── Add & Earn focus ── */
function focusEarn(id) {
    const el = document.getElementById(id);
    if (el) { el.focus(); }
}

/* ── Logout confirm ── */
function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        showToast('Logged out. Redirecting…');
        setTimeout(() => window.location.href = 'index.html', 1500);
    }
}

/* ── User menu (stub) ── */
function toggleUserMenu() {
    showToast('View profile options');
}

function toggleMenu() {
    document.getElementById("mobileMenu").classList.toggle("active");
}






document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("menuBtn");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.querySelector(".sidebar-overlay");

    if (!menuBtn || !sidebar || !overlay) {
        console.error("Sidebar elements missing");
        return;
    }

    // OPEN / CLOSE
    menuBtn.addEventListener("click", function () {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
        document.body.classList.toggle("sidebar-open");
    });

    // CLOSE on overlay click
    overlay.addEventListener("click", function () {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
        document.body.classList.remove("sidebar-open");
    });

    // close x on menu click
    const closeBtn = document.getElementById("closeSidebar");

    closeBtn.addEventListener("click", function () {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
        document.body.classList.remove("sidebar-open");
    });

});