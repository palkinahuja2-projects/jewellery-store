<?php
session_start();
include '../include/db.php';
$page_title = 'Manage Products';
include 'admin_nav.php';

// Search/filter
$search = isset($_GET['search']) ? clean($conn, $_GET['search']) : '';
$cat    = isset($_GET['category']) ? clean($conn, $_GET['category']) : '';

if($search)    $q = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id DESC";
elseif($cat)   $q = "SELECT * FROM products WHERE category='$cat' ORDER BY id DESC";
else           $q = "SELECT * FROM products ORDER BY id DESC";

$result = mysqli_query($conn, $q);
$count  = mysqli_num_rows($result);
?>

<!-- Toolbar -->
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
  <div>
    <div style="font-size:0.82rem;color:var(--muted);"><?php echo $count; ?> product<?php echo $count!=1?'s':''; ?> found</div>
  </div>
  <div style="display:flex;gap:0.8rem;flex-wrap:wrap;align-items:center;">
    <form method="GET" style="display:flex;gap:0.6rem;align-items:center;">
      <input type="text" name="search" class="form-control" placeholder="🔍 Search products..." value="<?php echo htmlspecialchars($search); ?>" style="width:200px;padding:8px 12px;">
      <select name="category" class="form-control" style="padding:8px 12px;width:140px;" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach(['rings','earrings','bracelets','pendants','studs','jhumkas','others'] as $c): ?>
          <option value="<?php echo $c; ?>" <?php echo $cat===$c?'selected':''; ?>><?php echo ucfirst($c); ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-outline btn-sm">Filter</button>
      <?php if($search || $cat): ?><a href="manage_products.php" class="btn btn-sm" style="color:var(--muted);">✕ Clear</a><?php endif; ?>
    </form>
    <a href="add_product.php" class="btn btn-pink">➕ Add Product</a>
  </div>
</div>

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">All Products 💍</div>
  </div>

  <?php if($count > 0): ?>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Product</th>
        <th>Category</th>
        <th>Price</th>
        <th>Description</th>
        <th style="text-align:center;">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td style="color:var(--muted);font-size:0.78rem;">#<?php echo $row['id']; ?></td>
        <td>
          <div class="product-name-cell">
            <img src="../<?php echo htmlspecialchars($row['image']); ?>" class="product-thumb" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <div>
              <div style="font-weight:500;"><?php echo htmlspecialchars($row['name']); ?></div>
            </div>
          </div>
        </td>
        <td><span class="badge badge-ring"><?php echo ucfirst($row['category']); ?></span></td>
        <td style="font-weight:600;color:var(--pink-dark);">₹<?php echo number_format($row['price']); ?></td>
        <td style="color:var(--muted);font-size:0.8rem;max-width:200px;">
          <?php echo htmlspecialchars(substr($row['description'] ?? '', 0, 60)); ?>…
        </td>
        <td style="text-align:center;">
          <div style="display:flex;gap:6px;justify-content:center;">
            <a href="../product.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn btn-outline btn-sm">👁️</a>
            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-outline btn-sm">✏️ Edit</a>
            <a href="delete_product.php?id=<?php echo $row['id']; ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Delete \'<?php echo addslashes($row['name']); ?>\'? This cannot be undone.')">🗑️</a>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <div class="empty-state">
      <span class="empty-icon">💍</span>
      <p>No products found</p>
      <a href="add_product.php" class="btn btn-pink">Add Your First Product</a>
    </div>
  <?php endif; ?>
</div>

<?php include 'admin_footer.php'; ?>