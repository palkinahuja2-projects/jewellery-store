<?php
session_start();
include 'include/db.php';
include 'include/razorpay_config.php';

// ── Collect cart total for display ──
$total = 0;
$cart_rows = [];
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $id  = intval($id);
        $qty = intval($qty);
        $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $id LIMIT 1");
        $p   = mysqli_fetch_assoc($res);
        if (!$p) continue;
        $sub        = $p['price'] * $qty;
        $total     += $sub;
        $cart_rows[] = ['product' => $p, 'qty' => $qty, 'sub' => $sub];
    }
}

if (empty($cart_rows)) {
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout — LY Jewels</title>
  <meta name="description" content="Complete your jewellery order securely at LY Jewels.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&display=swap">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* ── Checkout Layout ── */
    .checkout-page { max-width:1100px; margin:0 auto; padding:3rem 2rem; display:grid; grid-template-columns:1fr 420px; gap:2.5rem; align-items:start; }
    @media(max-width:900px){ .checkout-page{ grid-template-columns:1fr; } }

    /* ── Form Card ── */
    .checkout-card {
      background:var(--surface);
      border:1px solid var(--border);
      border-radius:20px;
      padding:2.5rem;
      box-shadow:0 8px 40px var(--shadow);
    }
    .checkout-card h2 {
      font-family:'Playfair Display',serif;
      font-size:1.6rem; font-weight:400;
      color:var(--text); margin-bottom:0.3rem;
    }
    .checkout-card .card-sub {
      font-size:0.82rem; color:var(--muted);
      margin-bottom:2rem; display:block;
    }

    /* ── Form Fields ── */
    .field-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    @media(max-width:600px){ .field-row{ grid-template-columns:1fr; } }
    .field { margin-bottom:1.2rem; }
    .field label {
      display:block; font-size:0.67rem;
      letter-spacing:0.18em; text-transform:uppercase;
      color:var(--muted); margin-bottom:6px; font-weight:500;
    }
    .field input, .field textarea {
      width:100%; background:var(--bg);
      border:1.5px solid var(--border);
      color:var(--text); padding:12px 16px;
      font-family:'DM Sans',sans-serif; font-size:0.9rem;
      outline:none; border-radius:12px;
      transition:all 0.25s ease;
    }
    .field input:focus, .field textarea:focus {
      border-color:var(--pink);
      box-shadow:0 0 0 3px rgba(232,114,154,0.12);
      background:var(--surface);
    }
    .field textarea { height:90px; resize:vertical; }
    .field .input-icon { position:relative; }
    .field .input-icon span {
      position:absolute; left:14px; top:50%;
      transform:translateY(-50%); font-size:1rem; pointer-events:none;
    }
    .field .input-icon input { padding-left:40px; }

    /* ── Pay Button ── */
    #pay-btn {
      width:100%; padding:16px;
      background:linear-gradient(135deg,var(--pink),var(--pink-deep));
      color:white; border:none; border-radius:50px;
      font-family:'Cormorant Garamond',serif;
      font-size:1.2rem; font-weight:600; letter-spacing:0.08em;
      cursor:pointer; margin-top:1rem;
      box-shadow:0 6px 24px var(--shadow2);
      transition:all 0.3s ease;
      display:flex; align-items:center; justify-content:center; gap:10px;
    }
    #pay-btn:hover { transform:translateY(-3px); box-shadow:0 12px 32px var(--shadow2); }
    #pay-btn:disabled { opacity:0.6; cursor:not-allowed; transform:none; }
    #pay-btn .spinner {
      width:18px; height:18px;
      border:2px solid rgba(255,255,255,0.4);
      border-top-color:white; border-radius:50%;
      animation:spin 0.8s linear infinite; display:none;
    }
    @keyframes spin { to{ transform:rotate(360deg); } }
    #pay-btn.loading .spinner { display:block; }
    #pay-btn.loading .btn-text { display:none; }

    .secure-note {
      text-align:center; font-size:0.75rem; color:var(--muted);
      margin-top:1rem; display:flex; align-items:center;
      justify-content:center; gap:6px;
    }

    /* ── Order Summary Card ── */
    .order-summary-card {
      background:var(--surface);
      border:1px solid var(--border);
      border-radius:20px; padding:2rem;
      box-shadow:0 8px 40px var(--shadow);
      position:sticky; top:160px;
    }
    .order-summary-card h3 {
      font-family:'Playfair Display',serif;
      font-size:1.2rem; font-weight:400;
      color:var(--text); margin-bottom:1.5rem;
      padding-bottom:1rem; border-bottom:1px solid var(--border);
    }
    .summary-item {
      display:flex; justify-content:space-between;
      align-items:center; padding:10px 0;
      border-bottom:1px solid var(--border);
      gap:1rem;
    }
    .summary-item:last-of-type { border-bottom:none; }
    .summary-item-left { display:flex; align-items:center; gap:10px; }
    .summary-thumb {
      width:52px; height:52px; border-radius:10px;
      object-fit:cover; border:1px solid var(--border);
      flex-shrink:0;
    }
    .summary-name {
      font-family:'Playfair Display',serif;
      font-size:0.88rem; color:var(--text);
    }
    .summary-qty { font-size:0.75rem; color:var(--muted); margin-top:2px; }
    .summary-price { color:var(--pink-deep); font-weight:500; font-size:0.92rem; white-space:nowrap; }

    .summary-divider { border:none; border-top:2px solid var(--border2); margin:1rem 0; }
    .summary-row { display:flex; justify-content:space-between; font-size:0.85rem; color:var(--text2); margin-bottom:0.5rem; }
    .summary-total {
      display:flex; justify-content:space-between;
      font-family:'Playfair Display',serif;
      font-size:1.5rem; color:var(--pink-deep);
      margin-top:0.5rem;
    }
    .summary-shipping {
      font-size:0.75rem; color:#4caf50; margin-top:0.4rem;
      text-align:right;
    }

    /* ── Error message ── */
    .pay-error {
      background:rgba(220,50,50,0.08);
      border:1px solid rgba(220,50,50,0.3);
      color:#c0392b; border-radius:10px;
      padding:0.8rem 1rem; font-size:0.85rem;
      margin-top:1rem; display:none;
    }
    .pay-error.show { display:block; }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow">✦ Almost There ✦</p>
  <h2>Secure Checkout 💳</h2>
</div>

<div class="checkout-page">

  <!-- ── LEFT: Customer Form ── -->
  <div class="checkout-card">
    <h2>Delivery & Payment</h2>
    <span class="card-sub">Fill in your details and pay securely via Razorpay</span>

    <form id="checkout-form" novalidate>

      <div class="field-row">
        <div class="field">
          <label>Full Name *</label>
          <div class="input-icon">
            <span>👤</span>
            <input type="text" id="cust-name" name="name" placeholder="Priya Sharma" required>
          </div>
        </div>
        <div class="field">
          <label>Phone Number *</label>
          <div class="input-icon">
            <span>📱</span>
            <input type="tel" id="cust-phone" name="phone" placeholder="+91 98765 43210" required maxlength="13">
          </div>
        </div>
      </div>

      <div class="field">
        <label>Email Address *</label>
        <div class="input-icon">
          <span>✉️</span>
          <input type="email" id="cust-email" name="email" placeholder="priya@example.com" required>
        </div>
      </div>

      <div class="field">
        <label>Delivery Address *</label>
        <textarea id="cust-address" name="address" placeholder="Flat No, Street, City, State, PIN Code" required></textarea>
      </div>

      <button type="submit" id="pay-btn">
        <div class="spinner"></div>
        <span class="btn-text">🔒 Pay ₹<?php echo number_format($total, 2); ?> Securely</span>
      </button>

      <div class="pay-error" id="pay-error"></div>

      <p class="secure-note">
        🔐 256-bit SSL encrypted &nbsp;|&nbsp; Powered by Razorpay
      </p>
    </form>
  </div>

  <!-- ── RIGHT: Order Summary ── -->
  <div class="order-summary-card">
    <h3>🛍️ Your Order (<?php echo count($cart_rows); ?> item<?php echo count($cart_rows) > 1 ? 's' : ''; ?>)</h3>

    <?php foreach ($cart_rows as $row): ?>
    <div class="summary-item">
      <div class="summary-item-left">
        <img src="<?php echo htmlspecialchars($row['product']['image']); ?>"
             alt="<?php echo htmlspecialchars($row['product']['name']); ?>"
             class="summary-thumb">
        <div>
          <div class="summary-name"><?php echo htmlspecialchars($row['product']['name']); ?></div>
          <div class="summary-qty">Qty: <?php echo $row['qty']; ?></div>
        </div>
      </div>
      <div class="summary-price">₹<?php echo number_format($row['sub'], 2); ?></div>
    </div>
    <?php endforeach; ?>

    <hr class="summary-divider">

    <div class="summary-row">
      <span>Subtotal</span>
      <span>₹<?php echo number_format($total, 2); ?></span>
    </div>
    <div class="summary-row">
      <span>Shipping</span>
      <span><?php echo $total >= 999 ? '<span style="color:#4caf50">FREE</span>' : '₹49.00'; ?></span>
    </div>

    <?php
      $shipping = $total >= 999 ? 0 : 49;
      $grand    = $total + $shipping;
    ?>

    <hr class="summary-divider">

    <div class="summary-total">
      <span>Total</span>
      <span>₹<?php echo number_format($grand, 2); ?></span>
    </div>
    <?php if ($total >= 999): ?>
    <p class="summary-shipping">✓ Free shipping applied! 🎉</p>
    <?php else: ?>
    <p class="summary-shipping" style="color:var(--muted);">Add ₹<?php echo 999 - $total; ?> more for free shipping</p>
    <?php endif; ?>
  </div>

</div>

<!-- Razorpay JS SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
  e.preventDefault();

  // ── Validate fields ──
  const name    = document.getElementById('cust-name').value.trim();
  const phone   = document.getElementById('cust-phone').value.trim();
  const email   = document.getElementById('cust-email').value.trim();
  const address = document.getElementById('cust-address').value.trim();
  const errBox  = document.getElementById('pay-error');

  errBox.classList.remove('show');

  if (!name || !phone || !email || !address) {
    errBox.textContent = '⚠️ Please fill in all required fields.';
    errBox.classList.add('show');
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    errBox.textContent = '⚠️ Please enter a valid email address.';
    errBox.classList.add('show');
    return;
  }
  if (phone.replace(/\D/g,'').length < 10) {
    errBox.textContent = '⚠️ Please enter a valid 10-digit phone number.';
    errBox.classList.add('show');
    return;
  }

  // ── Show loading state ──
  const btn = document.getElementById('pay-btn');
  btn.classList.add('loading');
  btn.disabled = true;

  try {
    // ── Step 1: Create Razorpay order (server-side) ──
    const res = await fetch('create_razorpay_order.php', { method: 'POST' });
    const order = await res.json();

    if (order.error) {
      throw new Error(order.error + (order.details ? ': ' + order.details : ''));
    }

    // ── Step 2: Open Razorpay payment modal ──
    const options = {
      key:          order.key_id,
      amount:       order.amount,
      currency:     order.currency,
      name:         order.name,
      description:  order.description,
      order_id:     order.order_id,
      prefill: {
        name:    name,
        email:   email,
        contact: phone,
      },
      theme: { color: order.theme_color },
      modal: {
        ondismiss: function() {
          btn.classList.remove('loading');
          btn.disabled = false;
        }
      },
      // ── Step 3: On payment success → verify on server ──
      handler: function(response) {
        btn.classList.add('loading');
        btn.disabled = true;

        // Build hidden form and POST to payment_verify.php
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'payment_verify.php';

        const fields = {
          razorpay_payment_id: response.razorpay_payment_id,
          razorpay_order_id:   response.razorpay_order_id,
          razorpay_signature:  response.razorpay_signature,
          customer_name:       name,
          customer_email:      email,
          customer_phone:      phone,
          customer_address:    address,
          total_amount:        '<?php echo $grand; ?>',
        };

        Object.entries(fields).forEach(([k, v]) => {
          const inp = document.createElement('input');
          inp.type = 'hidden';
          inp.name = k;
          inp.value = v;
          form.appendChild(inp);
        });

        document.body.appendChild(form);
        form.submit();
      },
    };

    const rzp = new Razorpay(options);
    rzp.on('payment.failed', function(resp) {
      btn.classList.remove('loading');
      btn.disabled = false;
      errBox.textContent = '❌ Payment failed: ' + (resp.error.description || 'Please try again.');
      errBox.classList.add('show');
    });

    btn.classList.remove('loading');
    btn.disabled = false;
    rzp.open();

  } catch (err) {
    btn.classList.remove('loading');
    btn.disabled = false;
    errBox.textContent = '❌ ' + err.message;
    errBox.classList.add('show');
  }
});
</script>

</body>
</html>