<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Submitted — StayZio</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Poppins',sans-serif;background:#f8fafc;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px}
    .card{background:#fff;border-radius:24px;box-shadow:0 8px 40px rgba(0,0,0,.08);max-width:480px;width:100%;padding:40px 36px;text-align:center}
    .icon-wrap{width:80px;height:80px;background:#fff1f1;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px}
    .icon-wrap i{font-size:36px;color:#e31e24}
    h1{font-size:22px;font-weight:900;color:#0c0c0e;margin-bottom:10px}
    p{font-size:13px;color:#64748b;line-height:1.7;margin-bottom:24px}
    .steps{display:flex;flex-direction:column;gap:10px;margin-bottom:28px;text-align:left}
    .step{display:flex;align-items:flex-start;gap:12px;padding:12px 14px;background:#f8fafc;border-radius:10px;border:1px solid #e8eaed}
    .step-num{width:24px;height:24px;background:#e31e24;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;margin-top:1px}
    .step-text{font-size:12px;color:#374151;font-weight:600;line-height:1.5}
    .step-text small{display:block;color:#94a3b8;font-weight:400;margin-top:2px}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:13px 24px;border-radius:12px;font-size:13px;font-weight:800;text-decoration:none;transition:.2s}
    .btn-primary{background:#e31e24;color:#fff;box-shadow:0 4px 16px rgba(227,30,36,.3)}
    .btn-secondary{background:#f7f8fa;color:#374151;border:1.5px solid #e8eaed;margin-top:10px}
    .brand{margin-bottom:28px}
    .brand span{font-size:28px;font-weight:900;color:#0c0c0e}
    .brand span em{color:#e31e24;font-style:normal}
  </style>
</head>
<body>
  <div class="card">

    <div class="brand"><span>Stay<em>Zio</em></span></div>

    <div class="icon-wrap">
      <i class="fas fa-check"></i>
    </div>

    <h1>Registration Submitted!</h1>
    <p>Thank you for partnering with StayZio. Your application has been received and is under review by our team.</p>

    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div class="step-text">Application received
          <small>Your details are securely saved in our system</small>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-text">Admin review
          <small>Our team will verify your hotel details</small>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-text">Activation
          <small>Once approved, you'll receive login credentials via email</small>
        </div>
      </div>
    </div>

    <a href="/" class="btn btn-primary"><i class="fas fa-home"></i> Back to Home</a><br>
    <a href="/vendor/login" class="btn btn-secondary"><i class="fas fa-sign-in-alt"></i> Vendor Login</a>

  </div>
</body>
</html>
