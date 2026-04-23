<?php
session_start();
include 'include/db.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];

    if(empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $res  = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        $user = mysqli_fetch_assoc($res);
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit;
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — LY Jewels</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .auth-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--bg);
      padding: 7rem 1rem 3rem;
    }
    .auth-split {
      display: flex;
      width: 100%;
      max-width: 900px;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 16px 60px rgba(214,140,160,0.18);
      border: 1px solid var(--border2);
      animation: fadeUp 0.5s ease both;
    }
    @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

    .auth-deco {
      flex: 1;
      background: linear-gradient(160deg, #f9eef1 0%, #fce4ec 50%, #f8d7e3 100%);
      padding: 3rem 2.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-right: 1px solid var(--border2);
      min-width: 240px;
    }
    .deco-brand .logo-text { font-family:'Playfair Display',serif; font-size:1.8rem; color:var(--pink); display:block; }
    .deco-brand .logo-sub  { font-size:0.72rem; color:var(--muted); letter-spacing:0.1em; text-transform:uppercase; margin-top:4px; display:block; }
    .deco-perks { list-style:none; }
    .deco-perks li {
      display:flex; align-items:center; gap:10px;
      padding:9px 0; border-bottom:1px solid rgba(214,140,160,0.18);
      color:var(--text2); font-size:0.82rem;
    }
    .deco-perks li:last-child { border:none; }
    .deco-quote {
      font-family:'Playfair Display',serif;
      font-size:1.1rem;
      color:var(--pink-dark, #b8687e);
      font-style:italic;
      line-height:1.5;
    }

    .auth-form-side {
      flex: 1.2;
      background: var(--surface);
      padding: 3rem 2.8rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .auth-eyebrow {
      font-size:0.72rem; font-weight:600; letter-spacing:0.15em;
      text-transform:uppercase; color:var(--pink); margin-bottom:0.5rem;
    }
    .auth-title {
      font-family:'Playfair Display',serif; font-size:2rem;
      color:var(--text); margin-bottom:0.4rem; line-height:1.2;
    }
    .auth-sub { color:var(--muted); font-size:0.84rem; margin-bottom:2rem; }

    .form-group { margin-bottom:1.2rem; }
    .form-group label {
      display:block; font-size:0.74rem; font-weight:600;
      letter-spacing:0.08em; text-transform:uppercase;
      color:var(--text2); margin-bottom:6px;
    }
    .input-wrap { position:relative; }
    .input-wrap .i-icon {
      position:absolute; left:13px; top:50%;
      transform:translateY(-50%); font-size:0.95rem; pointer-events:none;
    }
    .form-group input {
      width:100%; padding:12px 14px 12px 40px;
      border:1.5px solid var(--border2); border-radius:10px;
      background:var(--bg2, #fdf8f9); color:var(--text);
      font-size:0.9rem; font-family:inherit; outline:none;
      transition:border-color 0.25s, box-shadow 0.25s;
      box-sizing:border-box;
    }
    .form-group input:focus {
      border-color:var(--pink);
      box-shadow:0 0 0 3px rgba(214,140,160,0.15);
      background:#fff;
    }
    .form-group input::placeholder { color:var(--muted); }
    .toggle-pw {
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      background:none; border:none; cursor:pointer; font-size:1rem; color:var(--muted);
    }
    .auth-alert {
      padding:11px 16px; border-radius:10px; font-size:0.82rem;
      margin-bottom:1.2rem; display:flex; align-items:center; gap:8px;
    }
    .auth-alert.error   { background:#fdecea; color:#c0392b; border:1px solid #f5c6cb; }
    .auth-alert.success { background:#eafaf1; color:#1e8449; border:1px solid #a9dfbf; }
    .auth-btn {
      width:100%; padding:13px;
      background:var(--pink); color:white; border:none; border-radius:50px;
      font-size:0.86rem; font-weight:600; font-family:inherit;
      cursor:pointer; letter-spacing:0.05em; transition:all 0.2s; margin-top:0.4rem;
    }
    .auth-btn:hover { opacity:0.88; transform:translateY(-1px); box-shadow:0 6px 20px rgba(214,140,160,0.3); }
    .auth-divider {
      display:flex; align-items:center; gap:1rem;
      margin:1.4rem 0; color:var(--muted); font-size:0.75rem;
    }
    .auth-divider::before, .auth-divider::after {
      content:''; flex:1; height:1px; background:var(--border);
    }
    .auth-link { text-align:center; font-size:0.82rem; color:var(--muted); margin-top:1.2rem; }
    .auth-link a { color:var(--pink); font-weight:600; }
    .forgot { text-align:right; margin:-0.7rem 0 1rem; }
    .forgot a { font-size:0.75rem; color:var(--muted); }
    .forgot a:hover { color:var(--pink); }

    @media(max-width:640px) { .auth-deco{display:none;} .auth-split{border-radius:16px;} }
  </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="auth-page">
  <div class="auth-split">

    <div class="auth-deco">
      <div class="deco-brand">
        <span class="logo-text">LY Jewels</span>
        <span class="logo-sub">Handcrafted with love</span>
      </div>
      <ul class="deco-perks">
        <li>🎀 Track your orders easily</li>
        <li>💍 Save items to your wishlist</li>
        <li>🚀 Faster checkout experience</li>
        <li>💌 Exclusive member offers</li>
        <li>🔄 Easy returns & exchanges</li>
      </ul>
      <div class="deco-quote">"Every piece tells a story. Let yours begin." ✨</div>
    </div>

    <div class="auth-form-side">
      <div class="auth-eyebrow">✦ Welcome Back</div>
      <h1 class="auth-title">Sign in to<br>LY Jewels 🌸</h1>
      <p class="auth-sub">Your jewellery journey continues here.</p>

      <?php if($error): ?>
        <div class="auth-alert error">⚠️ <?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <?php if(isset($_GET['registered'])): ?>
        <div class="auth-alert success">🎉 Account created! Welcome to LY Jewels. Please sign in.</div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-group">
          <label>Email Address</label>
          <div class="input-wrap">
            <span class="i-icon">📧</span>
            <input type="email" name="email" placeholder="you@example.com" required
                   value="<?php echo isset($_POST['email'])?htmlspecialchars($_POST['email']):''; ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Password</label>
          <div class="input-wrap">
            <span class="i-icon">🔒</span>
            <input type="password" name="password" id="pwField" placeholder="••••••••" required>
            <button type="button" class="toggle-pw" onclick="togglePw('pwField',this)">👁️</button>
          </div>
        </div>
        <div class="forgot"><a href="#">Forgot password?</a></div>
        <button type="submit" class="auth-btn">Sign In 💕</button>
      </form>

      <div class="auth-divider">or</div>
      <div class="auth-link">New here? <a href="signup.php">Create a free account 🎀</a></div>
      <div class="auth-link" style="margin-top:0.6rem;">
        <a href="index.php" style="color:var(--muted);font-size:0.78rem;">← Back to Home</a>
      </div>
    </div>

  </div>
</div>

<script>
function togglePw(id, btn) {
  const f = document.getElementById(id);
  f.type = f.type === 'password' ? 'text' : 'password';
  btn.textContent = f.type === 'password' ? '👁️' : '🙈';
}
</script>
</body>
</html>