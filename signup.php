<?php
session_start();
include 'include/db.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $phone    = trim(mysqli_real_escape_string($conn, $_POST['phone'] ?? ''));

    if(empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = 'Please fill in all required fields.';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif(strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif($password !== $confirm) {
        $error = 'Passwords do not match. Please try again.';
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0) {
            $error = 'An account with this email already exists. <a href="login.php">Sign in instead →</a>';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $ins = mysqli_query($conn,
                "INSERT INTO users (name, email, phone, password) VALUES ('$name','$email','$phone','$hashed')");
            if($ins) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .auth-page {
      min-height:100vh; display:flex; align-items:center; justify-content:center;
      background:var(--bg); padding:7rem 1rem 3rem;
    }
    .auth-card {
      background:var(--surface); border:1px solid var(--border2); border-radius:24px;
      padding:3rem 2.8rem; width:100%; max-width:500px;
      box-shadow:0 16px 60px rgba(214,140,160,0.15);
      animation:fadeUp 0.5s ease both;
    }
    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}

    .auth-top { text-align:center; margin-bottom:2rem; }
    .auth-top .logo-text { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--pink); display:block; }
    .auth-top .logo-sub  { font-size:0.72rem; color:var(--muted); letter-spacing:0.1em; text-transform:uppercase; }
    .auth-eyebrow { font-size:0.72rem; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; color:var(--pink); margin-bottom:0.4rem; text-align:center; }
    .auth-title { font-family:'Playfair Display',serif; font-size:1.7rem; color:var(--text); text-align:center; margin-bottom:0.3rem; }
    .auth-sub { color:var(--muted); font-size:0.82rem; text-align:center; margin-bottom:2rem; }

    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    .form-group { margin-bottom:1.1rem; }
    .form-group.full { grid-column:1/-1; }
    .form-group label {
      display:block; font-size:0.74rem; font-weight:600;
      letter-spacing:0.08em; text-transform:uppercase; color:var(--text2); margin-bottom:6px;
    }
    .input-wrap { position:relative; }
    .input-wrap .i-icon {
      position:absolute; left:13px; top:50%; transform:translateY(-50%);
      font-size:0.95rem; pointer-events:none;
    }
    .form-group input {
      width:100%; padding:11px 14px 11px 40px;
      border:1.5px solid var(--border2); border-radius:10px;
      background:var(--bg2,#fdf8f9); color:var(--text);
      font-size:0.88rem; font-family:inherit; outline:none;
      transition:border-color 0.25s, box-shadow 0.25s; box-sizing:border-box;
    }
    .form-group input:focus {
      border-color:var(--pink); box-shadow:0 0 0 3px rgba(214,140,160,0.15); background:#fff;
    }
    .form-group input::placeholder { color:var(--muted); }
    .toggle-pw {
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      background:none; border:none; cursor:pointer; font-size:1rem; color:var(--muted);
    }
    .pw-strength { margin-top:5px; height:4px; border-radius:4px; background:var(--border); overflow:hidden; }
    .pw-strength-bar { height:100%; border-radius:4px; transition:width 0.3s, background 0.3s; width:0; }
    .pw-hint { font-size:0.7rem; color:var(--muted); margin-top:4px; }
    .auth-alert { padding:11px 16px; border-radius:10px; font-size:0.82rem; margin-bottom:1.2rem; }
    .auth-alert.error { background:#fdecea; color:#c0392b; border:1px solid #f5c6cb; }
    .auth-btn {
      width:100%; padding:13px; background:var(--pink); color:white; border:none;
      border-radius:50px; font-size:0.86rem; font-weight:600; font-family:inherit;
      cursor:pointer; letter-spacing:0.05em; transition:all 0.2s; margin-top:0.5rem;
    }
    .auth-btn:hover { opacity:0.88; transform:translateY(-1px); box-shadow:0 6px 20px rgba(214,140,160,0.3); }
    .auth-terms {
      font-size:0.74rem; color:var(--muted); text-align:center; margin-top:1rem; line-height:1.6;
    }
    .auth-terms a { color:var(--pink); }
    .auth-link { text-align:center; font-size:0.82rem; color:var(--muted); margin-top:1.2rem; }
    .auth-link a { color:var(--pink); font-weight:600; }
    .perks-strip {
      display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.8rem;
      padding:1rem; background:var(--bg2,#fdf8f9); border-radius:12px;
      border:1px solid var(--border); justify-content:center;
    }
    .perk { font-size:0.74rem; color:var(--text2); padding:4px 10px;
      background:white; border:1px solid var(--border2); border-radius:20px; }

    @media(max-width:480px) { .form-row{grid-template-columns:1fr;} }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="auth-page">
  <div class="auth-card">

    <div class="auth-top">
      <span class="logo-text">LY Jewels</span>
      <span class="logo-sub">Handcrafted with love 🎀</span>
    </div>

    <div class="auth-eyebrow">✦ Join the Family</div>
    <h1 class="auth-title">Create Your Account 🌸</h1>
    <p class="auth-sub">Join thousands of jewellery lovers today.</p>

    <div class="perks-strip">
      <span class="perk">🎀 Exclusive Offers</span>
      <span class="perk">💍 Wishlist Access</span>
      <span class="perk">📦 Order Tracking</span>
      <span class="perk">🚀 Fast Checkout</span>
    </div>

    <?php if($error): ?>
      <div class="auth-alert error">⚠️ <?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="signup.php">
      <div class="form-row">
        <div class="form-group">
          <label>Full Name *</label>
          <div class="input-wrap">
            <span class="i-icon">👤</span>
            <input type="text" name="name" placeholder="Your name" required
                   value="<?php echo isset($_POST['name'])?htmlspecialchars($_POST['name']):''; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Phone</label>
          <div class="input-wrap">
            <span class="i-icon">📱</span>
            <input type="tel" name="phone" placeholder="10-digit number"
                   value="<?php echo isset($_POST['phone'])?htmlspecialchars($_POST['phone']):''; ?>">
          </div>
        </div>
        <div class="form-group full">
          <label>Email Address *</label>
          <div class="input-wrap">
            <span class="i-icon">📧</span>
            <input type="email" name="email" placeholder="you@example.com" required
                   value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):''; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Password *</label>
          <div class="input-wrap">
            <span class="i-icon">🔒</span>
            <input type="password" name="password" id="pw1" placeholder="Min 6 chars" required oninput="checkStrength(this.value)">
            <button type="button" class="toggle-pw" onclick="togglePw('pw1',this)">👁️</button>
          </div>
          <div class="pw-strength"><div class="pw-strength-bar" id="strengthBar"></div></div>
          <div class="pw-hint" id="strengthHint">Enter a password</div>
        </div>
        <div class="form-group">
          <label>Confirm Password *</label>
          <div class="input-wrap">
            <span class="i-icon">🔒</span>
            <input type="password" name="confirm" id="pw2" placeholder="Repeat password" required>
            <button type="button" class="toggle-pw" onclick="togglePw('pw2',this)">👁️</button>
          </div>
        </div>
      </div>

      <button type="submit" class="auth-btn">Create My Account 💕</button>
    </form>

    <div class="auth-terms">
      By signing up, you agree to our
      <a href="shopping_policy.php">Shopping Policy</a> &amp;
      <a href="#">Privacy Policy</a>.
    </div>
    <div class="auth-link">Already have an account? <a href="login.php">Sign in 🎀</a></div>
    <div class="auth-link" style="margin-top:0.5rem;">
      <a href="index.php" style="color:var(--muted);font-size:0.78rem;">← Back to Home</a>
    </div>
  </div>
</div>

<script>
function togglePw(id, btn) {
  const f = document.getElementById(id);
  f.type = f.type === 'password' ? 'text' : 'password';
  btn.textContent = f.type === 'password' ? '👁️' : '🙈';
}
function checkStrength(pw) {
  const bar = document.getElementById('strengthBar');
  const hint = document.getElementById('strengthHint');
  let score = 0;
  if(pw.length >= 6) score++;
  if(pw.length >= 10) score++;
  if(/[A-Z]/.test(pw)) score++;
  if(/[0-9]/.test(pw)) score++;
  if(/[^A-Za-z0-9]/.test(pw)) score++;
  const levels = [
    {w:'0%',  c:'#e74c3c', t:'Too short'},
    {w:'25%', c:'#e74c3c', t:'Weak 😟'},
    {w:'50%', c:'#f39c12', t:'Fair 😐'},
    {w:'75%', c:'#2ecc71', t:'Good 😊'},
    {w:'100%',c:'#27ae60', t:'Strong 💪'},
  ];
  const l = levels[Math.min(score, 4)];
  bar.style.width = l.w; bar.style.background = l.c; hint.textContent = l.t;
}
</script>
</body>
</html>