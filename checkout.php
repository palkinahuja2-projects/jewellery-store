<?php
session_start();
include 'include/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow">✦ Review Your Selection ✦</p>
  <h2>Shopping Cart 🛒</h2>
</div>

<div class="cart-wrapper">

<?php
$total = 0;
if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0):
?>
  <table>
    <thead>
      <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($_SESSION['cart'] as $id => $qty):
      $id = intval($id);
      $res = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
      $p = mysqli_fetch_assoc($res);
      if(!$p) continue;
      $sub = $p['price'] * $qty;
      $total += $sub;
    ?>
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:1rem;">
            <img src="<?php echo htmlspecialchars($p['image']); ?>" class="thumb" alt="<?php echo htmlspecialchars($p['name']); ?>">
            <div>
              <div style="font-family:'Playfair Display',serif;font-size:1rem;"><?php echo htmlspecialchars($p['name']); ?></div>
              <div style="font-size:0.75rem;color:var(--muted);">★★★★★</div>
            </div>
          </div>
        </td>
        <td class="price-tag">₹<?php echo $p['price']; ?></td>
        <td>
          <div class="cart-qty">
            <a href="update_cart.php?id=<?php echo $id; ?>&action=decrease">−</a>
            <span><?php echo $qty; ?></span>
            <a href="update_cart.php?id=<?php echo $id; ?>&action=increase">+</a>
          </div>
        </td>
        <td class="price-tag">₹<?php echo $sub; ?></td>
        <td><a href="remove_from_cart.php?id=<?php echo $id; ?>" class="remove-link">Remove</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="cart-summary">
    <div>
      <div class="cart-total-label">Order Total</div>
      <div class="cart-total-amount">₹<?php echo $total; ?></div>
      <?php if($total < 999): ?>
      <div class="cart-shipping">Add ₹<?php echo 999 - $total; ?> more for free shipping 🚚</div>
      <?php else: ?>
      <div class="cart-shipping" style="color:#4caf50;">✓ You qualify for free shipping! 🎉</div>
      <?php endif; ?>
    </div>
    <div class="cart-actions">
      <a href="products.php"><button class="btn-outline">← Continue Shopping</button></a>
      <a href="checkout.php"><button class="btn-primary">Checkout 💕</button></a>
    </div>
  </div>

<?php else: ?>
  <div style="text-align:center;padding:5rem 0;">
    <div style="font-size:4rem;margin-bottom:1.5rem;animation:float 3s ease-in-out infinite;display:block;">🛒</div>
    <p style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--muted);margin-bottom:1.5rem;">Your cart is empty</p>
    <a href="products.php"><button class="btn-primary">Browse Our Collection 🎀</button></a>
  </div>
<?php endif; ?>

</div>

</body>
</html>