
function toggleChip(el) {
    el.classList.toggle('selected');
}

function toggleFilter(titleEl) {
    titleEl.classList.toggle('collapsed');
    const section = titleEl.nextElementSibling;
    if (section) {
        section.style.display = section.style.display === 'none' ? '' : 'none';
    }
}

function clearFilters() {
    document.querySelectorAll('.chip.selected').forEach(c => c.classList.remove('selected'));
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
}

function activateSort(btn) {
    document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function activatePage(btn) {
    document.querySelectorAll('.pg-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function selectDtab(tab, e) {
    e.stopPropagation();
    const siblings = tab.closest('.duration-tabs').querySelectorAll('.dtab');
    siblings.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
}

// View toggle
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
