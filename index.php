<?php include 'include/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LY Jewels — Styled with Love</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Dark Mode Toggle -->
<button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">🌙</button>

<!-- Header -->
<header class="header" id="site-header">
  <div class="nav-container">
    <a href="index.php" class="logo-wrap">
      <img src="images/logo.jpeg" alt="LY Jewels Logo" class="logo-img">
      <div>
        <span class="logo-text">LY Jewels</span>
        <span class="logo-sub">Styled with love</span>
      </div>
    </a>
    <nav>
      <a href="index.php" data-text="Home">Home</a>
      <a href="products.php" data-text="Shop">Shop</a>
      <a href="wishlist.php" data-text="Wishlist"><span class="bow">🎀</span> Wishlist</a>
      <a href="contact.php" data-text="Contact">Contact</a>
      <a href="faq.php" data-text="FAQ">FAQ</a>
      <a href="login.php" data-text="Login">Login</a>
      <a href="reviews.php" data-text="Reviews">Reviews</a>
      <a href="shopping_policy.php" data-text="Policy">Policy</a>
      <a href="cart.php" class="nav-cart">🛒 Cart</a>
    </nav>
  </div>
</header>

<!-- Hero Slider -->
<section class="hero">
  <div class="hero-slide active" style="background-image:url('images/slide1.jpg')"></div>
  <div class="hero-slide" style="background-image:url('images/slide2.jpg')"></div>
  <div class="hero-slide" style="background-image:url('images/slide3.jpg')"></div>
  <div class="hero-overlay"></div>

  <!-- Sparkles -->
  <span class="sparkle">✨</span>
  <span class="sparkle">💎</span>
  <span class="sparkle">🌸</span>

  <div class="hero-content">
    <span class="hero-eyebrow">Welcome to LY Jewels</span>
    <h1 class="hero-title">Jewellery that<br><em>Speaks You</em></h1>
    <p class="hero-sub">Every piece is crafted to make you feel like the most beautiful version of yourself.</p>
    <div class="hero-btns">
      <a href="products.php"><button class="btn-primary">Shop Now 💕</button></a>
    </div>
  </div>

  <div class="hero-dots">
    <div class="hero-dot active" onclick="goSlide(0)"></div>
    <div class="hero-dot" onclick="goSlide(1)"></div>
    <div class="hero-dot" onclick="goSlide(2)"></div>
  </div>
</section>

<!-- Features Strip -->
<div class="features-strip">
  <div class="features-inner">
    <div class="feature-item">
      <span class="feature-icon">🚚</span>
      <div><strong>Free Shipping</strong> on orders above ₹999</div>
    </div>
    <div class="feature-item">
      <span class="feature-icon">💎</span>
      <div><strong>Premium Quality</strong> antitarnished jewellery</div>
    </div>
    <div class="feature-item">
      <span class="feature-icon">🔄</span>
      <div><strong>Easy Returns</strong> within 7 days</div>
    </div>
    <div class="feature-item">
      <span class="feature-icon">💌</span>
      <div><strong>Gift Wrapping</strong> available on request</div>
    </div>
  </div>
</div>

<!-- New Arrivals -->
<div class="container">
  <p class="section-eyebrow just-for-you">✦ Just for You ✦</p>
  <h2 class="section-title">New Arrivals</h2>
  <div class="pink-divider"></div>

  <div class="product-grid">
  <?php
  $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 6");
  $i = 0;
  while($row = mysqli_fetch_assoc($result)):
    $i++;
  ?>
    <div class="product-card fade-up delay-<?php echo min($i,3); ?>">
      <div class="product-img">
        <?php if($i <= 2): ?><div class="product-badge">✨ New</div><?php endif; ?>
        <a href="add_to_wishlist.php?id=<?php echo $row['id']; ?>" class="wishlist-quick">♡</a>
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
      </div>
      <div class="product-info">
        <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
        <div class="product-stars">★★★★★</div>
        <div class="product-price">₹<?php echo htmlspecialchars($row['price']); ?></div>
        <div class="btn-group">
          <a href="product.php?id=<?php echo $row['id']; ?>">
            <button class="btn-outline">View</button>
          </a>
          <a href="add_to_cart.php?id=<?php echo $row['id']; ?>">
            <button class="btn-primary">Add to Cart</button>
          </a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  </div>

  <div style="text-align:center;margin-top:3rem;">
    <a href="products.php"><button class="btn-outline" style="padding:13px 40px;font-size:0.82rem;">View Full Collection 🎀</button></a>
  </div>
</div>

<!-- Categories Section -->
<div style="background:var(--bg2);padding:4rem 3rem;border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
  <div style="max-width:1200px;margin:0 auto;">
    <p class="section-eyebrow">✦ Browse by Style ✦</p>
    <h2 class="section-title">Shop by Category</h2>
    <div class="pink-divider"></div>
    <div style="display:flex;flex-wrap:wrap;gap:16px;justify-content:center;margin-top:1rem;">
      <?php
      $cats = [
        ['rings','💍 Rings'],['earrings','💎 Earrings'],['bracelets','✨ Bracelets'],
        ['pendants','🌸 Pendants'],['studs','🔮 Studs'],['jhumkas','🎀 Jhumkas'],['others','💕 Others']
      ];
      foreach($cats as [$slug,$label]): ?>
      <a href="products.php?category=<?php echo $slug; ?>"
         style="padding:16px 32px;background:var(--surface);border:1.5px solid var(--border2);border-radius:50px;font-size:1rem;font-weight:500;color:var(--text2);transition:all 0.3s ease;letter-spacing:0.04em;"
         onmouseover="this.style.background='var(--pink)';this.style.color='white';this.style.borderColor='var(--pink)';"
         onmouseout="this.style.background='var(--surface)';this.style.color='var(--text2)';this.style.borderColor='var(--border2)';">
        <?php echo $label; ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <span class="logo-text">LY Jewels</span>
      <p>Each piece in our collection is lovingly styled to celebrate your most precious moments. Wear it, cherish it. 🎀</p>
    </div>
    <div class="footer-col">
      <h4>Shop</h4>
      <a href="products.php?category=rings">Rings</a>
      <a href="products.php?category=earrings">Earrings</a>
      <a href="products.php?category=bracelets">Bracelets</a>
      <a href="products.php?category=pendants">Pendants</a>
      <a href="products.php?category=jhumkas">Jhumkas</a>
    </div>
    <div class="footer-col">
      <h4>Help</h4>
      <a href="faq.php">FAQ</a>
      <a href="contact.php">Contact Us</a>
      <a href="cart.php">My Cart</a>
      <a href="wishlist.php">Wishlist</a>
    </div>
    <div class="footer-col">
      <h4>Connect</h4>
      <a href="#">📸 Instagram</a>
      <a href="contact.php">✉️ Email Us</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© 2025 LY Jewels. Made with 🎀 for jewellery lovers.</span>
    <span style="color:var(--pink);">Choose with Love ♡</span>
  </div>
</footer>

<script>
// Slider
let cur = 0;
const slides = document.querySelectorAll('.hero-slide');
const dots = document.querySelectorAll('.hero-dot');
function goSlide(n) {
  slides[cur].classList.remove('active'); dots[cur].classList.remove('active');
  cur = n;
  slides[cur].classList.add('active'); dots[cur].classList.add('active');
}
setInterval(() => goSlide((cur + 1) % slides.length), 4500);

// Dark mode
function toggleTheme() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  document.documentElement.setAttribute('data-theme', isDark ? 'light' : 'dark');
  document.querySelector('.theme-toggle').textContent = isDark ? '🌙' : '☀️';
  localStorage.setItem('ly-theme', isDark ? 'light' : 'dark');
}
// Load saved theme
const saved = localStorage.getItem('ly-theme');
if(saved === 'dark') {
  document.documentElement.setAttribute('data-theme','dark');
  document.querySelector('.theme-toggle').textContent = '☀️';
}

// ── Scroll-triggered header shrink ──
document.addEventListener('DOMContentLoaded', function(){
  const header = document.getElementById('site-header');
  if(header){
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 40);
    window.addEventListener('scroll', onScroll, {passive:true});
    onScroll();
  }
  // Active link
  document.querySelectorAll('nav a:not(.nav-cart)').forEach(a => {
    if(a.getAttribute('href') === 'index.php') {
      a.classList.add('nav-active');
    }
  });
});
</script>
</body>
</html>