<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Policy — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .policy-page {
      max-width: 820px;
      margin: 0 auto;
      padding: 2rem 2rem 5rem;
    }
    .policy-toc {
      background: var(--surface);
      border: 1px solid var(--border2);
      border-radius: var(--radius);
      padding: 1.5rem 2rem;
      margin-bottom: 2.5rem;
      box-shadow: 0 2px 12px rgba(214,140,160,0.07);
    }
    .policy-toc h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--text);
      margin-bottom: 0.8rem;
    }
    .policy-toc ol {
      padding-left: 1.2rem;
      color: var(--muted);
      font-size: 0.84rem;
      line-height: 2;
    }
    .policy-toc ol a { color: var(--pink); text-decoration: none; }
    .policy-toc ol a:hover { text-decoration: underline; }

    .policy-section {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 2rem 2.2rem;
      margin-bottom: 1.4rem;
      box-shadow: 0 2px 12px rgba(214,140,160,0.05);
      animation: fadeUp 0.4s ease both;
    }
    @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
    .policy-section:nth-child(2){animation-delay:0.05s}
    .policy-section:nth-child(3){animation-delay:0.1s}
    .policy-section:nth-child(4){animation-delay:0.15s}
    .policy-section:nth-child(5){animation-delay:0.2s}

    .policy-section-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 1.2rem;
      padding-bottom: 0.8rem;
      border-bottom: 1px solid var(--border);
    }
    .policy-icon {
      width: 44px; height: 44px;
      border-radius: 10px;
      background: var(--pink-soft, #f9eef1);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; flex-shrink: 0;
    }
    .policy-section-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem;
      color: var(--text);
    }
    .policy-section p {
      color: var(--text2);
      font-size: 0.88rem;
      line-height: 1.8;
      margin-bottom: 0.8rem;
    }
    .policy-section p:last-child { margin-bottom: 0; }
    .policy-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 0.84rem;
      margin-top: 0.8rem;
    }
    .policy-table th {
      text-align: left;
      padding: 9px 14px;
      background: var(--bg2, #fdf8f9);
      font-size: 0.74rem;
      font-weight: 600;
      letter-spacing: 0.07em;
      text-transform: uppercase;
      color: var(--muted);
      border-bottom: 1px solid var(--border);
    }
    .policy-table td {
      padding: 10px 14px;
      border-bottom: 1px solid var(--border);
      color: var(--text2);
    }
    .policy-table tr:last-child td { border: none; }
    .policy-table tr:hover td { background: var(--pink-soft, #f9eef1); }
    .policy-highlight {
      background: var(--pink-soft, #f9eef1);
      border: 1px solid var(--border2);
      border-radius: 10px;
      padding: 1rem 1.2rem;
      font-size: 0.84rem;
      color: var(--text2);
      margin-top: 0.8rem;
      line-height: 1.7;
    }
    .policy-list {
      padding-left: 1.2rem;
      color: var(--text2);
      font-size: 0.88rem;
      line-height: 2;
    }
    .policy-list li::marker { color: var(--pink); }
    .policy-badge {
      display: inline-block;
      padding: 2px 10px;
      background: var(--pink-soft,#f9eef1);
      border: 1px solid var(--border2);
      border-radius: 20px;
      font-size: 0.74rem;
      font-weight: 600;
      color: var(--pink-dark,#b8687e);
      margin-right: 4px;
    }
    .policy-updated {
      text-align: center;
      font-size: 0.75rem;
      color: var(--muted);
      margin-top: 2rem;
    }
    .contact-cta {
      background: linear-gradient(135deg, var(--pink-soft,#f9eef1) 0%, #fce4ec 100%);
      border: 1px solid var(--border2);
      border-radius: var(--radius);
      padding: 2rem;
      text-align: center;
      margin-top: 2rem;
    }
    .contact-cta h3 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--text); margin-bottom:0.5rem; }
    .contact-cta p  { color:var(--muted); font-size:0.84rem; margin-bottom:1.2rem; }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow">✦ Transparency First ✦</p>
  <h2>Shopping Policy 🛍️</h2>
</div>

<div class="policy-page">

  <!-- Table of Contents -->
  <div class="policy-toc">
    <h3>📋 On This Page</h3>
    <ol>
      <li><a href="#shipping">Shipping & Delivery</a></li>
      <li><a href="#returns">Returns & Exchanges</a></li>
      <li><a href="#payment">Payment & Pricing</a></li>
      <li><a href="#care">Jewellery Care</a></li>
      <li><a href="#privacy">Privacy & Security</a></li>
      <li><a href="#custom">Custom Orders</a></li>
    </ol>
  </div>

  <!-- Section 1: Shipping -->
  <div class="policy-section" id="shipping">
    <div class="policy-section-header">
      <div class="policy-icon">🚚</div>
      <div class="policy-section-title">Shipping &amp; Delivery</div>
    </div>
    <p>We take great care in packing and dispatching every order with love. Here's what you can expect:</p>
    <table class="policy-table">
      <thead>
        <tr><th>Order Value</th><th>Shipping</th><th>Estimated Delivery</th></tr>
      </thead>
      <tbody>
        <tr><td>Below ₹999</td><td>₹50 flat fee</td><td>3–5 business days</td></tr>
        <tr><td>₹999 and above</td><td><strong>FREE 🎉</strong></td><td>3–5 business days</td></tr>
        <tr><td>Express (on request)</td><td>₹120</td><td>1–2 business days</td></tr>
      </tbody>
    </table>
    <div class="policy-highlight">
      📦 <strong>Dispatch Time:</strong> Orders are processed and dispatched within <strong>1–2 business days</strong>.
      Once dispatched, you'll receive a tracking number via SMS/WhatsApp.
      Delivery times may vary during festivals and sale seasons.
    </div>
  </div>

  <!-- Section 2: Returns -->
  <div class="policy-section" id="returns">
    <div class="policy-section-header">
      <div class="policy-icon">🔄</div>
      <div class="policy-section-title">Returns &amp; Exchanges</div>
    </div>
    <p>We want you to absolutely love your jewellery. If something isn't right, here's how we handle it:</p>
    <ul class="policy-list">
      <li>Returns accepted within <strong>7 days</strong> of delivery.</li>
      <li>Item must be <strong>unused, unworn</strong>, and in original packaging.</li>
      <li>Contact us first via the <a href="contact.php" style="color:var(--pink);">Contact page</a> to initiate a return.</li>
      <li>Refunds are processed within <strong>5–7 business days</strong> to original payment method.</li>
      <li>Custom and personalised orders are <strong>non-returnable</strong>.</li>
      <li>Items damaged in transit must be reported with photos within <strong>48 hours</strong>.</li>
    </ul>
    <div class="policy-highlight">
      💡 <strong>Exchanges:</strong> We're happy to exchange for a different size or style. Just reach out and we'll sort it out for you! 🎀
    </div>
  </div>

  <!-- Section 3: Payment -->
  <div class="policy-section" id="payment">
    <div class="policy-section-header">
      <div class="policy-icon">💳</div>
      <div class="policy-section-title">Payment &amp; Pricing</div>
    </div>
    <p>All prices on LY Jewels are listed in <strong>Indian Rupees (₹)</strong> and are inclusive of applicable taxes.</p>
    <p>We accept the following payment methods:</p>
    <p>
      <span class="policy-badge">💳 UPI</span>
      <span class="policy-badge">🏦 Net Banking</span>
      <span class="policy-badge">💰 Debit/Credit Card</span>
      <span class="policy-badge">📱 Wallets</span>
      <span class="policy-badge">🤝 Cash on Delivery</span>
    </p>
    <div class="policy-highlight" style="margin-top:1rem;">
      🔒 All transactions are secured with SSL encryption. We do <strong>not</strong> store your card details.
      Prices are subject to change during sales and promotional periods.
    </div>
  </div>

  <!-- Section 4: Care -->
  <div class="policy-section" id="care">
    <div class="policy-section-header">
      <div class="policy-icon">💎</div>
      <div class="policy-section-title">Jewellery Care Guide</div>
    </div>
    <p>To keep your LY Jewels pieces sparkling for years, please follow these care tips:</p>
    <ul class="policy-list">
      <li>Store in a <strong>cool, dry place</strong>, preferably in the pouch or box provided.</li>
      <li>Avoid contact with <strong>perfume, lotions, hairspray, and chemicals</strong>.</li>
      <li>Remove jewellery before <strong>swimming, bathing, or exercising</strong>.</li>
      <li>Clean gently with a <strong>soft, dry cloth</strong> after each wear.</li>
      <li>Keep pieces <strong>separated</strong> to avoid scratching.</li>
      <li>Sterling silver may tarnish over time — polish with a silver cloth to restore shine.</li>
    </ul>
  </div>

  <!-- Section 5: Privacy -->
  <div class="policy-section" id="privacy">
    <div class="policy-section-header">
      <div class="policy-icon">🔐</div>
      <div class="policy-section-title">Privacy &amp; Security</div>
    </div>
    <p>Your privacy matters to us. We collect only the information necessary to process your order and improve your shopping experience.</p>
    <ul class="policy-list">
      <li>Your personal information is <strong>never sold or shared</strong> with third parties.</li>
      <li>We use your email only for <strong>order updates and (optional) newsletters</strong>.</li>
      <li>You can request deletion of your account data at any time via <a href="contact.php" style="color:var(--pink);">contact us</a>.</li>
      <li>Our website uses cookies only for essential shopping functionality.</li>
    </ul>
  </div>

  <!-- Section 6: Custom Orders -->
  <div class="policy-section" id="custom">
    <div class="policy-section-header">
      <div class="policy-icon">✨</div>
      <div class="policy-section-title">Custom Orders</div>
    </div>
    <p>We love creating one-of-a-kind pieces! Here's how custom orders work:</p>
    <ul class="policy-list">
      <li>Contact us with your design idea, preferred material, and budget.</li>
      <li>We'll share a design sketch and price quote within <strong>2–3 business days</strong>.</li>
      <li>A <strong>50% advance payment</strong> is required to begin crafting.</li>
      <li>Custom pieces typically take <strong>7–14 business days</strong> to complete.</li>
      <li>Custom orders are <strong>non-refundable</strong> once production begins.</li>
    </ul>
    <div class="policy-highlight">
      🎀 Custom orders make the most beautiful gifts! Start with a message on our <a href="contact.php" style="color:var(--pink-dark,#b8687e);">Contact page</a>.
    </div>
  </div>

  <!-- CTA -->
  <div class="contact-cta">
    <div style="font-size:2.5rem;margin-bottom:0.8rem;">💌</div>
    <h3>Still have questions?</h3>
    <p>Our team is happy to help with any policy queries.</p>
    <a href="contact.php"><button class="btn-primary">Contact Us 🎀</button></a>
    &nbsp;
    <a href="faq.php"><button class="btn-outline">View FAQ</button></a>
  </div>

  <div class="policy-updated">Last updated: January 2025 · LY Jewels</div>

</div>

</body>
</html>