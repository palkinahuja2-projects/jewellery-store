<?php
// ============================================================
//  payment_verify.php
//  Verifies Razorpay signature, saves order, clears cart
// ============================================================
session_start();
include 'include/db.php';
include 'include/razorpay_config.php';

// ── Collect POST data ──
$payment_id  = trim($_POST['razorpay_payment_id']  ?? '');
$order_id    = trim($_POST['razorpay_order_id']    ?? '');
$signature   = trim($_POST['razorpay_signature']   ?? '');
$name        = trim($_POST['customer_name']        ?? '');
$email       = trim($_POST['customer_email']       ?? '');
$phone       = trim($_POST['customer_phone']       ?? '');
$address     = trim($_POST['customer_address']     ?? '');
$amount      = floatval($_POST['total_amount']     ?? 0);

// ── Basic validation ──
if (!$payment_id || !$order_id || !$signature || !$name || !$email) {
    header('Location: payment_failed.php?reason=missing_data');
    exit;
}

// ── Signature verification (CRITICAL — prevents fraud) ──
// Razorpay signs: order_id + "|" + payment_id using HMAC-SHA256
$expected_signature = hash_hmac(
    'sha256',
    $order_id . '|' . $payment_id,
    RAZORPAY_KEY_SECRET
);

if (!hash_equals($expected_signature, $signature)) {
    // Signature mismatch → tampered request
    header('Location: payment_failed.php?reason=signature_mismatch');
    exit;
}

// ── Get cart snapshot from session ──
$cart_snapshot = '';
if (isset($_SESSION['pending_order']['cart_snapshot'])) {
    $cart_snapshot = $_SESSION['pending_order']['cart_snapshot'];
}

// ── Save order to database ──
$stmt = mysqli_prepare($conn,
    "INSERT INTO orders
     (razorpay_order_id, razorpay_payment_id, razorpay_signature,
      customer_name, customer_email, customer_phone, customer_address,
      total_amount, status, cart_snapshot)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'paid', ?)"
);

mysqli_stmt_bind_param($stmt, 'ssssssdss',
    $order_id,
    $payment_id,
    $signature,
    $name,
    $email,
    $phone,
    $address,
    $amount,
    $cart_snapshot
);

mysqli_stmt_execute($stmt);
$db_order_id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// ── Clear cart & pending order ──
$_SESSION['cart']          = [];
$_SESSION['pending_order'] = null;

// ── Store success info for the success page ──
$_SESSION['order_success'] = [
    'db_id'      => $db_order_id,
    'payment_id' => $payment_id,
    'order_id'   => $order_id,
    'name'       => $name,
    'email'      => $email,
    'amount'     => $amount,
];

header('Location: payment_success.php');
exit;
