<style>
.mb-toolbar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid #f1f5f9}
.mb-count{font-size:12px;color:#6b7280;font-weight:600}
.mb-pills{display:flex;gap:5px;flex-wrap:wrap}
.mb-pill{font-size:10px;font-weight:800;padding:5px 11px;border-radius:14px;border:1px solid #e5e7eb;color:#6b7280;cursor:pointer;text-transform:uppercase;letter-spacing:.04em;transition:all .15s}
.mb-pill:hover{border-color:#cbd5e1}
.mb-pill.active{background:#e31e24;color:#fff;border-color:#e31e24}
.mb-list{display:flex;flex-direction:column;gap:12px}
.mb-card{background:#fff;border:1px solid #f0e8e6;border-radius:11px;padding:13px;display:grid;grid-template-columns:80px 1fr auto;gap:12px;align-items:start;transition:border-color .15s}
.mb-card:hover{border-color:#e31e24}
.mb-card.cancelled{opacity:.75}
.mb-img{width:80px;height:80px;border-radius:9px;background:linear-gradient(135deg,#fda4af,#fb923c);position:relative;overflow:hidden;flex-shrink:0}
.mb-img img{width:100%;height:100%;object-fit:cover;display:block}
.mb-img-fallback{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:24px;font-family:'Syne','Inter',sans-serif;text-shadow:0 2px 6px rgba(0,0,0,.2)}
.mb-mid b{display:block;font-size:13px;font-weight:800;color:#111;margin-bottom:1px;line-height:1.3}
.mb-loc{font-size:10px;color:#6b7280;margin-bottom:6px;display:flex;align-items:center;gap:3px}
.mb-tags{display:flex;gap:4px;margin-bottom:6px;flex-wrap:wrap}
.mb-tag{font-size:8px;font-weight:800;padding:3px 7px;border-radius:9px;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap}
.mb-tag-hourly{background:#fef3c7;color:#92400e}
.mb-tag-fullday{background:#fce7f3;color:#9f1239}
.mb-tag-paid{background:#dcfce7;color:#15803d}
.mb-tag-pending{background:#fef3c7;color:#92400e}
.mb-tag-cancelled{background:#fee2e2;color:#dc2626}
.mb-dt{font-size:11px;color:#374151;line-height:1.6}
.mb-dt-row{display:flex;align-items:center;gap:5px;margin-bottom:1px}
.mb-dt-row i{color:#e31e24;font-size:10px;width:11px;text-align:center}
.mb-dt-row b{font-weight:700;color:#111;font-size:11px}
.mb-booked{margin-top:4px;font-size:9px;color:#9ca3af;display:flex;align-items:center;gap:3px}
.mb-booked i{font-size:9px}
.mb-r{text-align:right;min-width:100px}
.mb-amt{font-size:16px;font-weight:900;color:#e31e24;font-family:'Syne','Inter',sans-serif;line-height:1}
.mb-amt.cancelled{color:#9ca3af;text-decoration:line-through;font-size:13px}
.mb-lbl{font-size:8px;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;font-weight:800;margin:3px 0 6px}
.mb-lbl.cancelled{color:#dc2626}
.mb-acts{display:flex;flex-direction:column;gap:4px}
.mb-btn{padding:5px 10px;border-radius:7px;font-size:9px;font-weight:800;text-align:center;display:flex;align-items:center;justify-content:center;gap:3px;cursor:pointer;border:1px solid transparent;text-decoration:none}
.mb-btn-view{background:#e31e24;color:#fff;border-color:#e31e24}
.mb-btn-view:hover{background:#b91219;color:#fff}
.mb-btn-cancel{background:#fee2e2;color:#dc2626;border:1px solid #fecaca}
.mb-btn-cancel:hover{background:#fecaca}
.mb-refund-msg{font-size:9px;color:#dc2626;font-weight:700;margin-top:6px;display:flex;align-items:center;gap:3px}
.mb-empty{text-align:center;padding:40px 20px;background:#fafafa;border-radius:12px;border:1px dashed #e5e7eb}
@media(max-width:640px){
  .mb-toolbar{flex-direction:column;align-items:stretch}
  .mb-pills{overflow-x:auto;-webkit-overflow-scrolling:touch}
  .mb-card{grid-template-columns:55px 1fr;padding:10px;gap:9px}
  .mb-img{width:55px;height:55px}
  .mb-img-fallback{font-size:18px}
  .mb-mid b{font-size:11px}
  .mb-tags{margin-bottom:5px}
  .mb-tag{font-size:7px;padding:2px 6px}
  .mb-dt{font-size:10px}
  .mb-r{grid-column:1/-1;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f3f4f6;padding-top:8px;margin-top:6px;text-align:left;min-width:0}
  .mb-acts{flex-direction:row}
  .mb-btn{font-size:9px;padding:6px 12px}
  .mb-amt{font-size:14px}
}
</style>
