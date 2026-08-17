// HERO SECTION JAVASCRIPT

(function () {
    // ========== TABS + Hourly/FullDay toggle ==========
    const tabs = document.querySelectorAll('.tabs button');
    const slider = document.getElementById('tabSlider');
    const timeSection = document.querySelector('.time-section');
    const guestSection = document.querySelector('.guest-section');

    function setActiveTab(idx) {
        tabs.forEach((btn, i) => {
            if (i === idx) btn.classList.add('active');
            else btn.classList.remove('active');
        });
        if (slider) slider.style.transform = `translateX(${idx * 100}%)`;
        if (idx === 0) {
            if (timeSection) timeSection.style.display = "block";
            if (guestSection) guestSection.style.display = "none";
        } else {
            if (timeSection) timeSection.style.display = "none";
            if (guestSection) guestSection.style.display = "block";
        }
    }

    tabs.forEach((btn, i) => {
        btn.addEventListener('click', () => setActiveTab(i));
    });
    setActiveTab(0);

    // ========== Location Dropdown ==========
    const locDropdown = document.getElementById('locationDropdown');
    const citySpan = locDropdown?.querySelector('.selected-city');
    const cityListItems = document.querySelectorAll('.dropdown-list li');

    cityListItems.forEach(li => {
        li.addEventListener('click', function () {
            if (citySpan) citySpan.innerHTML = this.innerText + ' <i class="fas fa-chevron-down"></i>';
            locDropdown.classList.remove('active');
        });
    });

    citySpan?.addEventListener('click', (e) => {
        e.stopPropagation();
        locDropdown.classList.toggle('active');
    });

    document.addEventListener('click', () => locDropdown?.classList.remove('active'));

    // ========== FULL YEAR DATE PICKER WITH LIVE SELECTION ==========
    const dateTrigger = document.getElementById('dateDropdownTrigger');
    const closePopupBtn = document.getElementById('closeDatePopupBtn');
    const dateRangeDisplay = document.getElementById('dateRangeDisplay');
    const selectedRangeSpan = document.getElementById('selectedRangeText');

    let selectedDate = null;
    let currentMonthIndex = 0;

    function formatDate(date) {
        if (!date) return '';
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'short' });
        const year = date.getFullYear().toString().slice(-2);
        return `${day} ${month} ${year}`;
    }

    function formatDateFull(date) {
        if (!date) return '';
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'short' });
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
    }

    function getMonthDate(offset) {
        const today = new Date();
        const targetDate = new Date(today.getFullYear(), today.getMonth() + offset, 1);
        return targetDate;
    }

    // Update the display immediately when dates are selected
    function updateDateDisplay() {
        if (startDate && endDate) {
            const rangeText = `${formatDate(startDate)} - ${formatDate(endDate)}`;
            dateRangeDisplay.innerHTML = `${rangeText} <i class="fas fa-chevron-down"></i>`;
            if (selectedRangeSpan) {
                selectedRangeSpan.innerText = `${formatDateFull(startDate)} → ${formatDateFull(endDate)}`;
            }
        } else if (startDate && !endDate) {
            dateRangeDisplay.innerHTML = `${formatDate(startDate)} - Select end <i class="fas fa-chevron-down"></i>`;
            if (selectedRangeSpan) {
                selectedRangeSpan.innerText = `${formatDateFull(startDate)} (select check-out)`;
            }
        }
    }

    function renderCalendar() {
        const container = document.getElementById('calendarMonths');
        if (!container) return;

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const startMonth = currentMonthIndex;
        const monthsToShow = [getMonthDate(startMonth), getMonthDate(startMonth + 1)];
        container.innerHTML = '';

        monthsToShow.forEach((monthDate) => {
            const year = monthDate.getFullYear();
            const month = monthDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const startWeekday = firstDay.getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const monthName = monthDate.toLocaleString('default', { month: 'long', year: 'numeric' });

            const monthCard = document.createElement('div');
            monthCard.className = 'month-card';
            monthCard.style.animation = `slideIn 0.3s ease`;
            monthCard.innerHTML = `
                <div class="month-name">${monthName}</div>
                <div class="weekdays">${['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'].map(d => `<div>${d}</div>`).join('')}</div>
                <div class="calendar-days" data-month="${year}-${month}"></div>
            `;
            const daysGrid = monthCard.querySelector('.calendar-days');

            for (let i = 0; i < startWeekday; i++) {
                const blank = document.createElement('div');
                blank.className = 'day disabled';
                blank.innerText = '';
                daysGrid.appendChild(blank);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dayDate = new Date(year, month, d);
                const dayDiv = document.createElement('div');
                dayDiv.className = 'day';
                dayDiv.innerText = d;

                if (dayDate < today) dayDiv.classList.add('disabled');

                dayDiv.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (dayDate < today) return;

                    // Update selection logic
                    if (!tempStart || (tempStart && tempEnd)) {
                        // Start new selection
                        tempStart = dayDate;
                        tempEnd = null;
                    } else if (tempStart && !tempEnd) {
                        // Complete the range
                        if (dayDate >= tempStart) {
                            tempEnd = dayDate;
                        } else {
                            tempEnd = tempStart;
                            tempStart = dayDate;
                        }
                        // Immediately save and update display
                        startDate = new Date(tempStart);
                        endDate = new Date(tempEnd);
                        updateDateDisplay();
                        // Close popup after selection
                        dateTrigger.classList.remove('active');
                    }
                    updateHighlight();
                    updateFooterText();
                });
                daysGrid.appendChild(dayDiv);
            }
            container.appendChild(monthCard);
        });
        updateHighlight();
        updateNavigationButtons();
    }

    function updateHighlight() {
        document.querySelectorAll('.calendar-days .day').forEach(dayDiv => {
            dayDiv.classList.remove('selected', 'in-range');
            if (dayDiv.classList.contains('disabled')) return;

            const dayNum = parseInt(dayDiv.innerText);
            const parentGrid = dayDiv.closest('.calendar-days');
            const monthAttr = parentGrid.getAttribute('data-month');
            if (!monthAttr) return;

            const [year, month] = monthAttr.split('-').map(Number);
            const cellDate = new Date(year, month, dayNum);

            if (tempStart && tempStart.toDateString() === cellDate.toDateString()) dayDiv.classList.remove('selected');
            if (tempEnd && tempEnd.toDateString() === cellDate.toDateString()) dayDiv.classList.add('selected');
            if (tempStart && tempEnd && cellDate > tempStart && cellDate < tempEnd) dayDiv.classList.add('in-range');
        });
    }

    function updateFooterText() {
        if (!selectedRangeSpan) return;
        if (tempStart && tempEnd) {
            selectedRangeSpan.innerText = `${formatDateFull(tempStart)} → ${formatDateFull(tempEnd)}`;
        } else if (tempStart) {
            selectedRangeSpan.innerText = `${formatDateFull(tempStart)} (select check-out)`;
        } else {
            selectedRangeSpan.innerText = 'No dates selected';
        }
    }

    function previousMonths() {
        if (currentMonthIndex > 0) {
            currentMonthIndex--;
            renderCalendar();
        }
    }

    function nextMonths() {
        currentMonthIndex++;
        renderCalendar();
    }

    function updateNavigationButtons() {
        const existingNav = document.querySelector('.calendar-nav');
        if (existingNav) existingNav.remove();

        const datePopupHeader = document.querySelector('.date-popup-header');
        if (!datePopupHeader) return;

        const navDiv = document.createElement('div');
        navDiv.className = 'calendar-nav';
        navDiv.style.display = 'flex';
        navDiv.style.gap = '8px';
        navDiv.style.marginLeft = 'auto';

        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.style.background = '#f1f5f9';
        prevBtn.style.border = 'none';
        prevBtn.style.width = '32px';
        prevBtn.style.height = '32px';
        prevBtn.style.borderRadius = '50%';
        prevBtn.style.cursor = 'pointer';
        prevBtn.style.transition = '0.2s';
        prevBtn.onmouseover = () => prevBtn.style.background = '#E31E24';
        prevBtn.onmouseout = () => prevBtn.style.background = '#f1f5f9';
        prevBtn.onclick = (e) => {
            e.stopPropagation();
            previousMonths();
        };

        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.style.background = '#f1f5f9';
        nextBtn.style.border = 'none';
        nextBtn.style.width = '32px';
        nextBtn.style.height = '32px';
        nextBtn.style.borderRadius = '50%';
        nextBtn.style.cursor = 'pointer';
        nextBtn.style.transition = '0.2s';
        nextBtn.onmouseover = () => nextBtn.style.background = '#E31E24';
        nextBtn.onmouseout = () => nextBtn.style.background = '#f1f5f9';

        nextBtn.onclick = (e) => {
            e.stopPropagation();
            nextMonths();
        };

        navDiv.appendChild(prevBtn);
        navDiv.appendChild(nextBtn);
        datePopupHeader.appendChild(navDiv);
    }

    // Add slide animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .calendar-nav button:hover {
            color: white;
        }
        .month-card {
            transition: all 0.3s ease;
        }
    `;
    document.head.appendChild(style);

    dateTrigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        if (startDate && endDate) {
            tempStart = new Date(startDate);
            tempEnd = new Date(endDate);
        } else {
            tempStart = null;
            tempEnd = null;
        }
        currentMonthIndex = 0;
        renderCalendar();
        updateFooterText();
        dateTrigger.classList.toggle('active');
    });
    closePopupBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        dateTrigger.classList.remove('active');
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#dateDropdownTrigger')) {
            dateTrigger.classList.remove('active');
        }
    });

    // Set default dates
    const defaultStart = new Date(2026, 3, 20);
    const defaultEnd = new Date(2026, 3, 21);
    startDate = defaultStart;
    endDate = defaultEnd;
    tempStart = defaultStart;
    tempEnd = defaultEnd;
    dateRangeDisplay.innerHTML = `20 Apr 26 - 21 Apr 26 <i class="fas fa-chevron-down"></i>`;
    if (selectedRangeSpan) {
        selectedRangeSpan.innerText = `20 Apr 2026 → 21 Apr 2026`;
    }

    // ========== TIME DROPDOWN ==========
    const timeDropdown = document.getElementById('timeDropdown');
    const selectedTimeSpan = timeDropdown?.querySelector('.selected-time');
    const timeListDiv = timeDropdown?.querySelector('.time-list');
    const ampmBtns = timeDropdown?.querySelectorAll('.time-toggle button');

    function generateTimes(type) {
        if (!timeListDiv) return;
        timeListDiv.innerHTML = '';
        let hours = type === 'am' ? [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] : [12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
        hours.forEach(h => {
            let hour12 = h > 12 ? h - 12 : h;
            let period = h < 12 ? "AM" : "PM";
            let timeStr = `${hour12.toString().padStart(2, '0')}:00 ${period}`;
            let div = document.createElement('div');
            div.innerText = timeStr;
            div.addEventListener('click', () => {
                if (selectedTimeSpan) selectedTimeSpan.innerHTML = timeStr + ' <i class="fas fa-chevron-down arrow"></i>';
                timeDropdown.classList.remove('active');
            });
            timeListDiv.appendChild(div);
        });
    }

    if (ampmBtns) {
        ampmBtns.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();

                ampmBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                generateTimes(this.dataset.type);
            });
        });
        generateTimes('am');
    }

    selectedTimeSpan?.addEventListener('click', (e) => {
        e.stopPropagation();
        timeDropdown.classList.toggle('active');
    });
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#timeDropdown')) {
            timeDropdown?.classList.remove('active');
        }
    });

    // ========== GUEST DROPDOWN ==========
    const guestDropdown = document.getElementById('guestDropdown');
    const guestSelected = guestDropdown?.querySelector('.selected-guest');
    const plusBtns = guestDropdown?.querySelectorAll('.plus');
    const minusBtns = guestDropdown?.querySelectorAll('.minus');
    const values = guestDropdown?.querySelectorAll('.value');

    function updateGuest() {
        if (guestSelected && values) {
            guestSelected.innerHTML = `<i class="fas fa-user"></i> ${values[0].innerText} <i class="fas fa-bed ms-2"></i> ${values[1].innerText} <i class="fas fa-child ms-2"></i> ${values[2].innerText} <i class="fas fa-chevron-down ms-2 arrow"></i>`;
        }
    }

    plusBtns?.forEach((btn, i) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            values[i].innerText = parseInt(values[i].innerText) + 1;
            updateGuest();
        });
    });

    minusBtns?.forEach((btn, i) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (parseInt(values[i].innerText) > 0) {
                values[i].innerText = parseInt(values[i].innerText) - 1;
                updateGuest();
            }
        });
    });

    guestSelected?.addEventListener('click', (e) => {
        e.stopPropagation();
        guestDropdown.classList.toggle('active');
    });

    document.addEventListener('click', () => guestDropdown?.classList.remove('active'));
    updateGuest();
})();


function toggleMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('show');
}
function toggleFaq(el) {
    el.parentElement.classList.toggle('open');
}
// Filter button toggle
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.filters').querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    });
});


document.addEventListener("DOMContentLoaded", function () {

    const filterButtons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".card");

    filterButtons.forEach(button => {
        button.addEventListener("click", function () {

            // remove active class
            filterButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            const filterText = this.textContent.trim();

            cards.forEach(card => {
                const badge = card.querySelector(".card-badge").textContent.trim();
                const km = parseFloat(badge);

                // Default hide
                let show = false;

                if (filterText === "Within 3 km") {
                    show = km <= 3;
                }
                else if (filterText === "Within 5 km") {
                    show = km <= 5;
                }
                else if (filterText === "Couple Friendly") {
                    show = card.innerText.includes("Couple");
                }
                else if (filterText === "Premium") {
                    show = card.innerText.includes("Premium");
                }
                else if (filterText === "Pay at Hotel") {
                    show = true; // customize later
                }

                card.style.display = show ? "block" : "none";
            });

        });
    });

});


// Milestone Counter Animation//

document.addEventListener("DOMContentLoaded", function () {

    const counters = document.querySelectorAll(".milestone-item .num");

    const runCounter = (counter) => {
        const text = counter.innerText.trim();

        let target = parseInt(text.replace(/\D/g, ""));
        let suffix = text.replace(/[0-9]/g, ""); // + or L+

        let count = 0;
        let speed = Math.ceil(target / 80);

        const update = setInterval(() => {
            count += speed;

            if (count >= target) {
                count = target;
                clearInterval(update);
            }

            counter.innerText = count + suffix;

        }, 30);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                counters.forEach(counter => {
                    if (!counter.classList.contains("counted")) {
                        counter.classList.add("counted");
                        runCounter(counter);
                    }
                });
            }
        });
    }, { threshold: 0.75 });

    observer.observe(document.querySelector(".milestones"));

});





/* Final mobile menu + booking handlers */
function toggleSiteMenu(){document.getElementById('siteMobileMenu')?.classList.toggle('open');document.getElementById('siteMenuBackdrop')?.classList.toggle('open');document.body.classList.toggle('site-menu-open');}
function closeSiteMenu(){document.getElementById('siteMobileMenu')?.classList.remove('open');document.getElementById('siteMenuBackdrop')?.classList.remove('open');document.body.classList.remove('site-menu-open');}
function handleMobileBookNow(e){if(window.innerWidth<=768){e&&e.preventDefault();toggleMobileBooking();return false;} window.location.href='hotel-booking.html';}
document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeSiteMenu();closeMobileBookingPanel&&closeMobileBookingPanel();}});
