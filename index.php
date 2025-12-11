<?php
require_once 'config.php';
require_once 'functions.php';

// Productos
$stmt = $pdo->query("SELECT id, name, description, price, image_path FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.product-card {
    transition: transform 0.2s;
    cursor: pointer;
}
.product-card:hover {
    transform: scale(1.03);
}
.product-image {
    object-fit: cover;
    height: 200px;
    width: 100%;
}
</style>

<div class="container mt-4">
    <h2 class="mb-4">Products</h2>

    <div class="row g-4">
        <?php foreach ($products as $p): ?>
            <div class="col-md-4 col-sm-6">
                <a href="product.php?id=<?php echo (int)$p['id']; ?>" class="text-decoration-none text-dark">
                    <div class="card product-card h-100">
                        <?php if (!empty($p['image_path'])): ?>
                            <img src="<?php echo e($p['image_path']); ?>" class="card-img-top product-image" alt="<?php echo e($p['name']); ?>">
                        <?php else: ?>
                            <img src="placeholder.png" class="card-img-top product-image" alt="Sin imagen">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo e($p['name']); ?></h5>
                            <p class="card-text"><?php echo nl2br(e($p['description'])); ?></p>
                            <p class="fw-bold">Prezioa: <?php echo e(number_format($p['price'], 2)); ?> €</p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
