<?php
// admin/admin_nav.php — include at top of every admin page AFTER session_start()
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title ?? 'Admin'; ?> — LY Jewels Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --pink:       #d68ca0;
      --pink-dark:  #b8687e;
      --pink-soft:  #f9eef1;
      --rose:       #e8b4c0;
      --sidebar-bg: #1a1118;
      --sidebar-w:  260px;
      --surface:    #ffffff;
      --surface2:   #fdf6f8;
      --border:     #f0dde4;
      --text:       #2d1f26;
      --text2:      #6b4f58;
      --muted:      #a08890;
      --success:    #27ae60;
      --warning:    #f39c12;
      --danger:     #e74c3c;
      --radius:     14px;
      --shadow:     0 4px 24px rgba(214,140,160,0.12);
      --shadow-lg:  0 8px 40px rgba(214,140,160,0.18);
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--surface2);
      color: var(--text);
      min-height: 100vh;
      display: flex;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--sidebar-bg);
      min-height: 100vh;
      position: fixed;
      left: 0; top: 0;
      display: flex;
      flex-direction: column;
      z-index: 100;
      border-right: 1px solid rgba(214,140,160,0.12);
    }

    .sidebar-brand {
      padding: 2rem 1.8rem 1.6rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-brand .logo-text {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: var(--pink);
      display: block;
      letter-spacing: 0.02em;
    }
    .sidebar-brand .logo-sub {
      font-size: 0.7rem;
      color: rgba(255,255,255,0.3);
      letter-spacing: 0.12em;
      text-transform: uppercase;
      margin-top: 2px;
      display: block;
    }
    .sidebar-badge {
      display: inline-block;
      background: var(--pink);
      color: white;
      font-size: 0.6rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 2px 8px;
      border-radius: 20px;
      margin-top: 6px;
    }

    .sidebar-nav {
      padding: 1.2rem 0;
      flex: 1;
    }
    .nav-section-label {
      font-size: 0.62rem;
      font-weight: 600;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.22);
      padding: 1rem 1.8rem 0.4rem;
    }
    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 1.8rem;
      color: rgba(255,255,255,0.55);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 400;
      transition: all 0.2s ease;
      position: relative;
      border-left: 3px solid transparent;
    }
    .sidebar-link:hover {
      color: rgba(255,255,255,0.9);
      background: rgba(255,255,255,0.04);
    }
    .sidebar-link.active {
      color: var(--pink);
      background: rgba(214,140,160,0.08);
      border-left-color: var(--pink);
      font-weight: 500;
    }
    .sidebar-link .nav-icon { font-size: 1rem; width: 20px; text-align: center; }
    .sidebar-link .nav-badge {
      margin-left: auto;
      background: var(--pink);
      color: white;
      font-size: 0.65rem;
      padding: 1px 7px;
      border-radius: 20px;
      font-weight: 600;
    }

    .sidebar-footer {
      padding: 1.4rem 1.8rem;
      border-top: 1px solid rgba(255,255,255,0.06);
    }
    .admin-avatar {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 1rem;
    }
    .avatar-circle {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, var(--pink), var(--pink-dark));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      color: white;
      font-weight: 600;
      flex-shrink: 0;
    }
    .avatar-info .name { font-size: 0.82rem; color: rgba(255,255,255,0.8); font-weight: 500; }
    .avatar-info .role { font-size: 0.68rem; color: rgba(255,255,255,0.3); }
    .logout-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      width: 100%;
      padding: 9px 14px;
      background: rgba(231,76,60,0.12);
      border: 1px solid rgba(231,76,60,0.2);
      border-radius: 8px;
      color: #e74c3c;
      font-size: 0.8rem;
      font-family: inherit;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.2s;
      font-weight: 500;
    }
    .logout-btn:hover { background: rgba(231,76,60,0.2); }

    /* ── MAIN CONTENT ── */
    .main-wrap {
      margin-left: var(--sidebar-w);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .topbar {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: 0 1px 12px rgba(214,140,160,0.07);
    }
    .topbar-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--text);
    }
    .topbar-right { display: flex; align-items: center; gap: 1rem; }
    .topbar-greeting {
      font-size: 0.8rem;
      color: var(--muted);
    }
    .topbar-greeting strong { color: var(--pink); }
    .topbar-store-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 16px;
      background: var(--pink-soft);
      border: 1px solid var(--rose);
      border-radius: 50px;
      color: var(--pink-dark);
      font-size: 0.78rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s;
    }
    .topbar-store-link:hover { background: var(--pink); color: white; }

    .page-content {
      padding: 2rem;
      flex: 1;
    }

    /* ── CARDS / STATS ── */
    .stat-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.2rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      box-shadow: var(--shadow);
      animation: fadeUp 0.4s ease both;
    }
    .stat-card:nth-child(2) { animation-delay: 0.06s; }
    .stat-card:nth-child(3) { animation-delay: 0.12s; }
    .stat-card:nth-child(4) { animation-delay: 0.18s; }
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(16px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .stat-icon {
      width: 52px; height: 52px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }
    .stat-icon.pink   { background: var(--pink-soft);           }
    .stat-icon.green  { background: #eafaf1;                    }
    .stat-icon.orange { background: #fef9e7;                    }
    .stat-icon.blue   { background: #eaf4fb;                    }
    .stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.7rem;
      color: var(--text);
      line-height: 1;
    }
    .stat-label { font-size: 0.75rem; color: var(--muted); margin-top: 3px; }

    /* ── PANEL / TABLE ── */
    .panel {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      margin-bottom: 2rem;
      animation: fadeUp 0.4s ease 0.1s both;
    }
    .panel-header {
      padding: 1.2rem 1.6rem;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .panel-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--text);
    }
    .panel-body { padding: 1.6rem; }

    table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    thead th {
      text-align: left;
      padding: 10px 14px;
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--muted);
      background: var(--surface2);
      border-bottom: 1px solid var(--border);
    }
    tbody td {
      padding: 12px 14px;
      border-bottom: 1px solid var(--border);
      color: var(--text);
      vertical-align: middle;
    }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: var(--pink-soft); transition: background 0.15s; }

    /* ── BADGES ── */
    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.72rem;
      font-weight: 600;
    }
    .badge-pending  { background: #fef9e7; color: #d4a017; border: 1px solid #f7dc6f; }
    .badge-delivered{ background: #eafaf1; color: #27ae60; border: 1px solid #a9dfbf; }
    .badge-ring     { background: var(--pink-soft); color: var(--pink-dark); border: 1px solid var(--rose); }

    /* ── BUTTONS ── */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 18px;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      border: none;
      text-decoration: none;
      transition: all 0.2s;
      letter-spacing: 0.03em;
    }
    .btn-pink  { background: var(--pink); color: white; }
    .btn-pink:hover { background: var(--pink-dark); transform: translateY(-1px); box-shadow: var(--shadow); }
    .btn-outline { background: transparent; color: var(--pink); border: 1.5px solid var(--pink); }
    .btn-outline:hover { background: var(--pink-soft); }
    .btn-danger { background: transparent; color: var(--danger); border: 1.5px solid var(--danger); }
    .btn-danger:hover { background: #fdecea; }
    .btn-success { background: var(--success); color: white; }
    .btn-success:hover { opacity: 0.88; }
    .btn-sm { padding: 5px 12px; font-size: 0.74rem; }

    /* ── FORMS ── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label {
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.07em;
      text-transform: uppercase;
      color: var(--text2);
    }
    .form-control {
      padding: 11px 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      background: var(--surface2);
      color: var(--text);
      font-size: 0.88rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
      border-color: var(--pink);
      box-shadow: 0 0 0 3px rgba(214,140,160,0.15);
      background: white;
    }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .form-control::placeholder { color: var(--muted); }

    /* ── ALERTS ── */
    .alert {
      padding: 12px 18px;
      border-radius: 10px;
      font-size: 0.84rem;
      margin-bottom: 1.4rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .alert-success { background: #eafaf1; color: #1e8449; border: 1px solid #a9dfbf; }
    .alert-error   { background: #fdecea; color: #c0392b; border: 1px solid #f5c6cb; }
    .alert-info    { background: var(--pink-soft); color: var(--pink-dark); border: 1px solid var(--rose); }

    /* ── PRODUCT THUMB ── */
    .product-thumb {
      width: 48px; height: 48px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid var(--border);
    }
    .product-name-cell { display: flex; align-items: center; gap: 10px; }

    /* ── FILE INPUT ── */
    .file-drop {
      border: 2px dashed var(--border);
      border-radius: 10px;
      padding: 1.5rem;
      text-align: center;
      color: var(--muted);
      font-size: 0.82rem;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      background: var(--surface2);
    }
    .file-drop:hover { border-color: var(--pink); background: var(--pink-soft); }
    .file-drop input[type=file] { display: none; }
    .file-drop .file-icon { font-size: 2rem; display: block; margin-bottom: 6px; }

    /* ── MESSAGE CARD ── */
    .msg-card {
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 1.2rem 1.4rem;
      margin-bottom: 1rem;
      background: var(--surface);
      transition: box-shadow 0.2s;
    }
    .msg-card:hover { box-shadow: var(--shadow); }
    .msg-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
    .msg-name { font-weight: 600; color: var(--text); font-size: 0.9rem; }
    .msg-email { font-size: 0.75rem; color: var(--muted); }
    .msg-date { font-size: 0.72rem; color: var(--muted); }
    .msg-body { font-size: 0.84rem; color: var(--text2); line-height: 1.6; }

    /* ── EMPTY STATE ── */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--muted);
    }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 1rem; display: block; }
    .empty-state p { font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 1rem; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main-wrap { margin-left: 0; }
      .form-grid { grid-template-columns: 1fr; }
      .stat-grid { grid-template-columns: 1fr 1fr; }
    }
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-brand">
    <span class="logo-text">LY Jewels</span>
    <span class="logo-sub">Admin Panel</span>
    <span class="sidebar-badge">✦ Admin</span>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <a href="dashboard.php" class="sidebar-link <?php echo $current==='dashboard.php'?'active':''; ?>">
      <span class="nav-icon">🏠</span> Dashboard
    </a>

    <div class="nav-section-label">Catalogue</div>
    <a href="manage_products.php" class="sidebar-link <?php echo $current==='manage_products.php'?'active':''; ?>">
      <span class="nav-icon">💍</span> Products
    </a>
    <a href="add_product.php" class="sidebar-link <?php echo $current==='add_product.php'?'active':''; ?>">
      <span class="nav-icon">➕</span> Add Product
    </a>

    <div class="nav-section-label">Store</div>
    <a href="orders.php" class="sidebar-link <?php echo $current==='orders.php'?'active':''; ?>">
      <span class="nav-icon">📦</span> Orders
    </a>
    <a href="messages.php" class="sidebar-link <?php echo $current==='messages.php'?'active':''; ?>">
      <span class="nav-icon">💌</span> Messages
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-avatar">
      <div class="avatar-circle"><?php echo strtoupper(substr($_SESSION['admin'],0,1)); ?></div>
      <div class="avatar-info">
        <div class="name"><?php echo htmlspecialchars($_SESSION['admin']); ?></div>
        <div class="role">Administrator</div>
      </div>
    </div>
    <a href="logout.php" class="logout-btn">🚪 Sign Out</a>
  </div>
</aside>

<div class="main-wrap">
  <div class="topbar">
    <div class="topbar-title"><?php echo $page_title ?? 'Dashboard'; ?></div>
    <div class="topbar-right">
      <span class="topbar-greeting">Hello, <strong><?php echo htmlspecialchars($_SESSION['admin']); ?></strong> 🎀</span>
      <a href="../index.php" target="_blank" class="topbar-store-link">🛍️ View Store</a>
    </div>
  </div>
  <div class="page-content">