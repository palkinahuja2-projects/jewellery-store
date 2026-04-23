<?php
session_start();
include '../include/db.php';
$page_title = 'Edit Product';

$id = intval($_GET['id'] ?? 0);
if(!$id) { header("Location: manage_products.php"); exit; }

$result  = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);
if(!$product) { header("Location: manage_products.php"); exit; }

$success = '';
$error   = '';

if(isset($_POST['update'])) {
    $name        = clean($conn, $_POST['name']);
    $price       = intval($_POST['price']);
    $description = clean($conn, $_POST['description']);
    $category    = clean($conn, $_POST['category']);

    if(empty($name) || $price <= 0 || empty($category)) {
        $error = 'Please fill in all required fields.';
    } else {
        mysqli_query($conn,
            "UPDATE products SET name='$name', price='$price', description='$description', category='$category' WHERE id=$id");

        // Optional new image
        if(!empty($_FILES['image']['name'])) {
            $allowed = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if(!in_array($ext, $allowed)) {
                $error = 'Only JPG, PNG, and WEBP images are allowed.';
            } else {
                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $dest = "../images/" . $image_name;
                if(move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image_path = "images/" . $image_name;
                    mysqli_query($conn, "UPDATE products SET image='$image_path' WHERE id=$id");
                    $product['image'] = $image_path;
                }
            }
        }

        if(!$error) {
            $success = 'Product updated successfully! 🎉';
            // Refresh product data
            $result  = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
            $product = mysqli_fetch_assoc($result);
        }
    }
}

include 'admin_nav.php';
?>

<div style="max-width:720px;">

<?php if($success): ?>
  <div class="alert alert-success">✅ <?php echo $success; ?></div>
<?php endif; ?>
<?php if($error): ?>
  <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
<?php endif; ?>

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">Edit Product ✏️</div>
    <a href="manage_products.php" class="btn btn-outline btn-sm">← Back to Products</a>
  </div>
  <div class="panel-body">

    <!-- Current image preview -->
    <div style="margin-bottom:1.5rem;display:flex;align-items:center;gap:1.2rem;padding:1rem;background:var(--surface2);border-radius:10px;border:1px solid var(--border);">
      <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="Current"
           style="width:72px;height:72px;object-fit:cover;border-radius:10px;border:1px solid var(--border);">
      <div>
        <div style="font-family:'Playfair Display',serif;font-size:1rem;"><?php echo htmlspecialchars($product['name']); ?></div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:2px;">ID #<?php echo $product['id']; ?> · <?php echo ucfirst($product['category']); ?></div>
      </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-grid">

        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" class="form-control" required
                 value="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <div class="form-group">
          <label>Price (₹) *</label>
          <input type="number" name="price" class="form-control" min="1" required
                 value="<?php echo htmlspecialchars($product['price']); ?>">
        </div>

        <div class="form-group">
          <label>Category *</label>
          <select name="category" class="form-control" required>
            <?php foreach(['rings'=>'💍 Rings','earrings'=>'💎 Earrings','bracelets'=>'✨ Bracelets','pendants'=>'🌸 Pendants','studs'=>'🔮 Studs','jhumkas'=>'🎀 Jhumkas','others'=>'💕 Others'] as $val=>$label): ?>
              <option value="<?php echo $val; ?>" <?php echo $product['category']===$val?'selected':''; ?>>
                <?php echo $label; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group full">
          <label>Description</label>
          <textarea name="description" class="form-control"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group full">
          <label>Replace Image <span style="color:var(--muted);font-weight:400;">(optional — leave blank to keep current)</span></label>
          <div class="file-drop" onclick="document.getElementById('imgInput').click()">
            <input type="file" id="imgInput" name="image" accept="image/*" onchange="previewImg(this)">
            <span class="file-icon" id="fileIcon">📷</span>
            <div id="fileLabel">Click to upload a new image</div>
            <img id="imgPreview" src="" alt="" style="display:none;max-width:160px;max-height:160px;object-fit:cover;border-radius:10px;margin-top:12px;">
          </div>
        </div>

      </div>

      <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap;">
        <button type="submit" name="update" class="btn btn-pink" style="padding:11px 32px;">✅ Save Changes</button>
        <a href="manage_products.php" class="btn btn-outline" style="padding:11px 24px;">Cancel</a>
        <a href="delete_product.php?id=<?php echo $id; ?>"
           class="btn btn-danger" style="padding:11px 24px;margin-left:auto;"
           onclick="return confirm('Delete this product? This cannot be undone.')">🗑️ Delete Product</a>
      </div>
    </form>
  </div>
</div>

</div>

<script>
function previewImg(input) {
  const file = input.files[0];
  if(file) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('imgPreview').src = e.target.result;
      document.getElementById('imgPreview').style.display = 'block';
      document.getElementById('fileIcon').textContent = '✅';
      document.getElementById('fileLabel').textContent = file.name;
    };
    reader.readAsDataURL(file);
  }
}
</script>

<?php include 'admin_footer.php'; ?>