<?php
// ============================================================
//  create_razorpay_order.php
//  Called via AJAX from checkout.php
//  Creates a Razorpay order and returns the order_id as JSON
// ============================================================
session_start();
include 'include/db.php';
include 'include/razorpay_config.php';

header('Content-Type: application/json');

// ── Guard: cart must not be empty ──
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

// ── Calculate total from DB prices (never trust client-side amounts) ──
$total = 0;
$cart_items = [];
foreach ($_SESSION['cart'] as $id => $qty) {
    $id  = intval($id);
    $qty = intval($qty);
    $res = mysqli_query($conn, "SELECT id, name, price FROM products WHERE id = $id LIMIT 1");
    $p   = mysqli_fetch_assoc($res);
    if (!$p) continue;
    $sub         = $p['price'] * $qty;
    $total      += $sub;
    $cart_items[] = ['name' => $p['name'], 'qty' => $qty, 'price' => $p['price']];
}

if ($total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid cart total']);
    exit;
}

// Razorpay expects amount in PAISE (1 INR = 100 paise)
$amount_paise = (int) round($total * 100);

// ── Call Razorpay Orders API ──
$payload = json_encode([
    'amount'   => $amount_paise,
    'currency' => RAZORPAY_CURRENCY,
    'receipt'  => 'ly_' . time() . '_' . rand(1000, 9999),
    'notes'    => [
        'store'       => RAZORPAY_BUSINESS_NAME,
        'cart_items'  => count($cart_items),
    ],
]);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_USERPWD        => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: application/json'],
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_TIMEOUT        => 30,
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err  = curl_error($ch);
curl_close($ch);

if ($curl_err) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL Error: ' . $curl_err]);
    exit;
}

$data = json_decode($response, true);

if ($http_code !== 200 || !isset($data['id'])) {
    http_response_code(500);
    echo json_encode([
        'error'   => 'Razorpay API error',
        'details' => $data['error']['description'] ?? 'Unknown error',
    ]);
    exit;
}

// Store cart snapshot and amount in session for verification later
$_SESSION['pending_order'] = [
    'razorpay_order_id' => $data['id'],
    'amount'            => $total,
    'cart_snapshot'     => json_encode($cart_items),
];

// Return order details to frontend JS
echo json_encode([
    'order_id'  => $data['id'],
    'amount'    => $amount_paise,
    'currency'  => RAZORPAY_CURRENCY,
    'key_id'    => RAZORPAY_KEY_ID,
    'name'      => RAZORPAY_BUSINESS_NAME,
    'description' => RAZORPAY_DESCRIPTION,
    'theme_color' => RAZORPAY_THEME_COLOR,
]);
