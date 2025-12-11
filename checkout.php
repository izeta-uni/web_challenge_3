<?php
require_once 'config.php';
require_once 'functions.php';

if (!current_user_id()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' ||
    !check_csrf_token($_POST['csrf_token'] ?? '')) {
    die("Invalid order request.");
}

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    die("Your cart is empty.");
}

$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$rows = $stmt->fetchAll();

$prices = [];
$names = [];
foreach ($rows as $row) {
    $prices[$row['id']] = (float)$row['price'];
    $names[$row['id']] = $row['name'];
}

$total = 0.0;
foreach ($cart as $pid => $qty) {
    if (!isset($prices[$pid])) continue;
    $total += $prices[$pid] * $qty;
}

// Guardar orden
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->execute([current_user_id(), $total]);
    $orderId = (int)$pdo->lastInsertId();

    $stmtItem = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, unit_price)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($cart as $pid => $qty) {
        if (!isset($prices[$pid])) continue;
        $stmtItem->execute([$orderId, $pid, $qty, $prices[$pid]]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
} catch (Exception $e) {
    $pdo->rollBack();
    die("There was an error processing your order.");
}
?>
<?php include 'header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center" style="background-color:#d8a7ca; color:#fff;">
                    <h2>Thank you for your purchase!</h2>
                </div>
                <div class="card-body">
                    <p class="lead text-center">Your purchase has been successfully completed.</p>
                    <h5>Order ID: <span class="fw-bold"><?php echo $orderId; ?></span></h5>
                    <hr>
                    <h5>Order Summary:</h5>
                    <ul class="list-group mb-3">
                        <?php foreach ($cart as $pid => $qty): ?>
                            <?php if (!isset($names[$pid])) continue; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo e($names[$pid]); ?> x <?php echo (int)$qty; ?>
                                <span><?php echo e(number_format($prices[$pid]*$qty,2)); ?> €</span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            Total
                            <span><?php echo e(number_format($total,2)); ?> €</span>
                        </li>
                    </ul>
                    <p class="text-center">You will receive an email with more information about your order shortly.</p>
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-lg" style="background-color:#d8a7ca; color:#fff;">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
