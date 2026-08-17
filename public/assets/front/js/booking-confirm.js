
function szCopyId() {
  var id = document.getElementById('szBookingId').textContent.trim();
  navigator.clipboard.writeText(id).then(function() {
    var btn = document.querySelector('.szb-copy-btn');
    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    setTimeout(function(){ btn.innerHTML = '<i class="fas fa-copy"></i> Copy'; }, 2000);
  });
}
(function() {
  var canvas = document.getElementById('sz-confetti');
  var ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  var colors = ['#e31e24','#ff6b6b','#ffd93d','#6bcb77','#4d96ff','#ff9f43','#ffffff'];
  var pieces = Array.from({length:160}, function() {
    return {
      x: Math.random()*canvas.width, y: -20-Math.random()*canvas.height*0.4,
      w: 8+Math.random()*8, h: 5+Math.random()*5,
      color: colors[Math.floor(Math.random()*colors.length)],
      speed: 2.5+Math.random()*3.5, angle: Math.random()*Math.PI*2,
      rot: (Math.random()-.5)*0.12, drift: (Math.random()-.5)*1.2, opacity: 1
    };
  });
  var start = Date.now(), dur = 4800;
  function draw() {
    var el = Date.now()-start;
    if (el > dur) { canvas.style.display='none'; return; }
    ctx.clearRect(0,0,canvas.width,canvas.height);
    var fadeAt = dur*0.6;
    pieces.forEach(function(p) {
      p.y+=p.speed; p.x+=p.drift; p.angle+=p.rot;
      if (el>fadeAt) p.opacity = Math.max(0,1-(el-fadeAt)/(dur-fadeAt));
      ctx.save(); ctx.translate(p.x,p.y); ctx.rotate(p.angle);
      ctx.globalAlpha=p.opacity; ctx.fillStyle=p.color;
      ctx.fillRect(-p.w/2,-p.h/2,p.w,p.h); ctx.restore();
    });
    requestAnimationFrame(draw);
  }
  draw();
})();
