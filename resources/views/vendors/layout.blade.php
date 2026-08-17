<!DOCTYPE html>
<html lang="{{ $defaultLang->code ?? 'en' }}" dir="{{ (($defaultLang->direction??0)==1)?'rtl':'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Vendor') | {{ optional($websiteInfo)->website_title ?? 'StayZio' }}</title>
<link rel="shortcut icon" href="{{ asset('assets/img/'.(optional($websiteInfo)->favicon ?? 'favicon.png')) }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
{{-- Load Bootstrap + all vendor assets --}}
@include('vendors.partials.styles')
{{-- OUR CSS LOADS LAST - wins over Bootstrap --}}
<style>
/* ════════════════════════════════════════
   STAYZIO VENDOR — DARK THEME (v2)
   Loads AFTER Bootstrap to win specificity
════════════════════════════════════════ */
:root{
  --navy:#f5f0e8;--navy2:#ffffff;--navy3:#f8f6f1;--navy4:#f1ede5;
  --red:#e31e24;--red2:#ff3a40;--red-bg:rgba(227,30,36,.1);
  --text:#1a1612;--muted:#6b6560;--border:#e8e2d9;
  --green:#10b981;--amber:#f59e0b;--blue:#3b82f6;--purple:#8b5cf6;--cyan:#06b6d4;
  --sidebar:240px;--sidebar-c:54px;
}
/* ── Critical layout reset (beats Bootstrap) ── */
html{height:100%!important;overflow:hidden!important;background:#f5f0e8!important}
body{
  height:100%!important;overflow:hidden!important;background:#f5f0e8!important;
  display:flex!important;color:#1a1612!important;
  font-size:13px!important;font-family:'Inter',system-ui,sans-serif!important;
  margin:0!important;padding:0!important;
}
/* ── Sidebar ── */
#sz-sidebar{
  width:var(--sidebar);background:#ffffff;
  border-right:1px solid #e8e2d9;
  display:flex!important;flex-direction:column;flex-shrink:0;
  height:100vh!important;overflow:hidden;
  transition:width .22s ease;
  position:relative;z-index:100;
}
#sz-sidebar.collapsed{width:var(--sidebar-c);}
#sz-sidebar.collapsed .sb-name,#sz-sidebar.collapsed .sb-role,
#sz-sidebar.collapsed .sb-search-wrap,#sz-sidebar.collapsed .sb-group,
#sz-sidebar.collapsed .sb-item .lbl,#sz-sidebar.collapsed .sb-item .arr,
#sz-sidebar.collapsed .sb-item .badge-nav,#sz-sidebar.collapsed .sb-vendor-name,
#sz-sidebar.collapsed .sb-vendor-plan{opacity:0;overflow:hidden;white-space:nowrap;width:0}
#sz-sidebar.collapsed .sb-sub{max-height:0!important;overflow:hidden}
#sz-sidebar.collapsed .sb-item{justify-content:center;padding:9px 0}
#sz-sidebar.collapsed .sb-logo{padding:12px;justify-content:center}
/* Sidebar components */
.sb-logo{padding:15px 14px 12px;display:flex;align-items:center;gap:10px;border-bottom:1px solid #e8e2d9;flex-shrink:0}
.sb-mark{width:30px;height:30px;background:var(--red);border-radius:7px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;color:#fff;flex-shrink:0}
.sb-name{font-size:14px!important;font-weight:700!important;letter-spacing:-.3px;color:#1a1612!important}
.sb-role{font-size:10px;color:#6b6560!important}
.sb-vendor-info{padding:10px 14px;border-bottom:1px solid #e8e2d9;display:flex;align-items:center;gap:9px;min-width:0;flex-shrink:0}
.sb-vendor-av{width:28px;height:28px;background:var(--red-bg);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--red2);flex-shrink:0}
.sb-vendor-name{font-size:12px!important;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#1a1612!important}
.sb-vendor-plan{font-size:10px;color:#6b6560!important;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.sb-search-wrap{padding:10px 12px;border-bottom:1px solid #e8e2d9;flex-shrink:0}
.sb-search-wrap input{width:100%;background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:7px!important;padding:6px 10px!important;color:#1a1612!important;font-size:12px;outline:none;font-family:inherit}
.sb-search-wrap input:focus{border-color:var(--red)!important}
.sb-search-wrap input::placeholder{color:#6b6560}
.sb-nav{flex:1;overflow-y:auto;overflow-x:hidden;padding:6px 0 20px}
.sb-nav::-webkit-scrollbar{width:2px}
.sb-nav::-webkit-scrollbar-thumb{background:#d5d0c8;border-radius:2px}
.sb-group{padding:12px 14px 4px;font-size:9.5px;font-weight:700!important;color:#6b6560!important;letter-spacing:.1em;text-transform:uppercase}
.sb-item{display:flex!important;align-items:center;gap:9px;padding:7px 14px;cursor:pointer;color:#6b6560!important;font-size:12.5px!important;font-weight:500;transition:color .15s,background .15s;position:relative;text-decoration:none!important;background:transparent}
.sb-item:hover{background:rgba(227,30,36,.04)!important;color:#1a1612!important}
.sb-item.active{background:rgba(227,30,36,.08)!important;color:#e31e24!important;font-weight:600}
.sb-item.active::before{content:'';position:absolute;left:0;top:2px;bottom:2px;width:3px;background:var(--red);border-radius:0 3px 3px 0}
.sb-item i.icon{width:18px;text-align:center;font-size:16px;flex-shrink:0;color:inherit!important}
.sb-item .lbl{flex:1;color:inherit!important}
.sb-item .arr{font-size:11px;transition:transform .2s;color:#6b6560;flex-shrink:0}
.sb-item.open .arr{transform:rotate(90deg)}
.sb-sub{overflow:hidden;max-height:0;transition:max-height .3s ease}
.sb-sub.open{max-height:600px}
.sb-sub-item{display:flex!important;align-items:center;gap:8px;padding:5px 14px 5px 40px;color:#6b6560!important;font-size:12px;transition:color .15s,background .15s;text-decoration:none!important;background:transparent}
.sb-sub-item:hover{color:#1a1612!important;background:rgba(255,255,255,.03)!important}
.sb-sub-item.active{color:#1a1612!important}
.sb-sub-item::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:.4;display:inline-block}
.sb-sub-item.active::before{opacity:1;background:var(--red)}
/* ── Main area ── */
#sz-main{flex:1;display:flex!important;flex-direction:column;height:100vh!important;overflow:hidden;min-width:0}
.sz-topbar{height:52px;background:#ffffff!important;border-bottom:1px solid #e8e2d9;display:flex!important;align-items:center;padding:0 20px;gap:12px;flex-shrink:0}
.sz-hamburger{background:none!important;border:none!important;color:#6b6560;cursor:pointer;padding:4px 6px;border-radius:6px;font-size:18px;display:flex;align-items:center}
.sz-hamburger:hover{color:#1a1612;background:rgba(255,255,255,.05)}
.sz-bc{display:flex;align-items:center;gap:6px;font-size:12px;color:#6b6560!important}
.sz-bc .cur{color:#1a1612!important;font-weight:600}
.sz-spacer{flex:1}
.sz-tb-btn{width:34px;height:34px;border-radius:8px;background:#f8f6f1!important;border:1px solid #e8e2d9!important;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#6b6560!important;font-size:15px;text-decoration:none;transition:color .15s}
.sz-tb-btn:hover{color:#1a1612!important}
.sz-avatar{width:32px;height:32px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;cursor:pointer;flex-shrink:0;color:#fff!important}
.sz-uname{font-size:12px;font-weight:600;color:#1a1612!important}
.sz-content{flex:1;overflow-y:auto!important;overflow-x:hidden;padding:20px}
.sz-content::-webkit-scrollbar{width:4px}
.sz-content::-webkit-scrollbar-thumb{background:#d5d0c8;border-radius:2px}
/* ── Page header ── */
.page-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.page-hdr-left h2,.page-hdr-left h2 *{font-size:20px!important;font-weight:700!important;letter-spacing:-.3px;color:#1a1612!important;margin:0!important}
.page-hdr-left p{font-size:12px!important;color:#6b6560!important;margin-top:3px!important}
.page-hdr-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
/* ── Force headings ── */
h1,h2,h3,h4,h5,h6{color:#1a1612!important}
/* ── Buttons ── */
.btn{display:inline-flex!important;align-items:center;gap:6px;padding:7px 14px;border-radius:8px!important;font-size:12px!important;font-weight:600!important;cursor:pointer;transition:opacity .15s;text-decoration:none!important;white-space:nowrap;border:1px solid transparent}
.btn:hover{opacity:.88;text-decoration:none!important}
.btn-primary{background:var(--red)!important;color:#fff!important;border-color:var(--red)!important}
.btn-secondary{background:#f8f6f1!important;color:#1a1612!important;border-color:#e8e2d9!important}
.btn-secondary:hover{border-color:var(--red)!important;color:var(--red2)!important}
.btn-success{background:rgba(16,185,129,.15)!important;color:var(--green)!important;border-color:rgba(16,185,129,.3)!important}
.btn-danger{background:rgba(227,30,36,.1)!important;color:#f87171!important;border-color:rgba(227,30,36,.3)!important}
.btn-warning,.btn-warn{background:rgba(245,158,11,.12)!important;color:var(--amber)!important;border-color:rgba(245,158,11,.3)!important}
.btn-info{background:rgba(59,130,246,.12)!important;color:var(--blue)!important;border-color:rgba(59,130,246,.25)!important}
.btn-sm{padding:5px 10px!important;font-size:11.5px!important;border-radius:6px!important}
.btn-xs{padding:3px 8px!important;font-size:11px!important;border-radius:5px!important}
.btn-block{width:100%!important;justify-content:center}
/* ── Cards ── */
.card{background:#ffffff!important;border:1px solid #e8e2d9!important;border-radius:10px!important;overflow:hidden;margin-bottom:0}
.card-header,.card-hdr{background:#ffffff!important;border-bottom:1px solid #e8e2d9!important;padding:13px 16px!important;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
.card-title{font-size:13.5px!important;font-weight:700!important;color:#1a1612!important;margin:0!important}
.card-sub{font-size:11px!important;color:#6b6560!important;margin-top:2px}
.card-footer{background:#ffffff!important;border-top:1px solid #e8e2d9!important;padding:12px 16px!important}
.card-body{padding:16px!important;background:#ffffff!important}
/* ── KPI ── */
.kpi-grid{display:grid;gap:10px;margin-bottom:16px}
.kpi-grid.g4{grid-template-columns:repeat(4,1fr)}
.kpi-grid.g3{grid-template-columns:repeat(3,1fr)}
.kpi-grid.g2{grid-template-columns:repeat(2,1fr)}
.kpi{background:#ffffff!important;border:1px solid #e8e2d9!important;border-radius:10px;padding:14px 16px}
.kpi-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px}
.kpi-icon{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.kpi-icon.g{background:rgba(16,185,129,.12);color:var(--green)}
.kpi-icon.r{background:rgba(227,30,36,.12);color:var(--red2)}
.kpi-icon.a{background:rgba(245,158,11,.12);color:var(--amber)}
.kpi-icon.b{background:rgba(59,130,246,.12);color:var(--blue)}
.kpi-icon.p{background:rgba(139,92,246,.12);color:var(--purple)}
.kpi-icon.c{background:rgba(6,182,212,.12);color:var(--cyan)}
.delta{font-size:10px;padding:2px 7px;border-radius:6px;font-weight:600}
.delta.up{background:rgba(16,185,129,.12);color:var(--green)}
.delta.dn{background:rgba(227,30,36,.1);color:#f87171}
.delta.neu{background:#f8f6f1;color:#6b6560}
.kpi-val{font-size:22px!important;font-weight:700!important;color:#1a1612!important;letter-spacing:-.5px;line-height:1;display:block}
.kpi-val a{color:inherit!important;text-decoration:none!important}
.kpi-lbl{font-size:11px!important;color:#6b6560!important;margin-top:4px}
/* ── Tables ── */
.tbl-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead tr{border-bottom:1px solid #e8e2d9;background:#f8f6f1!important}
th{padding:10px 12px!important;font-size:10.5px!important;font-weight:600!important;color:#6b6560!important;text-align:left;text-transform:uppercase;letter-spacing:.05em;background:#f8f6f1!important}
td{padding:10px 12px!important;font-size:12.5px!important;border-bottom:1px solid rgba(255,255,255,.04)!important;vertical-align:middle;color:#1a1612!important;background:transparent!important}
tr:hover td{background:rgba(255,255,255,.02)!important}
.td-main{font-weight:600!important;color:#1a1612!important;font-size:13px!important}
.td-sub{font-size:11px!important;color:#6b6560!important;margin-top:2px}
.td-muted{color:#6b6560!important;font-size:12px!important}
.td-pair{display:flex;align-items:center;gap:10px}
.td-avatar{width:34px;height:34px;border-radius:8px;background:#f8f6f1;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#6b6560;flex-shrink:0}
.fw6{font-weight:600!important}.fw7{font-weight:700!important}
/* ── Badges ── */
.badge{display:inline-flex!important;align-items:center;gap:4px;padding:3px 9px!important;border-radius:20px!important;font-size:10.5px!important;font-weight:600!important;white-space:nowrap;border:none!important;vertical-align:middle}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.badge.no-dot::before,.badge-pill.no-dot::before{display:none}
.badge.green,.badge-success{background:rgba(16,185,129,.12)!important;color:var(--green)!important}
.badge.red,.badge-danger{background:rgba(227,30,36,.1)!important;color:#f87171!important}
.badge.amber,.badge-warning{background:rgba(245,158,11,.12)!important;color:var(--amber)!important}
.badge.blue,.badge-primary{background:rgba(59,130,246,.12)!important;color:var(--blue)!important}
.badge.purple,.badge-secondary{background:rgba(139,92,246,.12)!important;color:var(--purple)!important}
.badge.muted{background:#f8f6f1!important;color:#6b6560!important}
/* ── Filters ── */
.filters{display:flex;align-items:center;gap:8px;padding:12px 18px;border-bottom:1px solid #e8e2d9;flex-wrap:wrap}
.filters .fc{width:auto;padding:6px 10px;font-size:12px}
.filter-spacer{flex:1}
.ftag{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#f8f6f1;border:1px solid #e8e2d9;border-radius:6px;font-size:12px;color:#6b6560;cursor:pointer;text-decoration:none!important}
.ftag:hover,.ftag.active{border-color:var(--red)!important;color:var(--red2)!important;background:var(--red-bg)!important}
/* ── Forms ── */
.fg{display:flex;flex-direction:column;gap:5px}
.flabel,.control-label,label.flabel{font-size:10.5px!important;font-weight:700!important;color:#6b6560!important;text-transform:uppercase!important;letter-spacing:.06em;margin-bottom:5px!important;display:block}
.fc{background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:8px!important;padding:8px 12px!important;color:#1a1612!important;font-size:13px!important;outline:none;width:100%;transition:border-color .2s;font-family:inherit}
.fc:focus{border-color:var(--red)!important;box-shadow:none!important}
.fc::placeholder{color:#6b6560!important}
select.fc option{background:#ffffff;color:#1a1612}
textarea.fc{resize:vertical;min-height:88px}
.fc-hint{font-size:11px;color:#6b6560;margin-top:3px}
/* ── Bootstrap form overrides ── */
.form-control,.form-group input:not([type=checkbox]):not([type=radio]),.form-group select,.form-group textarea,.input-solid{
  background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:7px!important;
  color:#1a1612!important;font-size:13px!important;font-family:inherit!important;padding:8px 11px!important
}
.form-control:focus,.form-group input:focus,.form-group select:focus,.form-group textarea:focus{
  border-color:var(--red)!important;box-shadow:none!important;outline:none!important;
  background:#f8f6f1!important;color:#1a1612!important
}
.form-control::placeholder,.form-group input::placeholder,.form-group textarea::placeholder{color:#6b6560!important}
select.form-control option{background:#ffffff;color:#1a1612}
.form-group label{font-size:10.5px!important;font-weight:700!important;color:#6b6560!important;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px!important}
.form-group{margin-bottom:14px}
/* ── Alerts ── */
.alert{border-radius:8px!important;padding:10px 14px!important;font-size:13px!important;margin-bottom:14px}
.alert-warning{background:rgba(245,158,11,.1)!important;border:1px solid rgba(245,158,11,.3)!important;color:var(--amber)!important}
.alert-danger{background:rgba(227,30,36,.1)!important;border:1px solid rgba(227,30,36,.3)!important;color:#f87171!important}
.alert-success{background:rgba(16,185,129,.1)!important;border:1px solid rgba(16,185,129,.3)!important;color:var(--green)!important}
.alert-info{background:rgba(59,130,246,.1)!important;border:1px solid rgba(59,130,246,.3)!important;color:var(--blue)!important}
/* ── Stat rows / bars ── */
.stat-row{display:flex;align-items:center;justify-content:space-between;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.stat-row:last-child{border-bottom:none}
.stat-lbl{font-size:11px!important;color:#6b6560!important;font-weight:500}
.stat-val{font-size:13px!important;font-weight:700!important;color:#1a1612!important}
.bar-row{display:flex;align-items:center;gap:10px;margin-bottom:9px}
.bar-lbl{width:85px;font-size:12px;font-weight:500;flex-shrink:0;color:#1a1612!important;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.bar-track{flex:1;height:8px;background:rgba(255,255,255,.06);border-radius:4px;overflow:hidden}
.bar-fill{height:100%;border-radius:4px}
.bar-val{width:50px;text-align:right;font-size:11px;font-weight:600;flex-shrink:0;color:#1a1612!important}
/* ── Layout helpers ── */
.two-col{display:grid;grid-template-columns:2fr 1fr;gap:16px}
.two-col-eq{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.three-col{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px}
.mb-14{margin-bottom:14px}
.divider{border:none;border-top:1px solid #e8e2d9;margin:14px 0}
/* ── Package cards ── */
.pkg-card{background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:10px;padding:16px;position:relative}
.pkg-card.featured{border-color:var(--red)!important}
.pkg-badge{position:absolute;top:-9px;right:16px;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:2px 10px;border-radius:10px}
.pkg-price{font-size:28px!important;font-weight:700!important;color:#1a1612!important;line-height:1;margin:10px 0 4px}
.pkg-term{font-size:11px!important;color:#6b6560!important}
.pkg-feature{display:flex;align-items:center;gap:8px;font-size:12px!important;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.05);color:#1a1612!important}
.pkg-feature:last-child{border-bottom:none}
.pkg-feature i{color:var(--green);font-size:14px;flex-shrink:0}
/* ── Inventory inputs ── */
.inv-input{background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:6px!important;padding:5px 8px!important;color:#1a1612!important;font-size:12px!important;outline:none;width:100%;min-width:80px;font-family:inherit;text-align:center}
.inv-input:focus{border-color:var(--red)!important}
/* ── Select2 ── */
.select2-container--default .select2-selection--single,.select2-container--default .select2-selection--multiple{background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:7px!important}
.select2-container--default .select2-selection--single .select2-selection__rendered{color:#1a1612!important;padding:6px 10px!important}
.select2-dropdown{background:#ffffff!important;border:1px solid #e8e2d9!important}
.select2-container--default .select2-results__option{color:#1a1612!important}
.select2-container--default .select2-results__option--highlighted{background:var(--red-bg)!important;color:var(--red2)!important}
/* ── Dropzone ── */
.dropzone{background:#f8f6f1!important;border:2px dashed #e8e2d9!important;border-radius:10px!important;color:#1a1612!important}
.dropzone .dz-message{color:#6b6560!important}
/* ── Version accordion ── */
.version,.version-header{background:#f8f6f1!important;border:1px solid #e8e2d9!important;border-radius:8px!important;margin-bottom:8px!important}
.version-header button{color:#1a1612!important;background:none!important;font-weight:600!important}
.version-body{background:#ffffff!important;padding:14px!important}
/* ── Modals ── */
.modal-content{background:#ffffff!important;border:1px solid #e8e2d9!important;border-radius:12px!important}
.modal-header{background:#ffffff!important;border-bottom:1px solid #e8e2d9!important}
.modal-body{background:#ffffff!important}
.modal-footer{background:#ffffff!important;border-top:1px solid #e8e2d9!important}
.modal-title{color:#1a1612!important;font-size:15px!important;font-weight:700!important}
.close{color:#6b6560!important;opacity:1!important;background:none!important;border:none!important}
.close:hover{color:#1a1612!important}
/* ── Summernote ── */
.note-editor.note-frame{border:1px solid #e8e2d9!important;border-radius:8px!important;overflow:hidden!important}
.note-toolbar{background:#f8f6f1!important;border-bottom:1px solid #e8e2d9!important}
.note-editable{background:#f8f6f1!important;color:#1a1612!important;min-height:150px!important}
/* ── List groups ── */
.list-group-item{background:#f8f6f1!important;border:1px solid #e8e2d9!important;color:#1a1612!important;border-radius:7px!important;margin-bottom:6px!important}
.list-group-item label{color:#1a1612!important;font-size:13px!important;text-transform:none!important;font-weight:500!important}
/* ── Table (Bootstrap striped) ── */
.table{color:#1a1612!important;border-color:#e8e2d9!important}
.table thead th{background:#f8f6f1!important;color:#6b6560!important;border-color:#e8e2d9!important;font-size:10.5px!important}
.table td,.table th{border-color:rgba(255,255,255,.05)!important;color:#1a1612!important}
.table-striped tbody tr:nth-of-type(odd){background:rgba(0,0,0,.02)!important}
/* ── Misc ── */
.text-green{color:var(--green)!important}.text-amber{color:var(--amber)!important}
.text-red{color:var(--red2)!important}.text-blue{color:var(--blue)!important}
.text-muted{color:#6b6560!important}
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.page-header .page-title{font-size:20px!important;font-weight:700!important;color:#1a1612!important}
.breadcrumbs{display:none!important}
.av{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;color:#1a1612!important}
.av.red{background:rgba(227,30,36,.1);color:var(--red2)!important}
.av.muted{background:#f8f6f1;color:#6b6560!important}
.upload-btn{background:var(--red)!important;color:#fff!important;border:none!important;border-radius:7px!important;padding:7px 14px!important;font-size:12px!important;font-weight:600!important;cursor:pointer!important}
input[type=checkbox]{accent-color:var(--red)}
.custom-select{background:#f8f6f1!important;border:1px solid #e8e2d9!important;color:#1a1612!important;border-radius:7px!important}
@media(max-width:768px){
  #sz-sidebar{position:fixed;transform:translateX(-100%);transition:transform .25s ease}
  #sz-sidebar.mobile-open{transform:translateX(0)}
  #sz-main{width:100%}
  .kpi-grid.g4{grid-template-columns:repeat(2,1fr)}
  .three-col,.two-col{grid-template-columns:1fr}
}

/* Vendor property form inputs - force visible text */
.pf-input,.pf-input:focus,
.pf-price-input,.pf-price-input:focus,
body input.pf-input,body textarea.pf-input,body select.pf-input,
body input[type="text"],body input[type="number"],body input[type="email"],body input[type="tel"],
body textarea,body select{color:#1a1612!important}
body input.pf-input:focus,body textarea.pf-input:focus{color:#1a1612!important;background:#fff!important}
body .pf-price-input{color:#e31e24!important}
</style>
@yield('style')
</head>
<body>
<aside id="sz-sidebar">
  <div class="sb-logo">
    @php $logo = optional($websiteInfo)->header_logo ?? null; @endphp
    @if($logo)
      <img src="{{ asset('assets/img/'.$logo) }}" style="height:26px;max-width:110px;object-fit:contain;flex-shrink:0" alt="logo">
    @else
      <div class="sb-mark">{{ strtoupper(substr(optional($websiteInfo)->website_title??'S',0,1)) }}</div>
    @endif
    <div>
      <div class="sb-name">{{ optional($websiteInfo)->website_title ?? 'StayZio' }}</div>
      <div class="sb-role">Vendor Portal</div>
    </div>
  </div>

  <div class="sb-vendor-info">
    <div class="sb-vendor-av">{{ strtoupper(substr(optional(Auth::guard('vendor')->user())->username??'V',0,1)) }}</div>
    <div style="min-width:0">
      <div class="sb-vendor-name">{{ optional(Auth::guard('vendor')->user())->username ?? 'Vendor' }}</div>
      <div class="sb-vendor-plan">
        @php
          $vid = optional(Auth::guard('vendor')->user())->id;
          $today = date('Y-m-d');
          $vm = $vid ? \App\Models\Membership::where([['vendor_id',$vid],['start_date','<=',$today],['expire_date','>=',$today]])->where('status',1)->whereYear('start_date','<>','9999')->first() : null;
        @endphp
        {{ $vm ? 'Active Plan' : 'No Active Plan' }}
      </div>
    </div>
  </div>

  <div class="sb-search-wrap">
    <input type="text" placeholder="🔍  Search menu…" id="sz-search" oninput="szSearch(this.value)">
  </div>

  <nav class="sb-nav">
    <div class="sb-group">Overview</div>
    <a href="{{ route('vendor.dashboard', ['language' => $defaultLang->code]) }}" class="sb-item {{ request()->routeIs('vendor.dashboard')?'active':'' }}">
      <i class="ti ti-layout-dashboard icon"></i><span class="lbl">Dashboard</span>
    </a>

    {{-- ── My Properties ── --}}
    <a href="{{ route('vendor.properties.index', ['language' => $defaultLang->code]) }}" class="sb-item {{ request()->routeIs('vendor.properties*') ? 'active' : '' }}">
      <i class="ti ti-building icon"></i><span class="lbl">My Properties</span>
    </a>

    {{-- ── Bookings ── --}}
    <div class="sb-group">Bookings</div>
    <div class="sb-item {{ request()->routeIs('vendor.room_bookings.*') ? 'active open' : '' }}" id="nav-bookings" onclick="szToggle('sub-bookings','nav-bookings')">
      <i class="ti ti-calendar-check icon"></i><span class="lbl">Bookings</span><i class="ti ti-chevron-right arr"></i>
    </div>
    <div class="sb-sub {{ request()->routeIs('vendor.room_bookings.*') ? 'open' : '' }}" id="sub-bookings">
      <a class="sb-sub-item {{ request()->routeIs('vendor.room_bookings.all_bookings') ? 'active' : '' }}" href="{{ route('vendor.room_bookings.all_bookings', ['language' => $defaultLang->code]) }}">All Bookings</a>
      <a class="sb-sub-item {{ request()->routeIs('vendor.room_bookings.paid_bookings') ? 'active' : '' }}" href="{{ route('vendor.room_bookings.paid_bookings', ['language' => $defaultLang->code]) }}">Paid Bookings</a>
      <a class="sb-sub-item {{ request()->routeIs('vendor.room_bookings.unpaid_bookings') ? 'active' : '' }}" href="{{ route('vendor.room_bookings.unpaid_bookings', ['language' => $defaultLang->code]) }}">Pending Bookings</a>
      <a class="sb-sub-item {{ request()->routeIs('vendor.room_bookings.calendar') ? 'active' : '' }}" href="{{ route('vendor.room_bookings.calendar', ['language' => $defaultLang->code]) }}">Calendar</a>
    </div>

    {{-- ── Inventory ── --}}
    <a href="{{ url('vendor/hotel-management/hourly-inventory') }}" class="sb-item {{ request()->routeIs('vendor.hotel_management.hourly_inventory*') ? 'active' : '' }}">
      <i class="ti ti-packages icon"></i><span class="lbl">Inventory</span>
    </a>

    {{-- ── Withdrawal ── --}}
    <div class="sb-item {{ request()->routeIs('vendor.withdraw*') ? 'active open' : '' }}" id="nav-wd" onclick="szToggle('sub-wd','nav-wd')">
      <i class="ti ti-credit-card icon"></i><span class="lbl">Withdrawals</span><i class="ti ti-chevron-right arr"></i>
    </div>
    <div class="sb-sub {{ request()->routeIs('vendor.withdraw*') ? 'open' : '' }}" id="sub-wd">
      <a class="sb-sub-item {{ request()->routeIs('vendor.withdraw') ? 'active' : '' }}" href="{{ route('vendor.withdraw', ['language' => $defaultLang->code]) }}">My Withdrawals</a>
      <a class="sb-sub-item {{ request()->routeIs('vendor.withdraw.create') ? 'active' : '' }}" href="{{ route('vendor.withdraw.create', ['language' => $defaultLang->code]) }}">Request Withdrawal</a>
    </div>

    {{-- ── Account ── --}}
    <div class="sb-group">Account</div>
    <a class="sb-item {{ request()->routeIs('vendor.edit.profile') ? 'active' : '' }}" href="{{ route('vendor.edit.profile', ['language' => $defaultLang->code]) }}">
      <i class="ti ti-user icon"></i><span class="lbl">Profile</span>
    </a>
    <a class="sb-item {{ request()->routeIs('vendor.change_password') ? 'active' : '' }}" href="{{ route('vendor.change_password', ['language' => $defaultLang->code]) }}">
      <i class="ti ti-lock icon"></i><span class="lbl">Change Password</span>
    </a>
    <a class="sb-item" href="{{ route('vendor.logout', ['language' => $defaultLang->code]) }}"><i class="ti ti-logout icon"></i><span class="lbl">Logout</span></a>
  </nav>
</aside>

<div id="sz-main">
  <header class="sz-topbar">
    <button class="sz-hamburger" id="sz-toggle" title="Toggle sidebar"><i class="ti ti-menu-2"></i></button>
    <div class="sz-bc">
      <span>@yield('section','Vendor')</span>
      @hasSection('page')<i class="ti ti-chevron-right" style="font-size:10px"></i><span class="cur">@yield('page')</span>@endif
    </div>
    <div class="sz-spacer"></div>
    <a href="{{ route('vendor.edit.profile', ['language' => $defaultLang->code]) }}" class="sz-tb-btn" title="Profile"><i class="ti ti-user"></i></a>
    <a href="{{ route('vendor.logout', ['language' => $defaultLang->code]) }}" class="sz-tb-btn" title="Logout"><i class="ti ti-logout"></i></a>
    <div class="sz-avatar">{{ strtoupper(substr(optional(Auth::guard('vendor')->user())->username??'V',0,1)) }}</div>
    <span class="sz-uname">{{ optional(Auth::guard('vendor')->user())->username ?? 'Vendor' }}</span>
  </header>

  <div class="sz-content">
    @if(session('success'))
    <div style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:var(--green);border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13px"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:rgba(227,30,36,.1);border:1px solid rgba(227,30,36,.2);color:#f87171;border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13px"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif
    @if(session('alert'))
    <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);color:var(--amber);border-radius:8px;padding:10px 14px;margin-bottom:14px;display:flex;align-items:center;gap:8px;font-size:13px"><i class="ti ti-alert-triangle"></i> {{ session('alert') }}</div>
    @endif
    @yield('content')
  </div>
</div>

@include('vendors.partials.scripts')
<script>
function szToggle(subId, navId) {
  var sub = document.getElementById(subId);
  var nav = document.getElementById(navId);
  var isOpen = sub.classList.contains('open');
  document.querySelectorAll('.sb-sub.open').forEach(function(s){ if(s.id!==subId) s.classList.remove('open'); });
  document.querySelectorAll('.sb-item.open').forEach(function(e){ if(e.id!==navId) e.classList.remove('open'); });
  sub.classList.toggle('open', !isOpen);
  if(nav) nav.classList.toggle('open', !isOpen);
}
function szSearch(q) {
  q = q.toLowerCase();
  document.querySelectorAll('.sb-sub').forEach(function(s){ s.classList.toggle('open', !!q); });
  document.querySelectorAll('.sb-item, .sb-sub-item, .sb-group').forEach(function(el){
    el.style.display = (!q || el.textContent.toLowerCase().includes(q)) ? '' : 'none';
  });
}
// Sidebar: EXPANDED by default, hamburger collapses
(function(){
  var sb = document.getElementById('sz-sidebar');
  var btn = document.getElementById('sz-toggle');
  var KEY = 'sz_vendor_sb';
  var open = localStorage.getItem(KEY) !== 'collapsed';
  if (!open) sb.classList.add('collapsed');
  if(btn) btn.addEventListener('click', function(){
    open = !open;
    localStorage.setItem(KEY, open ? 'open' : 'collapsed');
    sb.classList.toggle('collapsed', !open);
  });
})();
// Force body layout after Bootstrap loads
document.addEventListener('DOMContentLoaded', function(){
  document.body.style.cssText = 'display:flex!important;height:100vh!important;overflow:hidden!important;background:#f5f0e8!important;margin:0!important;padding:0!important';
});
</script>
@yield('script')
</body>
</html>
