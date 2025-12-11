<?php
require_once 'config.php';
require_once 'functions.php';

$q = trim($_GET['q'] ?? '');

$products = [];
if ($q !== '') {
    $sql = "
        SELECT id, name, price, image_path
        FROM products
        WHERE name LIKE '%$q%' OR description LIKE '%$q%'
        ORDER BY id DESC
    ";
    $result = $pdo->query($sql);
    $products = $result->fetchAll();
}
?>
<?php include 'header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h2 class="mb-4">Search Tool (Vulnerable)</h2>

    <form method="get" class="row g-2 mb-4">
        <div class="col-md-8">
            <input type="text" name="q" value="<?php echo $q; ?>" class="form-control" placeholder="Search product...">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn w-100" style="background-color: #d8a7ca; color: #ffffff;">
                Search
            </button>
        </div>
    </form>

    <?php if ($q !== ''): ?>
        <!-- Vulnerable: el texto de búsqueda no se escapa -->
        <h4 class="mb-3">Results for "<?php echo $q; ?>":</h4>
        <?php if ($products): ?>
            <div class="row g-4">
                <?php foreach ($products as $p): ?>
                    <div class="col-md-4 col-sm-6">
                        <a href="product.php?id=<?php echo (int)$p['id']; ?>" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm product-card">
                                <?php if (!empty($p['image_path'])): ?>
                                    <img src="<?php echo $p['image_path']; ?>" class="card-img-top" style="height:200px; object-fit:cover;" alt="<?php echo $p['name']; ?>">
                                <?php else: ?>
                                    <img src="placeholder.png" class="card-img-top" style="height:200px; object-fit:cover;" alt="No image">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $p['name']; ?></h5>
                                    <p class="fw-bold">Price: <?php echo number_format($p['price'], 2); ?> €</p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No results found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
