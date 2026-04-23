<?php
session_start();
include '../include/db.php';
$page_title = 'Add Product';

$success = '';
$error   = '';

if(isset($_POST['submit'])) {
    $name        = clean($conn, $_POST['name']);
    $price       = intval($_POST['price']);
    $description = clean($conn, $_POST['description']);
    $category    = clean($conn, $_POST['category']);

    if(empty($name) || $price <= 0 || empty($description) || empty($category)) {
        $error = 'Please fill in all required fields.';
    } elseif(empty($_FILES['image']['name'])) {
        $error = 'Please upload a product image.';
    } else {
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)) {
            $error = 'Only JPG, PNG, and WEBP images are allowed.';
        } else {
            $image_name = time() . '_' . basename($_FILES['image']['name']);
            $dest = "../images/" . $image_name;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = "images/" . $image_name;
                $q = "INSERT INTO products (name, price, image, description, category)
                      VALUES ('$name', '$price', '$image_path', '$description', '$category')";
                if(mysqli_query($conn, $q)) {
                    $success = 'Product "' . htmlspecialchars($name) . '" added successfully! 🎉';
                } else {
                    $error = 'Database error: ' . mysqli_error($conn);
                }
            } else {
                $error = 'Failed to upload image. Check folder permissions.';
            }
        }
    }
}

include 'admin_nav.php';
?>

<div style="max-width:720px;">

<?php if($success): ?>
  <div class="alert alert-success">✅ <?php echo $success; ?>
    <a href="manage_products.php" style="margin-left:1rem;font-weight:600;color:var(--success);">View All Products →</a>
  </div>
<?php endif; ?>
<?php if($error): ?>
  <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
<?php endif; ?>

<div class="panel">
  <div class="panel-header">
    <div class="panel-title">Add New Product ✨</div>
    <a href="manage_products.php" class="btn btn-outline btn-sm">← Back to Products</a>
  </div>
  <div class="panel-body">
    <form method="POST" enctype="multipart/form-data">
      <div class="form-grid">

        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Rose Gold Ring" required
                 value="<?php echo isset($_POST['name'])?htmlspecialchars($_POST['name']):''; ?>">
        </div>

        <div class="form-group">
          <label>Price (₹) *</label>
          <input type="number" name="price" class="form-control" placeholder="e.g. 1299" min="1" required
                 value="<?php echo isset($_POST['price'])?htmlspecialchars($_POST['price']):''; ?>">
        </div>

        <div class="form-group">
          <label>Category *</label>
          <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <?php foreach(['rings'=>'💍 Rings','earrings'=>'💎 Earrings','bracelets'=>'✨ Bracelets','pendants'=>'🌸 Pendants','studs'=>'🔮 Studs','jhumkas'=>'🎀 Jhumkas','others'=>'💕 Others'] as $val=>$label): ?>
              <option value="<?php echo $val; ?>" <?php echo (isset($_POST['category']) && $_POST['category']===$val)?'selected':''; ?>>
                <?php echo $label; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group full">
          <label>Description *</label>
          <textarea name="description" class="form-control" placeholder="Describe the product — materials, style, occasion..." required><?php echo isset($_POST['description'])?htmlspecialchars($_POST['description']):''; ?></textarea>
        </div>

        <div class="form-group full">
          <label>Product Image *</label>
          <div class="file-drop" onclick="document.getElementById('imgInput').click()">
            <input type="file" id="imgInput" name="image" accept="image/*" onchange="previewImg(this)">
            <span class="file-icon" id="fileIcon">📷</span>
            <div id="fileLabel">Click to upload image</div>
            <div style="font-size:0.72rem;color:var(--muted);margin-top:4px;">JPG, PNG or WEBP • Max 5MB recommended</div>
            <img id="imgPreview" src="" alt="" style="display:none;max-width:180px;max-height:180px;object-fit:cover;border-radius:10px;margin-top:12px;border:2px solid var(--pink-soft);">
          </div>
        </div>

      </div><!-- /form-grid -->

      <div style="display:flex;gap:1rem;margin-top:1.5rem;">
        <button type="submit" name="submit" class="btn btn-pink" style="padding:11px 32px;">✨ Add Product</button>
        <a href="manage_products.php" class="btn btn-outline" style="padding:11px 24px;">Cancel</a>
      </div>
    </form>
  </div>
</div>

</div><!-- /max-width -->

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