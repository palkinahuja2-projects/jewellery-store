<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow" style="font-family:'Playfair Display',serif;font-size:clamp(1rem,2vw,1.4rem);font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:var(--pink-deep);">✦ Common Questions ✦</p>
  <h2 style="font-family:'Playfair Display',serif;font-size:clamp(2.8rem,5vw,4.5rem);font-weight:400;color:var(--text);">FAQ 🎀</h2>
</div>

<div class="faq-wrap">

  <?php
  $faqs = [
    ["How long does delivery take?","Orders are dispatched within 1–2 business days and arrive within 3–5 days depending on your location. 🚚"],
    ["Is free shipping available?","Yes! We offer free shipping on all orders above ₹999. Orders below that have a small flat fee. 💕"],
    ["Can I return or exchange my jewellery?","We accept returns within 7 days of delivery, provided the item is unused and in original packaging. Please contact us to initiate a return. 🔄"],
    ["What materials are used?","Our pieces use premium metals and high-quality stones. Each product description mentions specific materials. 💎"],
    ["How do I care for my jewellery?","Store in a cool, dry place. Avoid contact with perfume, lotions, and chemicals. Clean gently with a soft cloth. 🌸"],
    ["Do you do gift wrapping?","Yes! We offer beautiful gift wrapping on request. Just mention it at checkout or contact us. 🎀"],
    ["Can I track my order?","Once dispatched, you'll receive a tracking number via SMS. You can also contact us directly for updates. 📦"],

  ];
  foreach($faqs as $faq):
  ?>
  <div class="faq-item">
    <div class="faq-question">
      <?php echo $faq[0]; ?>
      <span class="faq-icon">+</span>
    </div>
    <div class="faq-answer"><?php echo $faq[1]; ?></div>
  </div>
  <?php endforeach; ?>

  <div style="text-align:center;margin-top:3rem;padding:2.5rem;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);">
    <div style="font-size:2.5rem;margin-bottom:1rem;">🎀</div>
    <p style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:0.6rem;color:var(--text);">Still have a question?</p>
    <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1.5rem;">We're just a message away!</p>
    <a href="contact.php"><button class="btn-primary">Contact Us 💌</button></a>
  </div>

</div>

<script>
document.querySelectorAll('.faq-item').forEach(item => {
  item.querySelector('.faq-question').addEventListener('click', () => {
    const wasOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if(!wasOpen) item.classList.add('open');
  });
});
</script>

</body>
</html>