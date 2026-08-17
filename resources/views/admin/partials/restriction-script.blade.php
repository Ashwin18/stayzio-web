<script>
document.addEventListener("DOMContentLoaded", function () {
  var idx = document.querySelectorAll(".restriction-item").length;
  var addBtn = document.getElementById("add-restriction");
  if (!addBtn) return;
  addBtn.addEventListener("click", function () {
    var wrap = document.getElementById("restrictions-wrapper");
    var item = document.createElement("div");
    item.className = "restriction-item";
    item.style.cssText = "background:var(--navy3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:8px";
    item.innerHTML = [
      "<div style=\"display:grid;grid-template-columns:70px 1fr 140px 38px;gap:8px;align-items:end\">",
      "<div class=\"fg\"><label class=\"flabel\">Icon</label>",
      "<input type=\"text\" class=\"fc\" name=\"restrictions[" + idx + "][icon]\" placeholder=\"Icon\"></div>",
      "<div class=\"fg\"><label class=\"flabel\">Rule</label>",
      "<input type=\"text\" class=\"fc\" name=\"restrictions[" + idx + "][title]\" placeholder=\"No Pets\"></div>",
      "<div class=\"fg\"><label class=\"flabel\">Type</label>",
      "<select class=\"fc\" name=\"restrictions[" + idx + "][type]\">",
      "<option value=\"allowed\">Allowed</option>",
      "<option value=\"limited\">Limited</option>",
      "<option value=\"restricted\">Restricted</option>",
      "<option value=\"not_allowed\" selected>Not Allowed</option>",
      "</select></div>",
      "<div style=\"display:flex;align-items:flex-end\">",
      "<button type=\"button\" class=\"btn btn-danger btn-sm remove-restriction\" style=\"width:38px;height:38px;padding:0;justify-content:center;border-radius:8px\">&times;</button>",
      "</div></div>"
    ].join("");
    wrap.appendChild(item);
    idx++;
  });
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-restriction")) {
      e.target.closest(".restriction-item").remove();
    }
  });
});
</script>
