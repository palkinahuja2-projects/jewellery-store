<?php
include 'include/db.php';
$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);
if(!$product){ echo "Product not found"; exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name']); ?> — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<!-- Breadcrumb -->
<div style="padding:1.2rem 3rem;border-bottom:1px solid var(--border);font-size:0.75rem;color:var(--muted);">
  <a href="index.php" style="color:var(--muted);">Home</a>
  <span style="margin:0 8px;color:var(--pink);">›</span>
  <a href="products.php" style="color:var(--muted);">Shop</a>
  <span style="margin:0 8px;color:var(--pink);">›</span>
  <span style="color:var(--text);"><?php echo htmlspecialchars($product['name']); ?></span>
</div>

<div class="detail-section">

  <!-- Image -->
  <div class="detail-imgs">
    <div class="main-img-box">
      <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="mainImg">
    </div>
    <div class="thumb-row">
      <div class="thumb-box">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" onclick="document.getElementById('mainImg').src=this.src">
      </div>
    </div>
  </div>

  <!-- Info -->
  <div class="detail-info fade-up">
    <div class="detail-eyebrow">✦ <?php echo htmlspecialchars(ucfirst($product['category'] ?? 'Jewellery')); ?></div>
    <h1 class="detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
    <div class="detail-stars">★★★★★ <span style="color:var(--muted);font-size:0.8rem;margin-left:6px;">Premium Quality</span></div>
    <div class="detail-price">₹<?php echo htmlspecialchars($product['price']); ?></div>
    <p class="detail-desc"><?php echo htmlspecialchars($product['description']); ?></p>

    <div class="detail-info-box">
      🚚 Free shipping on orders above ₹999<br>
      🔄 Easy returns within 7 days<br>
      🎀 Gift wrapping available on request<br>
      💎 Premium quality, handcrafted with care
    </div>

    <div class="detail-actions">
      <a href="add_to_cart.php?id=<?php echo $product['id']; ?>">
        <button class="btn-primary" style="padding:14px 36px;">Add to Cart 🛒</button>
      </a>
      <a href="add_to_wishlist.php?id=<?php echo $product['id']; ?>">
        <button class="btn-outline" style="padding:14px 20px;font-size:1rem;">♡</button>
      </a>
    </div>

    <div style="margin-top:1.5rem;">
      <a href="products.php" style="font-size:0.8rem;color:var(--muted);">← Continue Shopping</a>
    </div>
  </div>

</div>

</body>
</html>