<?php
session_start();
include 'include/db.php';

$success = '';
$error   = '';

// ── CSRF Token generation ──
if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle review submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check
    if(!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Security check failed. Please try again.';
    } else {
        $reviewer_name = htmlspecialchars(trim($_POST['reviewer_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $rating        = intval($_POST['rating'] ?? 0);
        $review_text   = htmlspecialchars(trim($_POST['review_text'] ?? ''), ENT_QUOTES, 'UTF-8');
        $product_name  = htmlspecialchars(trim($_POST['product_name'] ?? ''), ENT_QUOTES, 'UTF-8');

        // Rate limiting: max 3 reviews per session
        if(!isset($_SESSION['review_count'])) $_SESSION['review_count'] = 0;
        if($_SESSION['review_count'] >= 3) {
            $error = 'You have reached the review limit for this session.';
        } elseif(empty($reviewer_name) || empty($review_text) || $rating < 1 || $rating > 5) {
            $error = 'Please fill in all fields and select a rating.';
        } elseif(strlen($reviewer_name) > 100 || strlen($review_text) > 2000) {
            $error = 'Input exceeds allowed length.';
        } else {

            // Handle image upload (optional)
            $image_path = '';
            if(!empty($_FILES['review_image']['name'])) {
                $allowed_types = ['image/jpeg','image/png','image/webp','image/gif'];
                $max_size      = 3 * 1024 * 1024; // 3 MB

                if(!in_array($_FILES['review_image']['type'], $allowed_types)) {
                    $error = 'Only JPG, PNG, WEBP, or GIF images are allowed.';
                } elseif($_FILES['review_image']['size'] > $max_size) {
                    $error = 'Image must be under 3 MB.';
                } else {
                    $ext       = pathinfo($_FILES['review_image']['name'], PATHINFO_EXTENSION);
                    $safe_name = 'review_' . bin2hex(random_bytes(8)) . '.' . $ext;
                    $upload_dir = 'images/reviews/';
                    if(!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                    if(move_uploaded_file($_FILES['review_image']['tmp_name'], $upload_dir . $safe_name)) {
                        $image_path = $upload_dir . $safe_name;
                    } else {
                        $error = 'Image upload failed. Please try again.';
                    }
                }
            }

            if(empty($error)) {
                $rv = mysqli_real_escape_string($conn, $reviewer_name);
                $rt = mysqli_real_escape_string($conn, $review_text);
                $pn = mysqli_real_escape_string($conn, $product_name);
                $ip = mysqli_real_escape_string($conn, $image_path);

                $q = "INSERT INTO reviews (reviewer_name, rating, review_text, product_name, image_path, created_at)
                      VALUES ('$rv', $rating, '$rt', '$pn', '$ip', NOW())";
                if(mysqli_query($conn, $q)) {
                    $_SESSION['review_count']++;
                    // Regenerate CSRF token after successful submission
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    $success = 'Thank you for your review! 🎀';
                } else {
                    $error = 'Could not save review. Please ensure the reviews table is set up.';
                }
            }
        }
    }
}

// Fetch reviews
$reviews     = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id DESC");
$total_rev   = $reviews ? mysqli_num_rows($reviews) : 0;
$avg_row     = mysqli_fetch_row(mysqli_query($conn, "SELECT AVG(rating) FROM reviews"));
$avg_rating  = $avg_row ? round($avg_row[0], 1) : 0;
$filter_star = isset($_GET['star']) ? intval($_GET['star']) : 0;
if($filter_star >= 1 && $filter_star <= 5) {
    $reviews   = mysqli_query($conn, "SELECT * FROM reviews WHERE rating=$filter_star ORDER BY id DESC");
    $total_rev = mysqli_num_rows($reviews);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reviews — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .reviews-layout {
      max-width: 1100px;
      margin: 0 auto;
      padding: 2rem 2rem 5rem;
      display: grid;
      grid-template-columns: 340px 1fr;
      gap: 2rem;
      align-items: start;
    }

    /* ── LEFT SIDEBAR ── */
    .reviews-sidebar { position: sticky; top: 100px; }

    .rating-summary {
      background: var(--surface);
      border: 1px solid var(--border2);
      border-radius: var(--radius);
      padding: 2rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(214,140,160,0.1);
      margin-bottom: 1.4rem;
    }
    .big-rating {
      font-family: 'Playfair Display', serif;
      font-size: 4rem;
      color: var(--text);
      line-height: 1;
    }
    .big-stars { font-size: 1.6rem; margin: 0.4rem 0; }
    .rating-count { font-size: 0.8rem; color: var(--muted); }

    .rating-bars { margin-top: 1.4rem; }
    .rating-bar-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 7px;
      font-size: 0.78rem;
      color: var(--muted);
    }
    .rating-bar-row a { color: inherit; text-decoration: none; display:contents; }
    .rating-bar-track {
      flex: 1; height: 7px;
      background: var(--border);
      border-radius: 4px;
      overflow: hidden;
    }
    .rating-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--pink), #e8b4c0);
      border-radius: 4px;
      transition: width 0.6s ease;
    }
    .rating-bar-num { width: 24px; text-align: right; color: var(--text2); font-weight: 500; }

    /* Write Review Box */
    .write-review-box {
      background: var(--surface);
      border: 1px solid var(--border2);
      border-radius: var(--radius);
      padding: 1.8rem;
      box-shadow: 0 4px 20px rgba(214,140,160,0.08);
    }
    .write-review-box h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--text);
      margin-bottom: 1.2rem;
    }
    .form-group { margin-bottom: 1rem; }
    .form-group label {
      display: block;
      font-size: 0.73rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text2);
      margin-bottom: 5px;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px 13px;
      border: 1.5px solid var(--border2);
      border-radius: 9px;
      background: var(--bg2, #fdf8f9);
      color: var(--text);
      font-size: 0.86rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      box-sizing: border-box;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: var(--pink);
      box-shadow: 0 0 0 3px rgba(214,140,160,0.15);
      background: white;
    }
    textarea { resize: vertical; min-height: 90px; }

    /* Image upload zone */
    .upload-zone {
      border: 2px dashed var(--border2);
      border-radius: 12px;
      padding: 1.2rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      background: var(--bg2);
      position: relative;
    }
    .upload-zone:hover { border-color: var(--pink); background: var(--surface2); }
    .upload-zone input[type="file"] {
      position: absolute; inset: 0; opacity: 0; cursor: pointer;
      width: 100%; height: 100%; border: none; padding: 0;
    }
    .upload-zone-text { font-size: 0.8rem; color: var(--muted); pointer-events: none; }
    .upload-zone-icon { font-size: 1.8rem; display: block; margin-bottom: 0.3rem; }
    #img-preview { max-width: 100%; border-radius: 10px; margin-top: 0.8rem; display: none; border: 1px solid var(--border); }

    /* Star picker */
    .star-picker { display: flex; gap: 6px; flex-direction: row-reverse; justify-content: flex-end; }
    .star-picker input { display: none; }
    .star-picker label {
      font-size: 1.6rem;
      cursor: pointer;
      color: #ddd;
      transition: color 0.15s, transform 0.15s;
      text-transform: none;
      letter-spacing: 0;
      font-weight: 400;
      padding: 0;
    }
    .star-picker label:hover,
    .star-picker label:hover ~ label,
    .star-picker input:checked ~ label { color: #f4c430; }
    .star-picker label:hover { transform: scale(1.2); }

    .submit-btn {
      width: 100%;
      padding: 12px;
      background: var(--pink);
      color: white;
      border: none;
      border-radius: 50px;
      font-size: 0.84rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 0.5rem;
    }
    .submit-btn:hover { opacity: 0.88; transform: translateY(-1px); }

    .alert { padding: 11px 14px; border-radius: 9px; font-size: 0.82rem; margin-bottom: 1rem; }
    .alert-success { background: #eafaf1; color: #1e8449; border: 1px solid #a9dfbf; }
    .alert-error   { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; }

    /* ── RIGHT: REVIEWS LIST ── */
    .reviews-main {}

    .reviews-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.8rem;
      margin-bottom: 1.4rem;
    }
    .reviews-count {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--text);
    }
    .filter-stars { display: flex; gap: 6px; flex-wrap: wrap; }
    .filter-star-btn {
      padding: 5px 14px;
      border-radius: 20px;
      border: 1.5px solid var(--border2);
      background: var(--surface);
      color: var(--text2);
      font-size: 0.78rem;
      cursor: pointer;
      text-decoration: none;
      font-family: inherit;
      transition: all 0.2s;
    }
    .filter-star-btn:hover, .filter-star-btn.active {
      background: var(--pink);
      border-color: var(--pink);
      color: white;
    }

    /* Review Card */
    .review-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.6rem;
      margin-bottom: 1.1rem;
      box-shadow: 0 2px 12px rgba(214,140,160,0.05);
      animation: fadeUp 0.4s ease both;
      transition: box-shadow 0.2s;
    }
    .review-card:hover { box-shadow: 0 6px 24px rgba(214,140,160,0.12); }
    @keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}

    .review-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 0.8rem;
    }
    .reviewer-info { display: flex; align-items: center; gap: 12px; }
    .reviewer-avatar {
      width: 42px; height: 42px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--pink), #e8b4c0);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      color: white;
      font-weight: 600;
      flex-shrink: 0;
    }
    .reviewer-name { font-weight: 600; font-size: 0.92rem; color: var(--text); }
    .review-product { font-size: 0.74rem; color: var(--muted); margin-top: 2px; }
    .review-meta { text-align: right; }
    .review-stars { font-size: 1rem; color: #f4c430; }
    .review-date  { font-size: 0.72rem; color: var(--muted); margin-top: 3px; }
    .review-body  { font-size: 0.88rem; color: var(--text2); line-height: 1.75; }
    .review-img   { max-width: 100%; border-radius: 10px; margin-top: 0.9rem; border: 1px solid var(--border); max-height: 260px; object-fit: cover; }
    .verified-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      font-size: 0.7rem;
      color: var(--success, #27ae60);
      background: #eafaf1;
      border: 1px solid #a9dfbf;
      padding: 2px 8px;
      border-radius: 20px;
      margin-top: 0.8rem;
    }

    .empty-reviews {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--muted);
    }
    .empty-reviews .empty-icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
    .empty-reviews p { font-family:'Playfair Display',serif; font-size:1.2rem; }

    @media(max-width:768px) {
      .reviews-layout { grid-template-columns: 1fr; }
      .reviews-sidebar { position: static; }
    }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-hero">
  <p class="section-eyebrow" style="font-family:'Playfair Display',serif;font-size:clamp(1rem,2vw,1.4rem);font-weight:600;letter-spacing:0.14em;text-transform:uppercase;color:var(--pink-deep);">✦ Real Words, Real Love ✦</p>
  <h2 style="font-size:clamp(2.8rem,5vw,4.5rem);">Customer Reviews 💌</h2>
</div>

<div class="reviews-layout">

  <!-- ── LEFT SIDEBAR ── -->
  <div class="reviews-sidebar">

    <!-- Rating Summary -->
    <div class="rating-summary">
      <div class="big-rating"><?php echo $avg_rating ?: '—'; ?></div>
      <div class="big-stars">
        <?php
        $full = floor($avg_rating);
        for($i=1;$i<=5;$i++) echo $i<=$full ? '★' : '☆';
        ?>
      </div>
      <div class="rating-count">Based on <?php echo $total_rev; ?> review<?php echo $total_rev!=1?'s':''; ?></div>

      <div class="rating-bars">
        <?php
        for($s=5;$s>=1;$s--) {
            $cnt = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM reviews WHERE rating=$s"))[0] ?? 0;
            $pct = $total_rev > 0 ? round($cnt/$total_rev*100) : 0;
        ?>
        <div class="rating-bar-row">
          <a href="reviews.php?star=<?php echo $s; ?>">
            <span><?php echo $s; ?>★</span>
            <div class="rating-bar-track">
              <div class="rating-bar-fill" style="width:<?php echo $pct; ?>%"></div>
            </div>
            <span class="rating-bar-num"><?php echo $cnt; ?></span>
          </a>
        </div>
        <?php } ?>
      </div>
    </div>

    <!-- Write a Review Form -->
    <div class="write-review-box">
      <h3>✍️ Write a Review</h3>

      <?php if($success): ?>
        <div class="alert alert-success">🎉 <?php echo $success; ?></div>
      <?php endif; ?>
      <?php if($error): ?>
        <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="POST" action="reviews.php" enctype="multipart/form-data">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <div class="form-group">
          <label>Your Name *</label>
          <input type="text" name="reviewer_name" placeholder="e.g. Priya S." required maxlength="100"
                 value="<?php echo isset($_POST['reviewer_name'])?htmlspecialchars($_POST['reviewer_name']):''; ?>">
        </div>

        <div class="form-group">
          <label>Product Purchased</label>
          <input type="text" name="product_name" placeholder="e.g. Rose Gold Ring" maxlength="150"
                 value="<?php echo isset($_POST['product_name'])?htmlspecialchars($_POST['product_name']):''; ?>">
        </div>

        <div class="form-group">
          <label>Your Rating *</label>
          <div class="star-picker">
            <?php for($i=5;$i>=1;$i--): ?>
              <input type="radio" name="rating" id="star<?php echo $i; ?>" value="<?php echo $i; ?>"
                     <?php echo (isset($_POST['rating'])&&$_POST['rating']==$i)?'checked':''; ?>>
              <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> star">★</label>
            <?php endfor; ?>
          </div>
        </div>

        <div class="form-group">
          <label>Your Review *</label>
          <textarea name="review_text" placeholder="Share your experience with us... 🎀" required maxlength="2000"><?php echo isset($_POST['review_text'])?htmlspecialchars($_POST['review_text']):''; ?></textarea>
        </div>

        <!-- Upload Image Section -->
        <div class="form-group">
          <label>Upload a Photo (optional)</label>
          <div class="upload-zone" onclick="document.getElementById('review_image').click()">
            <input type="file" name="review_image" id="review_image" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImg(event)" style="display:none;">
            <span class="upload-zone-icon">📷</span>
            <div class="upload-zone-text">Click to upload image (JPG, PNG, WEBP · max 3 MB)</div>
          </div>
          <img id="img-preview" src="" alt="Preview">
        </div>

        <button type="submit" class="submit-btn">Submit Review 💕</button>
      </form>
    </div>

  </div><!-- /sidebar -->

  <!-- ── RIGHT: REVIEWS LIST ── -->
  <div class="reviews-main">

    <div class="reviews-toolbar">
      <div class="reviews-count">
        <?php if($filter_star): ?>
          <?php echo $filter_star; ?>★ Reviews (<?php echo $total_rev; ?>)
        <?php else: ?>
          All Reviews (<?php echo $total_rev; ?>)
        <?php endif; ?>
      </div>
      <div class="filter-stars">
        <a href="reviews.php" class="filter-star-btn <?php echo !$filter_star?'active':''; ?>">All</a>
        <?php for($s=5;$s>=1;$s--): ?>
          <a href="reviews.php?star=<?php echo $s; ?>" class="filter-star-btn <?php echo $filter_star===$s?'active':''; ?>">
            <?php echo $s; ?>★
          </a>
        <?php endfor; ?>
      </div>
    </div>

    <?php if($total_rev > 0): ?>
      <?php
      $delay = 0;
      while($r = mysqli_fetch_assoc($reviews)):
        $stars = '';
        for($i=1;$i<=5;$i++) $stars .= $i<=$r['rating']?'★':'☆';
        $initial = strtoupper(substr($r['reviewer_name'],0,1));
        $delay += 0.05;
      ?>
      <div class="review-card" style="animation-delay:<?php echo $delay; ?>s">
        <div class="review-header">
          <div class="reviewer-info">
            <div class="reviewer-avatar"><?php echo $initial; ?></div>
            <div>
              <div class="reviewer-name"><?php echo htmlspecialchars($r['reviewer_name']); ?></div>
              <?php if(!empty($r['product_name'])): ?>
                <div class="review-product">Purchased: <?php echo htmlspecialchars($r['product_name']); ?></div>
              <?php endif; ?>
            </div>
          </div>
          <div class="review-meta">
            <div class="review-stars"><?php echo $stars; ?></div>
            <div class="review-date"><?php echo date('d M Y', strtotime($r['created_at'] ?? 'now')); ?></div>
          </div>
        </div>
        <div class="review-body"><?php echo nl2br(htmlspecialchars($r['review_text'])); ?></div>
        <?php if(!empty($r['image_path']) && file_exists($r['image_path'])): ?>
          <img src="<?php echo htmlspecialchars($r['image_path']); ?>" alt="Review photo" class="review-img">
        <?php endif; ?>
        <div class="verified-badge">✅ Verified Purchase</div>
      </div>
      <?php endwhile; ?>

    <?php else: ?>
      <div class="empty-reviews">
        <span class="empty-icon">💌</span>
        <p>
          <?php if($filter_star): ?>
            No <?php echo $filter_star; ?>★ reviews yet.
            <a href="reviews.php" style="color:var(--pink);">View all reviews</a>
          <?php else: ?>
            Be the first to leave a review! 🎀
          <?php endif; ?>
        </p>
      </div>
    <?php endif; ?>

  </div><!-- /reviews-main -->
</div><!-- /reviews-layout -->

<script>
function previewImg(event) {
  const file = event.target.files[0];
  const preview = document.getElementById('img-preview');
  if(file) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(file);
  } else {
    preview.style.display = 'none';
  }
}
</script>

</body>
</html>