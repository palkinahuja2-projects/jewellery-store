<?php
session_start();
include '../include/db.php';

$error = '';

if(isset($_POST['login'])) {
    $username = clean($conn, $_POST['username']);
    $password = clean($conn, $_POST['password']);

    $result = mysqli_query($conn,
        "SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Invalid username or password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — LY Jewels</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --pink: #d68ca0; --pink-dark: #b8687e; --pink-soft: #f9eef1;
      --rose: #e8b4c0; --sidebar-bg: #1a1118; --surface: #ffffff;
      --surface2: #fdf6f8; --border: #f0dde4; --border2: #e8cdd5;
      --text: #2d1f26; --text2: #6b4f58; --muted: #a08890;
      --radius: 14px;
    }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--sidebar-bg);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }
    /* Decorative blobs */
    body::before {
      content: '';
      position: fixed;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(214,140,160,0.15) 0%, transparent 70%);
      top: -100px; right: -100px;
      border-radius: 50%;
      pointer-events: none;
    }
    body::after {
      content: '';
      position: fixed;
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(214,140,160,0.08) 0%, transparent 70%);
      bottom: -80px; left: -80px;
      border-radius: 50%;
      pointer-events: none;
    }

    .login-wrap {
      display: flex;
      width: 100%;
      max-width: 880px;
      min-height: 540px;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 24px 80px rgba(0,0,0,0.5);
      position: relative;
      z-index: 1;
      margin: 1rem;
    }

    /* Left decorative panel */
    .login-left {
      flex: 1;
      background: linear-gradient(160deg, #2d1520 0%, #1a1118 60%, #0f0b0d 100%);
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-right: 1px solid rgba(214,140,160,0.12);
      min-width: 260px;
    }
    .left-brand .logo-text {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: var(--pink);
      display: block;
      letter-spacing: 0.02em;
    }
    .left-brand .logo-sub {
      font-size: 0.72rem;
      color: rgba(255,255,255,0.3);
      letter-spacing: 0.15em;
      text-transform: uppercase;
      margin-top: 4px;
      display: block;
    }
    .left-features { list-style: none; }
    .left-features li {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      color: rgba(255,255,255,0.5);
      font-size: 0.82rem;
    }
    .left-features li:last-child { border-bottom: none; }
    .left-features li span { font-size: 1rem; }
    .left-copy {
      font-size: 0.7rem;
      color: rgba(255,255,255,0.2);
      letter-spacing: 0.05em;
    }

    /* Right form */
    .login-right {
      flex: 1.2;
      background: var(--surface);
      padding: 3rem 2.8rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .login-eyebrow {
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--pink);
      margin-bottom: 0.6rem;
    }
    .login-title {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: var(--text);
      margin-bottom: 0.4rem;
      line-height: 1.2;
    }
    .login-sub { color: var(--muted); font-size: 0.84rem; margin-bottom: 2rem; }

    .form-group { margin-bottom: 1.2rem; }
    .form-group label {
      display: block;
      font-size: 0.74rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text2);
      margin-bottom: 6px;
    }
    .input-wrap { position: relative; }
    .input-wrap .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      pointer-events: none;
    }
    .form-group input {
      width: 100%;
      padding: 12px 14px 12px 42px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      background: var(--surface2);
      color: var(--text);
      font-size: 0.9rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.25s, box-shadow 0.25s;
    }
    .form-group input:focus {
      border-color: var(--pink);
      box-shadow: 0 0 0 3px rgba(214,140,160,0.15);
      background: white;
    }
    .form-group input::placeholder { color: var(--muted); }
    .toggle-pw {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      color: var(--muted);
      padding: 4px;
    }

    .alert-error {
      background: #fdecea;
      color: #c0392b;
      border: 1px solid #f5c6cb;
      border-radius: 10px;
      padding: 11px 16px;
      font-size: 0.82rem;
      margin-bottom: 1.2rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .login-btn {
      width: 100%;
      padding: 13px;
      background: var(--pink);
      color: white;
      border: none;
      border-radius: 50px;
      font-size: 0.86rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      letter-spacing: 0.05em;
      transition: all 0.2s;
      margin-top: 0.4rem;
    }
    .login-btn:hover {
      background: var(--pink-dark);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(184,104,126,0.3);
    }

    .login-footer {
      margin-top: 1.8rem;
      text-align: center;
      font-size: 0.75rem;
      color: var(--muted);
    }
    .login-footer a { color: var(--pink); font-weight: 600; }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .login-wrap { animation: fadeUp 0.5s ease both; }

    @media (max-width: 600px) {
      .login-left { display: none; }
      .login-wrap { border-radius: 16px; }
    }
  </style>
</head>
<body>

<div class="login-wrap">

  <!-- Left panel -->
  <div class="login-left">
    <div class="left-brand">
      <span class="logo-text">LY Jewels</span>
      <span class="logo-sub">Admin Dashboard</span>
    </div>

    <ul class="left-features">
      <li><span>💍</span> Manage your jewellery catalogue</li>
      <li><span>📦</span> Track and fulfil orders</li>
      <li><span>💌</span> Respond to customer messages</li>
      <li><span>📊</span> Monitor store performance</li>
      <li><span>🎀</span> Keep your store beautiful</li>
    </ul>

    <div class="left-copy">© 2025 LY Jewels. Crafted with Love ♡</div>
  </div>

  <!-- Right form -->
  <div class="login-right">
    <div class="login-eyebrow">✦ Secure Access</div>
    <h1 class="login-title">Welcome back,<br>Admin 🌸</h1>
    <p class="login-sub">Sign in to manage your jewellery store.</p>

    <?php if($error): ?>
      <div class="alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label>Username</label>
        <div class="input-wrap">
          <span class="input-icon">👤</span>
          <input type="text" name="username" placeholder="admin" required
                 value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <span class="input-icon">🔒</span>
          <input type="password" name="password" id="pwField" placeholder="••••••••" required>
          <button type="button" class="toggle-pw" onclick="togglePw()">👁️</button>
        </div>
      </div>

      <button type="submit" name="login" class="login-btn">Sign In to Dashboard →</button>
    </form>

    <div class="login-footer">
      <a href="../index.php">← Back to Store</a>
    </div>
  </div>

</div>

<script>
function togglePw() {
  const f = document.getElementById('pwField');
  f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>