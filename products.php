<?php
include 'include/db.php';

$search   = isset($_GET['search'])   ? clean($conn, $_GET['search'])   : '';
$category = isset($_GET['category']) ? $_GET['category']               : '';
$price    = isset($_GET['price'])    ? $_GET['price']                  : '';

if($search != '') {
    $query = "SELECT * FROM products WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
} elseif($category != '') {
    $query = "SELECT * FROM products WHERE category='$category'";
} elseif($price != '') {
    if($price == 'low')      $query = "SELECT * FROM products WHERE price < 500";
    elseif($price == 'mid')  $query = "SELECT * FROM products WHERE price BETWEEN 500 AND 2000";
    elseif($price == 'high') $query = "SELECT * FROM products WHERE price > 2000";
} else {
    $query = "SELECT * FROM products";
}

$result = mysqli_query($conn, $query);
if(!$result) die("Query failed: " . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow">✦ Our Collection ✦</p>
  <h2>Shop All Jewellery</h2>
</div>

<div class="products-layout">

  <!-- Sidebar -->
  <aside class="filter-sidebar">
    <div class="filter-title">🎀 Filters</div>

    <div class="filter-section">
      <h4>Categories</h4>
      <a href="products.php" class="<?php echo (!$category && !$price && !$search) ? 'active' : ''; ?>">All Products</a>
      <?php foreach(['rings'=>'💍 Rings','earrings'=>'💎 Earrings','bracelets'=>'✨ Bracelets','pendants'=>'🌸 Pendants','studs'=>'🔮 Studs','jhumkas'=>'🎀 Jhumkas','others'=>'💕 Others'] as $slug => $label): ?>
      <a href="products.php?category=<?php echo $slug; ?>" class="<?php echo $category === $slug ? 'active' : ''; ?>"><?php echo $label; ?></a>
      <?php endforeach; ?>
    </div>

    <div class="filter-section">
      <h4>Price Range</h4>
      <a href="products.php?price=low"  class="<?php echo $price==='low'  ? 'active' : ''; ?>">Under ₹500</a>
      <a href="products.php?price=mid"  class="<?php echo $price==='mid'  ? 'active' : ''; ?>">₹500 – ₹2000</a>
      <a href="products.php?price=high" class="<?php echo $price==='high' ? 'active' : ''; ?>">Above ₹2000</a>
    </div>
  </aside>

  <!-- Main -->
  <main>
    <form method="GET" action="products.php" style="display:contents;">
      <div class="search-row">
        <input type="text" name="search" placeholder="Search jewellery..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search 🔍</button>
      </div>
    </form>

    <div class="products">
    <?php
    $count = 0;
    while($row = mysqli_fetch_assoc($result)):
      $count++;
    ?>
      <div class="product" style="animation-delay:<?php echo $count * 0.06; ?>s">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        <div class="product-body">
          <div class="product-stars">★★★★★</div>
          <h3><?php echo htmlspecialchars($row['name']); ?></h3>
          <p>₹<?php echo htmlspecialchars($row['price']); ?></p>
          <div class="product-btns">
            <a href="product.php?id=<?php echo $row['id']; ?>">
              <button class="btn-outline">View</button>
            </a>
            <a href="add_to_cart.php?id=<?php echo $row['id']; ?>">
              <button class="btn-primary">Add 🛒</button>
            </a>
            <a href="add_to_wishlist.php?id=<?php echo $row['id']; ?>">
              <button class="btn-outline" style="flex:0;padding:9px 12px;">♡</button>
            </a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>

    <?php if($count === 0): ?>
      <div style="grid-column:1/-1;text-align:center;padding:4rem;color:var(--muted);">
        <p style="font-family:'Playfair Display',serif;font-size:1.6rem;margin-bottom:1rem;">No products found 🎀</p>
        <a href="products.php" style="color:var(--pink);">Clear filters →</a>
      </div>
    <?php endif; ?>
    </div>
  </main>

</div>

<footer>
  <div class="footer-bottom" style="max-width:100%;padding:1.5rem 3rem;border-top:1px solid var(--border);">
    <span>© 2025 LY Jewels</span>
    <a href="index.php" style="color:var(--pink);">← Back to Home</a>
  </div>
</footer>

</body>
</html>