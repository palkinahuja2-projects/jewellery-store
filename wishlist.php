<?php
session_start();
include 'include/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Wishlist — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow" style="font-family:'Playfair Display',serif;font-size:clamp(1rem,2vw,1.4rem);font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:var(--pink-deep);">✦ Your Saved Favourites ✦</p>
  <h2 style="font-size:clamp(2.8rem,5vw,4.5rem);">My Wishlist 🎀</h2>
</div>

<div class="cart-wrapper">

<?php
if(isset($_SESSION['wishlist']) && count($_SESSION['wishlist']) > 0):
?>

  <div class="product-grid">
  <?php foreach($_SESSION['wishlist'] as $id):
    $id  = intval($id);
    $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
    $p   = mysqli_fetch_assoc($res);
    if(!$p) continue;
  ?>
    <div class="product-card">
      <div class="product-img">
        <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
        <a href="remove_from_wishlist.php?id=<?php echo $id; ?>" class="wishlist-quick" title="Remove from wishlist">♡</a>
      </div>
      <div class="product-info">
        <div class="product-name"><?php echo htmlspecialchars($p['name']); ?></div>
        <div class="product-stars">★★★★★</div>
        <div class="product-price">₹<?php echo htmlspecialchars($p['price']); ?></div>
        <div class="btn-group">
          <a href="product.php?id=<?php echo $id; ?>">
            <button class="btn-outline">View</button>
          </a>
          <a href="add_to_cart.php?id=<?php echo $id; ?>">
            <button class="btn-primary">Add to Cart 🛒</button>
          </a>
        </div>
        <div style="margin-top:0.6rem;text-align:center;">
          <a href="remove_from_wishlist.php?id=<?php echo $id; ?>"
             style="font-size:0.75rem;color:var(--muted);">Remove from Wishlist</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>

  <div class="cart-summary" style="margin-top:2rem;">
    <div>
      <div class="cart-total-label">
        <?php echo count($_SESSION['wishlist']); ?> item<?php echo count($_SESSION['wishlist'])!=1?'s':''; ?> saved
      </div>
      <div style="font-size:0.84rem;color:var(--muted);margin-top:0.3rem;">
        Move items to your cart to purchase them 💕
      </div>
    </div>
    <div class="cart-actions">
      <a href="products.php"><button class="btn-outline">← Continue Shopping</button></a>
      <a href="cart.php"><button class="btn-primary">Go to Cart 🛒</button></a>
    </div>
  </div>

<?php else: ?>

  <div style="text-align:center;padding:5rem 0;">
    <div style="font-size:4rem;margin-bottom:1.5rem;animation:float 3s ease-in-out infinite;display:block;">🎀</div>
    <p style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--muted);margin-bottom:0.8rem;">Your wishlist is empty</p>
    <p style="color:var(--muted);font-size:0.88rem;margin-bottom:1.8rem;">
      Save your favourite pieces and come back to them anytime ♡
    </p>
    <a href="products.php"><button class="btn-primary">Browse Our Collection 🎀</button></a>
  </div>

<?php endif; ?>

</div>

</body>
</html>