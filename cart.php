<?php
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // product_id => quantity
}

// Añadir producto GET
if (isset($_GET['add'])) {
    $pid = (int)$_GET['add'];
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

// Preparar items y total
$items = [];
$total = 0.0;

if ($_SESSION['cart']) {
    $ids = array_keys($_SESSION['cart']);
    if ($ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $qty = $_SESSION['cart'][$row['id']];
            $lineTotal = $row['price'] * $qty;
            $items[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'qty' => $qty,
                'line_total' => $lineTotal,
            ];
            $total += $lineTotal;
        }
    }
}
?>
<?php include 'header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2>Zure saskia</h2>

    <?php if (!$items): ?>
        <p>Hutsik dago.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produktua</th>
                    <th>Kopurua</th>
                    <th>Unitate prezioa</th>
                    <th>Guztira</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?php echo $it['name']; ?></td>
                        <td><?php echo (int)$it['qty']; ?></td>
                        <td><?php echo number_format($it['price'], 2); ?> €</td>
                        <td><?php echo number_format($it['line_total'], 2); ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Guztira: <strong><?php echo number_format($total, 2); ?> €</strong></p>

        <form method="post" action="checkout.php">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <button type="submit" class="btn" style="background-color:#d8a7ca; color:#fff;">Buy</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
