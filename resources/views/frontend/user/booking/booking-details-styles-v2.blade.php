<style>
.bdv2{padding:30px 0 80px;background:#fafafa;min-height:100vh}
.bdv2-wrap{max-width:940px;margin:0 auto;padding:0 16px}
.bdv2-back{display:inline-flex;align-items:center;gap:6px;color:#e31e24;font-weight:700;font-size:13px;text-decoration:none;margin-bottom:16px}
.bdv2-back:hover{color:#b91219}
.bdv2-hero{background:linear-gradient(135deg,#e31e24,#b91219);border-radius:16px;padding:22px;color:#fff;margin-bottom:18px;position:relative;overflow:hidden}
.bdv2-hero::after{content:'';position:absolute;right:-40px;top:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.08)}
.bdv2-hero-row{display:flex;justify-content:space-between;align-items:flex-start;gap:14px;flex-wrap:wrap;position:relative;z-index:1}
.bdv2-hero h1{font-size:22px;font-weight:900;margin:0 0 4px;font-family:'Syne','Inter',sans-serif}
.bdv2-hero p{font-size:12px;opacity:.85;margin:0 0 14px}
.bdv2-hero-id{background:rgba(255,255,255,.15);padding:9px 14px;border-radius:10px;border:1px solid rgba(255,255,255,.2);display:inline-flex;align-items:center;gap:14px;flex-wrap:wrap}
.bdv2-hero-id span{font-size:9px;font-weight:800;opacity:.7;letter-spacing:.07em;display:block;margin-bottom:2px}
.bdv2-hero-id b{font-family:monospace;font-size:14px;font-weight:700}
.bdv2-status{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:14px;font-size:10px;font-weight:800;background:#22c55e;color:#fff}
.bdv2-status.pending{background:#f59e0b}
.bdv2-status.rejected{background:#dc2626}
.bdv2-hero-btns{display:flex;flex-direction:column;gap:8px}
.bdv2-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:9px 16px;border-radius:9px;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;border:none;white-space:nowrap}
.bdv2-btn-white{background:#fff;color:#e31e24}
.bdv2-btn-white:hover{background:#fff;color:#b91219}
.bdv2-btn-out{background:transparent;border:1.5px solid rgba(255,255,255,.35);color:#fff}
.bdv2-btn-out:hover{background:rgba(255,255,255,.1);color:#fff}
.bdv2-grid{display:grid;grid-template-columns:1.3fr 1fr;gap:18px}
.bdv2-card{background:#fff;border:1px solid #f0e8e6;border-radius:14px;overflow:hidden;box-shadow:0 4px 16px rgba(18,24,40,.04);margin-bottom:14px}
.bdv2-card-hdr{padding:13px 18px;border-bottom:1px solid #f4ede9;display:flex;justify-content:space-between;align-items:center}
.bdv2-card-hdr h3{font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin:0}
.bdv2-card-body{padding:16px 18px}
.bdv2-hotel{display:flex;align-items:center;gap:14px}
.bdv2-hotel-img{width:72px;height:72px;border-radius:12px;overflow:hidden;background:linear-gradient(135deg,#fda4af,#fb923c);flex-shrink:0;position:relative}
.bdv2-hotel-img img{width:100%;height:100%;object-fit:cover;display:block}
.bdv2-hotel-img-fallback{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:26px;font-family:'Syne','Inter',sans-serif}
.bdv2-hotel-info b{display:block;font-size:15px;font-weight:800;color:#111;margin-bottom:3px}
.bdv2-hotel-info p{font-size:11px;color:#6b7280;margin:0 0 3px;display:flex;align-items:center;gap:4px}
.bdv2-hotel-rating{color:#f59e0b;font-weight:700;font-size:11px;display:flex;align-items:center;gap:4px}
.bdv2-timeline{background:linear-gradient(135deg,#fef8f7,#fdf2f0);border:1px solid #fde0d8;border-radius:11px;padding:14px 16px;display:flex;align-items:center;gap:8px}
.bdv2-tl-item{flex:1;text-align:center}
.bdv2-tl-item.l{text-align:left}
.bdv2-tl-item.r{text-align:right}
.bdv2-tl-item span{display:block;font-size:9px;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:3px}
.bdv2-tl-item b{font-size:13px;font-weight:800;color:#111;display:block}
.bdv2-tl-item em{font-style:normal;display:block;font-size:11px;font-weight:700;color:#e31e24;margin-top:1px}
.bdv2-tl-item em.muted{color:#9ca3af}
.bdv2-tl-sep{color:#e31e24;font-weight:900;font-size:18px}
.bdv2-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:14px}
.bdv2-info-cell{background:#faf8f7;border-radius:10px;padding:10px 12px}
.bdv2-info-cell span{display:block;font-size:9px;color:#9ca3af;font-weight:800;text-transform:uppercase;margin-bottom:3px;letter-spacing:.07em}
.bdv2-info-cell b{font-size:13px;color:#111;font-weight:700}
.bdv2-meta{background:#f0f9ff;border-left:3px solid #0ea5e9;padding:10px 12px;border-radius:7px;margin-top:14px;font-size:12px;color:#0c4a6e}
.bdv2-meta b{display:block;font-weight:800;font-size:10px;color:#075985;margin-bottom:3px;text-transform:uppercase;letter-spacing:.06em}
.bdv2-price-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;font-size:12px;color:#6b7280;border-bottom:1px solid #f5eeec}
.bdv2-price-row:last-child{border-bottom:none}
.bdv2-price-row b{color:#111;font-weight:700;font-size:13px}
.bdv2-price-total{border-top:2px solid #e31e24;padding-top:11px;margin-top:6px;font-size:14px}
.bdv2-price-total b{font-size:18px;font-weight:900;color:#e31e24}
.bdv2-payment-method{margin-top:14px;padding:10px 12px;background:#f9f7f6;border-radius:8px}
.bdv2-payment-method-lbl{font-size:9px;color:#9ca3af;font-weight:800;text-transform:uppercase;letter-spacing:.06em}
.bdv2-payment-method-val{font-size:13px;font-weight:800;color:#111;margin-top:2px}
@media(max-width:780px){
  .bdv2-grid{grid-template-columns:1fr}
  .bdv2-hero h1{font-size:18px}
  .bdv2-hero{padding:18px}
  .bdv2-hero-row{flex-direction:column}
  .bdv2-hero-btns{flex-direction:row;width:100%}
  .bdv2-btn{flex:1}
  .bdv2-timeline{flex-direction:column;align-items:stretch}
  .bdv2-tl-item.l,.bdv2-tl-item,.bdv2-tl-item.r{text-align:left}
  .bdv2-tl-sep{display:none}
}
</style>
