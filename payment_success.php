<?php
session_start();
include 'include/db.php';

// Must come from payment_verify.php
if (empty($_SESSION['order_success'])) {
    header('Location: index.php');
    exit;
}

$info = $_SESSION['order_success'];
unset($_SESSION['order_success']); // one-time view
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Successful — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .success-page {
      min-height:80vh; display:flex; flex-direction:column;
      align-items:center; justify-content:center;
      padding:4rem 2rem; text-align:center;
    }
    .success-icon-wrap {
      width:110px; height:110px;
      background:linear-gradient(135deg,#e8f5e9,#c8e6c9);
      border-radius:50%; display:flex; align-items:center;
      justify-content:center; font-size:3.2rem;
      margin-bottom:2rem;
      box-shadow:0 8px 32px rgba(76,175,80,0.2);
      animation:successPop 0.6s cubic-bezier(0.34,1.6,0.64,1) both;
    }
    @keyframes successPop {
      from { transform:scale(0) rotate(-20deg); opacity:0; }
      to   { transform:scale(1) rotate(0deg);   opacity:1; }
    }
    .success-title {
      font-family:'Playfair Display',serif;
      font-size:clamp(2rem,4vw,3rem); font-weight:400;
      color:var(--text); margin-bottom:0.8rem;
      animation:fadeUp 0.6s 0.3s ease both;
    }
    .success-title em { color:#4caf50; font-style:italic; }
    .success-sub {
      color:var(--text2); font-size:0.95rem;
      line-height:1.8; max-width:480px;
      margin:0 auto 2.5rem;
      animation:fadeUp 0.6s 0.45s ease both;
    }
    .order-info-box {
      background:var(--surface);
      border:1px solid var(--border);
      border-radius:16px; padding:1.8rem 2.5rem;
      margin-bottom:2.5rem; text-align:left;
      box-shadow:0 4px 20px var(--shadow);
      animation:fadeUp 0.6s 0.55s ease both;
    }
    .order-info-box h4 {
      font-family:'Playfair Display',serif;
      font-size:1rem; color:var(--text);
      margin-bottom:1rem; padding-bottom:0.8rem;
      border-bottom:1px solid var(--border);
    }
    .order-info-row {
      display:flex; justify-content:space-between;
      gap:2rem; font-size:0.85rem; color:var(--text2);
      margin-bottom:0.6rem;
    }
    .order-info-row strong { color:var(--text); }
    .success-actions {
      display:flex; gap:12px; flex-wrap:wrap;
      justify-content:center;
      animation:fadeUp 0.6s 0.65s ease both;
    }
    .confetti { position:fixed; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:9999; overflow:hidden; }
    .confetti-piece {
      position:absolute; width:10px; height:10px;
      border-radius:2px; animation:confettiFall linear forwards;
    }
    @keyframes confettiFall {
      0%   { transform:translateY(-20px) rotate(0deg);   opacity:1; }
      100% { transform:translateY(100vh) rotate(720deg); opacity:0; }
    }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<!-- Confetti -->
<div class="confetti" id="confetti"></div>

<div class="success-page">
  <div class="success-icon-wrap">✅</div>

  <h1 class="success-title">
    Payment <em>Successful!</em>
  </h1>

  <p class="success-sub">
    Thank you, <strong><?php echo htmlspecialchars($info['name']); ?></strong>! 🎀<br>
    Your jewellery order has been placed. A confirmation will be sent to
    <strong><?php echo htmlspecialchars($info['email']); ?></strong>.
  </p>

  <div class="order-info-box">
    <h4>🧾 Order Details</h4>
    <div class="order-info-row">
      <span>Order ID</span>
      <strong>#LY-<?php echo str_pad($info['db_id'], 5, '0', STR_PAD_LEFT); ?></strong>
    </div>
    <div class="order-info-row">
      <span>Payment ID</span>
      <strong><?php echo htmlspecialchars($info['payment_id']); ?></strong>
    </div>
    <div class="order-info-row">
      <span>Amount Paid</span>
      <strong style="color:var(--pink-deep);">₹<?php echo number_format($info['amount'], 2); ?></strong>
    </div>
    <div class="order-info-row">
      <span>Status</span>
      <strong style="color:#4caf50;">✓ Confirmed</strong>
    </div>
  </div>

  <div class="success-actions">
    <a href="products.php"><button class="btn-outline" style="padding:13px 30px;">Continue Shopping 🛍️</button></a>
    <a href="index.php"><button class="btn-primary" style="padding:13px 30px;">Back to Home 🏠</button></a>
  </div>
</div>

<script>
// Confetti burst
(function() {
  const colors = ['#e8729a','#f4a0bc','#c45080','#ffb3cc','#ff6b9d','#ffd4e8'];
  const container = document.getElementById('confetti');
  for (let i = 0; i < 80; i++) {
    const el = document.createElement('div');
    el.className = 'confetti-piece';
    el.style.cssText = `
      left:${Math.random()*100}%;
      background:${colors[Math.floor(Math.random()*colors.length)]};
      width:${6 + Math.random()*8}px;
      height:${6 + Math.random()*8}px;
      animation-duration:${2 + Math.random()*3}s;
      animation-delay:${Math.random()*1.5}s;
      border-radius:${Math.random() > 0.5 ? '50%' : '2px'};
    `;
    container.appendChild(el);
  }
  setTimeout(() => container.remove(), 6000);
})();
</script>
</body>
</html>
