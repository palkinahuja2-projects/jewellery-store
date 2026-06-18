<?php
include 'include/db.php';
$sent = false;
if(isset($_POST['submit'])) {
    $name    = clean($conn, $_POST['name']);
    $email   = clean($conn, $_POST['email']);
    $message = clean($conn, $_POST['message']);
    mysqli_query($conn, "INSERT INTO contact_messages (name,email,message) VALUES ('$name','$email','$message')");
    $sent = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow" style="font-family:'Playfair Display',serif;font-size:clamp(1rem,2vw,1.4rem);font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:var(--pink-deep);">✦ Say Hello ✦</p>
  <h2 style="font-size:clamp(2.8rem,5vw,4.5rem);">Contact Us 💌</h2>
</div>

<div class="contact-layout">

  <div class="contact-info">
    <h3>We'd Love to Hear from You</h3>
    <p>Have a question about your order, a piece of jewellery, or just want to say hello? We're here and happy to help! 💕</p>

    <div class="contact-card">
      <div class="contact-card-icon">✉️</div>
      <div><strong>Email</strong><span>ly.jewels30@email.com</span></div>
    </div>

    <div class="contact-card">
      <div class="contact-card-icon">📸</div>
      <div><strong>Instagram</strong><span>_ly.jewels_</span></div>
    </div>

    <div class="contact-card">
      <div class="contact-card-icon">📍</div>
      <div><strong>Location</strong><span>Jalandhar, India</span></div>
    </div>

    <div class="contact-card">
      <div class="contact-card-icon">⏰</div>
      <div><strong>Response Time</strong><span>Within 24 hours 🎀</span></div>
    </div>
  </div>

  <div class="contact-form-wrap">
    <h3>Send Us a Message 💕</h3>

    <?php if($sent): ?>
    <div class="alert alert-success">✓ Message sent! We'll get back to you within 24 hours. 🎀</div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Your Name</label>
        <input type="text" name="name" placeholder="Full name" required>
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required>
      </div>
      <div class="form-group">
        <label>Message</label>
        <textarea name="message" placeholder="How can we help you? 💕" required style="height:130px;"></textarea>
      </div>
      <button type="submit" name="submit" class="btn-primary" style="width:100%;padding:14px;">Send Message 💌</button>
    </form>
  </div>

</div>

</body>
</html>