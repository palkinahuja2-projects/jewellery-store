<?php
// nav.php — include this at top of every page after session_start() / db
// Usage: include 'nav.php';
?>
<button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">🌙</button>
<header class="header">
  <div class="nav-container">
    <a href="index.php" class="logo-wrap">
      <img src="images/logo.jpeg" alt="LY Jewels" class="logo-img">
      <div>
        <span class="logo-text">LY Jewels</span>
        <span class="logo-sub">Styled with love</span>
      </div>
    </a>
    <nav>
      <a href="index.php" data-text="Home">Home</a>
      <a href="products.php" data-text="Shop">Shop</a>
      <a href="wishlist.php" data-text="✨ Wishlist"><span class="bow">🎀</span> Wishlist</a>
      <a href="contact.php" data-text="Contact">Contact</a>
      <a href="faq.php" data-text="FAQ">FAQ</a>
      <a href="login.php" data-text="Login">Login</a>
      <a href="reviews.php" data-text="Reviews">Reviews</a>
      <a href="shopping_policy.php" data-text="Policy">Policy</a>
      <a href="cart.php" class="nav-cart">🛒 Cart</a>
    </nav>
  </div>
</header>
<script>
function toggleTheme() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  document.documentElement.setAttribute('data-theme', isDark ? 'light' : 'dark');
  document.querySelector('.theme-toggle').textContent = isDark ? '🌙' : '☀️';
  localStorage.setItem('ly-theme', isDark ? 'light' : 'dark');
}
(function(){
  const saved = localStorage.getItem('ly-theme');
  if(saved === 'dark') {
    document.documentElement.setAttribute('data-theme','dark');
  }
})();
document.addEventListener('DOMContentLoaded', function(){
  const saved = localStorage.getItem('ly-theme');
  const btn = document.querySelector('.theme-toggle');
  if(btn) btn.textContent = saved === 'dark' ? '☀️' : '🌙';

  /* ── Scroll-triggered header shrink ── */
  const header = document.querySelector('.header');
  if(header){
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 40);
    };
    window.addEventListener('scroll', onScroll, {passive:true});
    onScroll(); // run on load in case page is already scrolled
  }

  /* ── Active page link highlight ── */
  const page = location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('nav a:not(.nav-cart)').forEach(a => {
    const href = a.getAttribute('href');
    if(href && href === page){
      a.style.color = 'var(--pink-deep)';
      a.style.setProperty('--link-active','1');
      a.classList.add('nav-active');
    }
  });
});
</script>