<?php
session_start();
include '../include/db.php';
$page_title = 'Dashboard';
include 'admin_nav.php';

// Stats
$total_products = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM products"))[0];
$total_orders   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders"))[0];
$pending_orders = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM orders WHERE status='Pending'"))[0];
$total_messages = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM contact_messages"))[0];
$revenue_row    = mysqli_fetch_row(mysqli_query($conn, "SELECT SUM(price) FROM orders WHERE status='Delivered'"));
$total_revenue  = $revenue_row[0] ?? 0;

// Recent orders
$recent_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC LIMIT 5");

// Recent products
$recent_products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 5");
?>

<!-- Stats -->
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon pink">💍</div>
    <div>
      <div class="stat-num"><?php echo $total_products; ?></div>
      <div class="stat-label">Total Products</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon orange">📦</div>
    <div>
      <div class="stat-num"><?php echo $total_orders; ?></div>
      <div class="stat-label">Total Orders</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue">⏳</div>
    <div>
      <div class="stat-num"><?php echo $pending_orders; ?></div>
      <div class="stat-label">Pending Orders</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">💰</div>
    <div>
      <div class="stat-num">₹<?php echo number_format($total_revenue); ?></div>
      <div class="stat-label">Revenue (Delivered)</div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
  <a href="add_product.php" class="btn btn-pink">➕ Add Product</a>
  <a href="orders.php" class="btn btn-outline">📦 View Orders</a>
  <a href="messages.php" class="btn btn-outline">💌 Messages
    <?php if($total_messages > 0): ?>
      <span style="background:var(--pink);color:white;font-size:0.65rem;padding:1px 6px;border-radius:20px;"><?php echo $total_messages; ?></span>
    <?php endif; ?>
  </a>
</div>

<!-- Two-col layout -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">

  <!-- Recent Orders -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-title">Recent Orders 📦</div>
      <a href="orders.php" class="btn btn-outline btn-sm">View All</a>
    </div>
    <?php if(mysqli_num_rows($recent_orders) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Customer</th>
          <th>Amount</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      <?php while($o = mysqli_fetch_assoc($recent_orders)): ?>
        <tr>
          <td style="color:var(--muted);font-size:0.78rem;">#<?php echo $o['id']; ?></td>
          <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
          <td style="font-weight:600;color:var(--pink-dark);">₹<?php echo $o['price']; ?></td>
          <td>
            <span class="badge <?php echo $o['status']==='Delivered'?'badge-delivered':'badge-pending'; ?>">
              <?php echo $o['status']; ?>
            </span>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="empty-state" style="padding:2rem;">
        <span class="empty-icon">📦</span>
        <p>No orders yet</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Recent Products -->
  <div class="panel">
    <div class="panel-header">
      <div class="panel-title">Recent Products 💍</div>
      <a href="manage_products.php" class="btn btn-outline btn-sm">View All</a>
    </div>
    <?php if(mysqli_num_rows($recent_products) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Category</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
      <?php while($p = mysqli_fetch_assoc($recent_products)): ?>
        <tr>
          <td>
            <div class="product-name-cell">
              <img src="../<?php echo htmlspecialchars($p['image']); ?>" class="product-thumb" alt="">
              <span style="font-size:0.84rem;"><?php echo htmlspecialchars($p['name']); ?></span>
            </div>
          </td>
          <td><span class="badge badge-ring"><?php echo ucfirst($p['category']); ?></span></td>
          <td style="font-weight:600;color:var(--pink-dark);">₹<?php echo $p['price']; ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
      <div class="empty-state" style="padding:2rem;">
        <span class="empty-icon">💍</span>
        <p>No products yet</p>
        <a href="add_product.php" class="btn btn-pink btn-sm">Add First Product</a>
      </div>
    <?php endif; ?>
  </div>

</div>

<?php include 'admin_footer.php'; ?>