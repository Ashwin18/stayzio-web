<?php
$base = dirname(__DIR__);
$path = $base . '/resources/views/vendors/hotel/inventory/hourly.blade.php';

header('Content-Type: text/plain');

if (!file_exists($path)) { die('Target file not found'); }

$content = file_get_contents($path);
$report = [];

// 1. Add data-date and data-dow attributes to each row for JS targeting
$old1 = '          <tr style="{{ $isPast?\'opacity:.5\':\'\' }}">
            <td>
              <div style="font-weight:700;color:{{ $isToday?\'var(--red2)\':\'var(--text)\' }};font-size:13px">{{ $d->format(\'d M\') }}</div>';

$new1 = '          <tr style="{{ $isPast?\'opacity:.5\':\'\' }}" data-date="{{ $dateString }}" data-dow="{{ $d->dayOfWeek }}" data-past="{{ $isPast?1:0 }}">
            <td>
              <div style="font-weight:700;color:{{ $isToday?\'var(--red2)\':\'var(--text)\' }};font-size:13px">{{ $d->format(\'d M\') }}</div>';

$c1 = substr_count($content, $old1);
$content = str_replace($old1, $new1, $content);
$report[] = "Row data attributes: $c1 occurrence(s)";

// 2. Add the Bulk Fill toolbar right before the main inventory table card
$old2 = '<div class="card">
  <form id="inventoryForm" action="{{ route(\'vendor.hotel.inventory.update_inline\') }}" method="POST">';

$new2 = '<div class="card" style="margin-bottom:14px;background:rgba(59,130,246,.04);border-color:rgba(59,130,246,.2)">
  <div class="card-body" style="padding:14px 16px">
    <div style="font-weight:700;font-size:13px;margin-bottom:10px;display:flex;align-items:center;gap:6px"><i class="ti ti-wand"></i> Bulk Fill</div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
      <div class="fg">
        <label class="flabel">Field</label>
        <select id="bulkField" class="fc" onchange="updateBulkValueInput()">
          <option value="rate_3hrs">Rate - 3 hrs</option>
          <option value="rate_6hrs">Rate - 6 hrs</option>
          <option value="rate_fullday">Rate - Full day</option>
          <option value="total_rooms">Total rooms</option>
          <option value="status">Block day status</option>
          <option value="manual_status_3hrs">Duration override - 3 hrs</option>
          <option value="manual_status_6hrs">Duration override - 6 hrs</option>
          <option value="manual_status_fullday">Duration override - Full day</option>
          <option value="timing_3hrs">Check-in timing - 3 hrs</option>
          <option value="timing_6hrs">Check-in timing - 6 hrs</option>
        </select>
      </div>
      <div class="fg" id="bulkValueWrap" style="min-width:160px">
        <label class="flabel">Value</label>
        <input type="number" id="bulkValue" class="fc" placeholder="Enter value">
      </div>
      <div class="fg">
        <label class="flabel">Apply to</label>
        <select id="bulkDays" class="fc">
          <option value="all">All days in range</option>
          <option value="weekday">Weekdays only (Mon-Fri)</option>
          <option value="weekend">Weekends only (Sat-Sun)</option>
        </select>
      </div>
      <div class="fg">
        <button type="button" class="btn btn-secondary" onclick="applyBulkFill()"><i class="ti ti-wand"></i> Apply to table</button>
      </div>
    </div>
    <div style="font-size:11px;color:var(--muted);margin-top:8px">This fills in the fields below - nothing saves until you click "Save Inventory Rules".</div>
  </div>
</div>

<div class="card">
  <form id="inventoryForm" action="{{ route(\'vendor.hotel.inventory.update_inline\') }}" method="POST">';

$c2 = substr_count($content, $old2);
$content = str_replace($old2, $new2, $content);
$report[] = "Bulk Fill toolbar insertion: $c2 occurrence(s)";

// 3. Add the JS logic right before @endsection
$old3 = '@endsection';

$new3 = '<script>
function updateBulkValueInput() {
  var field = document.getElementById(\'bulkField\').value;
  var wrap = document.getElementById(\'bulkValueWrap\');
  var statusFields = { status: [[\'Available\',\'Available\'],[\'Blocked\',\'Blocked (Holiday)\']],
    manual_status_3hrs: [[\'\',\'Auto\'],[\'Available\',\'Available\'],[\'Sold Out\',\'Sold Out\']],
    manual_status_6hrs: [[\'\',\'Auto\'],[\'Available\',\'Available\'],[\'Sold Out\',\'Sold Out\']],
    manual_status_fullday: [[\'\',\'Auto\'],[\'Available\',\'Available\'],[\'Sold Out\',\'Sold Out\']] };

  if (statusFields[field]) {
    var opts = statusFields[field].map(function(o){ return \'<option value="\'+o[0]+\'">\'+o[1]+\'</option>\'; }).join(\'\');
    wrap.innerHTML = \'<label class="flabel">Value</label><select id="bulkValue" class="fc">\' + opts + \'</select>\';
  } else if (field.indexOf(\'timing\') === 0) {
    wrap.innerHTML = \'<label class="flabel">Value</label><input type="text" id="bulkValue" class="fc" placeholder="e.g. 12AM-11PM">\';
  } else {
    wrap.innerHTML = \'<label class="flabel">Value</label><input type="number" id="bulkValue" class="fc" placeholder="Enter value">\';
  }
}

function applyBulkFill() {
  var field = document.getElementById(\'bulkField\').value;
  var value = document.getElementById(\'bulkValue\').value;
  var dayFilter = document.getElementById(\'bulkDays\').value;

  if (value === \'\' || value === null) {
    alert(\'Enter a value to apply first.\');
    return;
  }

  var rows = document.querySelectorAll(\'#inventoryForm tbody tr\');
  var count = 0;
  rows.forEach(function(row) {
    if (row.dataset.past === \'1\') return;
    var dow = parseInt(row.dataset.dow, 10);
    var isWeekend = (dow === 0 || dow === 6);
    if (dayFilter === \'weekday\' && isWeekend) return;
    if (dayFilter === \'weekend\' && !isWeekend) return;

    var input = row.querySelector(\'[name$="[\' + field + \']"]\');
    if (input) {
      input.value = value;
      count++;
    }
  });

  alert(\'Applied to \' + count + \' day(s). Review the table, then click "Save Inventory Rules" to confirm.\');
}
</script>
@endsection';

$c3 = substr_count($content, $old3);
$content = str_replace($old3, $new3, $content);
$report[] = "JS logic insertion: $c3 occurrence(s)";

$backup = $path . '.bak-' . date('Ymd-His');
copy($path, $backup);
file_put_contents($path, $content);

echo "=== VENDOR BULK FILL DEPLOY REPORT ===\n\n";
foreach ($report as $line) { echo $line . "\n"; }
echo "\nBackup saved at: " . basename($backup) . "\n";

$viewCacheDir = $base . '/storage/framework/views';
$cleared = 0;
if (is_dir($viewCacheDir)) {
    foreach (glob($viewCacheDir . '/*.php') as $f) { unlink($f); $cleared++; }
}
echo "Compiled views cleared: $cleared files\n";
if (function_exists('opcache_reset')) { opcache_reset(); echo "OPcache cleared.\n"; }
