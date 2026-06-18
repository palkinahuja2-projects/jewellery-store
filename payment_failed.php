<?php
session_start();
$reason = $_GET['reason'] ?? 'unknown';
$messages = [
    'signature_mismatch' => 'Payment verification failed. Your payment was not captured. Please contact support.',
    'missing_data'       => 'Incomplete payment data received. Please try again.',
    'unknown'            => 'Something went wrong during payment processing.',
];
$msg = $messages[$reason] ?? $messages['unknown'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Failed — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .fail-page {
      min-height:80vh; display:flex; flex-direction:column;
      align-items:center; justify-content:center;
      padding:4rem 2rem; text-align:center;
    }
    .fail-icon-wrap {
      width:110px; height:110px;
      background:linear-gradient(135deg,#ffeaea,#ffcdd2);
      border-radius:50%; display:flex; align-items:center;
      justify-content:center; font-size:3.2rem;
      margin-bottom:2rem;
      box-shadow:0 8px 32px rgba(220,50,50,0.15);
      animation:failPop 0.6s cubic-bezier(0.34,1.6,0.64,1) both;
    }
    @keyframes failPop {
      from { transform:scale(0); opacity:0; }
      to   { transform:scale(1); opacity:1; }
    }
    .fail-title {
      font-family:'Playfair Display',serif;
      font-size:clamp(2rem,4vw,3rem); font-weight:400;
      color:var(--text); margin-bottom:0.8rem;
    }
    .fail-title em { color:#e53935; font-style:italic; }
    .fail-sub {
      color:var(--text2); font-size:0.95rem;
      line-height:1.8; max-width:460px;
      margin:0 auto 1.5rem;
    }
    .fail-reason-box {
      background:rgba(220,50,50,0.06);
      border:1px solid rgba(220,50,50,0.2);
      border-radius:12px; padding:1rem 1.5rem;
      font-size:0.85rem; color:#c0392b;
      margin-bottom:2rem; max-width:440px;
    }
    .fail-actions { display:flex; gap:12px; flex-wrap:wrap; justify-content:center; }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="fail-page">
  <div class="fail-icon-wrap">❌</div>

  <h1 class="fail-title">Payment <em>Failed</em></h1>

  <p class="fail-sub">
    Don't worry — your money is safe. No amount has been deducted.
    Please try again or contact us if the issue persists. 💗
  </p>

  <div class="fail-reason-box">
    ⚠️ <?php echo htmlspecialchars($msg); ?>
  </div>

  <div class="fail-actions">
    <a href="checkout.php"><button class="btn-primary" style="padding:13px 30px;">🔄 Try Again</button></a>
    <a href="cart.php"><button class="btn-outline" style="padding:13px 30px;">🛒 Back to Cart</button></a>
    <a href="contact.php"><button class="btn-outline" style="padding:13px 30px;">📞 Contact Us</button></a>
  </div>
</div>
</body>
</html>
