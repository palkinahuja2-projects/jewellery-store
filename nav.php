<?php
// _nav.php — include this at top of every page after session_start() / db
// Usage: include '_nav.php';
?>
<button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode">🌙</button>
<div class="top-bar">🎀 Free Shipping Above ₹999 &nbsp;|&nbsp; New Arrivals Every Week &nbsp;|&nbsp; 50% OFF on Rings 🎀</div>
<header class="header">
  <div class="nav-container">
    <a href="index.php" class="logo-wrap">
      <img src="images/logo.jpeg" alt="LY Jewels" class="logo-img">
      <div>
        <span class="logo-text">LY Jewels</span>
        <span class="logo-sub">Handcrafted with love</span>
      </div>
    </a>
    <nav>
      <a href="index.php">Home</a>
      <a href="products.php">Shop</a>
      <a href="wishlist.php"><span class="bow">🎀</span> Wishlist</a>
      <a href="contact.php">Contact</a>
      <a href="faq.php">FAQ</a>
      <a href="cart.php" class="nav-cart">🛒 Cart</a>
	  <a href="login.php">Login</a>
	  <a href="reviews.php">Reviews</a>
	  <a href="shopping_policy.php">Policy</a>
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
});
</script>