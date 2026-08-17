/* ── Stayzio User Script Scope Isolation ── */
(function (global) {
    "use strict";

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

    /* ── Toast (Safely checks for elements before manipulating text) ── */
    function showToast(msg) {
        const t = document.getElementById('toast');
        const tMsg = document.getElementById('toastMsg');
        
        // Fallback if elements don't exist in the layout HTML template
        if (!t || !tMsg) {
            console.log("Toast Notification: " + msg);
            return;
        }

        tMsg.textContent = msg;
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

    /* ── Logout confirm (Safe submission method using Laravel's expected POST handler) ── */
    function confirmLogout() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = window.location.origin + '/user/logout';
        }
    }

    /* ── User menu (stub) ── */
    function toggleUserMenu() {
        showToast('View profile options');
    }

    function toggleMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        if (mobileMenu) {
            mobileMenu.classList.toggle("active");
        }
    }

    /* ── DOM Events Loader ── */
    document.addEventListener("DOMContentLoaded", function () {

        const menuBtn = document.getElementById("menuBtn");
        const sidebar = document.getElementById("sidebar");
        const overlay = document.querySelector(".sidebar-overlay");

        // Fail silently in console instead of breaking all other JS functionality on non-dashboard views
        if (!menuBtn || !sidebar || !overlay) {
            console.warn("Sidebar navigation layout elements missing from this page view.");
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
        if (closeBtn) {
            closeBtn.addEventListener("click", function () {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
                document.body.classList.remove("sidebar-open");
            });
        }
    });

    // Explicitly attach functions to the global window scope so HTML markup actions still work seamlessly
    global.showSection = showSection;
    global.editField = editField;
    global.copyReferral = copyReferral;
    global.showToast = showToast;
    global.saveProfile = saveProfile;
    global.focusEarn = focusEarn;
    global.confirmLogout = confirmLogout;
    global.toggleUserMenu = toggleUserMenu;
    global.toggleMenu = toggleMenu;

})(typeof window !== "undefined" ? window : this);