<style>
#sz-confetti{position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:9999}
.szb-page{min-height:100vh;background:linear-gradient(160deg,#fff7f5 0%,#fff 60%,#f8fafc 100%);padding:48px 16px 80px}
.szb-shell{max-width:960px;margin:0 auto;display:grid;grid-template-columns:1fr 1.1fr;gap:28px;align-items:start}
.szb-hero{background:linear-gradient(145deg,#e31e24,#b91219);border-radius:28px;padding:36px 28px 28px;color:#fff;position:relative;overflow:hidden;box-shadow:0 24px 60px rgba(227,30,36,.30)}
.szb-hero::before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.10)}
.szb-hero::after{content:'';position:absolute;left:-70px;bottom:-70px;width:200px;height:200px;border-radius:50%;background:rgba(0,0,0,.07)}
.szb-check{width:88px;height:88px;border:3px solid rgba(255,255,255,.85);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:40px;margin-bottom:20px;position:relative;z-index:1;animation:szPop .5s cubic-bezier(.175,.885,.32,1.275) both}
@keyframes szPop{from{transform:scale(0);opacity:0}to{transform:scale(1);opacity:1}}
.szb-hero h1{font-size:34px;font-weight:900;line-height:1.15;margin:0 0 10px;position:relative;z-index:1}
.szb-hero p{font-size:14px;color:rgba(255,255,255,.85);line-height:1.6;margin:0;position:relative;z-index:1}
.szb-id-box{position:relative;z-index:1;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);border-radius:16px;padding:14px 16px;margin-top:24px;display:flex;align-items:center;justify-content:space-between;gap:12px}
.szb-id-box span{font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.7);font-weight:700;display:block;margin-bottom:4px}
.szb-id-box b{font-size:18px;font-family:monospace;letter-spacing:.04em}
.szb-copy-btn{background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);color:#fff;border-radius:10px;padding:7px 12px;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap}
.szb-badges{display:flex;gap:8px;flex-wrap:wrap;margin-top:20px;position:relative;z-index:1}
.szb-badge{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.22);border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;display:flex;align-items:center;gap:5px}
.szb-card{background:#fff;border:1px solid #f0e8e6;border-radius:28px;box-shadow:0 24px 64px rgba(18,24,40,.09);overflow:hidden}
.szb-card-top{display:flex;align-items:center;gap:14px;padding:20px 22px;border-bottom:1px solid #f4ede9}
.szb-card-top img{width:80px;height:68px;object-fit:cover;border-radius:14px;flex-shrink:0;border:1px solid #f0e8e6}
.szb-card-top h2{font-size:19px;font-weight:900;margin:0 0 4px;color:#111}
</style>