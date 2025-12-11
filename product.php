<?php
require_once 'config.php';
require_once 'functions.php';

// ✅ EJEMPLO SEGURO — SQLi + IDOR
// El ID se convierte a (int) para evitar inyección.
$id = (int)($_GET['id'] ?? 0);

// ✅ CONSULTA SEGURA
// Se usan Prepared Statements para evitar SQLi.
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

// Si un alumno prueba:
//    ?id=0 OR 1=1
// La consulta resultante será:
//
//    SELECT * FROM products WHERE id = 0 OR 1=1
//
// → Devuelve el primer producto de la base de datos

if (!$product) {
    http_response_code(404);
    die("Producto no existe.");
}

// ✅ REVIEWS también segura
// Se usan Prepared Statements.
$stmtReviews = $pdo->prepare("
    SELECT r.*, u.username 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$stmtReviews->execute([$id]);
$reviews = $stmtReviews->fetchAll();



// Enviar review
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_id()) {
    if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = "CSRF token incorrecto.";
    } else {
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        $uploadedFilePath = null;

        // Manejar archivo subido si existe
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            $fileInfo = pathinfo($_FILES['image']['name']);
            $extension = strtolower($fileInfo['extension']);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);

            if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimes)) {
                $errors[] = "Error: Only image files are allowed.";
            } else {
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                // Only if validation passes, move the file
                $filename = uniqid('img_', true) . '.' . $extension;
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $uploadedFilePath = 'uploads/' . $filename;
                } else {
                    $errors[] = "Error al subir el archivo.";
                }
            }
        }

        if ($rating < 1 || $rating > 5) {
            $errors[] = "La puntuación debe estar entre 1 y 5.";
        }

        if (!$errors) {
            $stmt = $pdo->prepare("
                INSERT INTO reviews (user_id, product_id, rating, comment, image_path)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([current_user_id(), $id, $rating, $comment, $uploadedFilePath]);
            header("Location: product.php?id=" . $id);
            exit;
        }
    }
}
?>
<?php include 'header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="row g-4">
        <!-- Imagen del producto -->
        <div class="col-md-5">
            <?php if (!empty($product['image_path'])): ?>
                <div class="card shadow-sm">
                    <img src="<?php echo e($product['image_path']); ?>" class="card-img-top" style="object-fit: cover; max-height: 400px;" alt="<?php echo e($product['name']); ?>">
                </div>
            <?php endif; ?>
        </div>

        <!-- Información del producto -->
        <div class="col-md-7">
            <h2 class="mb-3"><?php echo e($product['name']); ?></h2>
            <p><?php echo nl2br(e($product['description'])); ?></p>
            <p class="fw-bold fs-5">Price: <?php echo e(number_format($product['price'], 2)); ?> €</p>

            <!-- Botón Añadir al carrito -->
            <a href="cart.php?add=<?php echo (int)$product['id']; ?>" class="btn btn-primary" style="background-color:#d8a7ca; border-color:#d8a7ca; color:#fff;">
                Add to cart
            </a>
        </div>
    </div>

    <hr class="my-4">

    <h3 class="mb-3">Opinions</h3>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-danger"><?php echo e($e); ?></div>
    <?php endforeach; ?>

    <div class="row g-3 mb-4">
        <?php foreach ($reviews as $r): ?>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($r['username']); ?> (<?php echo (int)$r['rating']; ?>/5)</h5>
                        <p class="card-text"><?php echo nl2br(e($r['comment'])); ?></p>
                        <?php if ($r['image_path']): ?>
                            <?php 
                                $ext = strtolower(pathinfo($r['image_path'], PATHINFO_EXTENSION)); 
                                $imageExtensions = ['jpg','jpeg','png','gif','webp'];
                            ?>
                            <?php if(in_array($ext, $imageExtensions)): ?>
                                <img src="<?php echo e($r['image_path']); ?>" alt="archivo review" style="max-width:100%; max-height:150px; object-fit: cover;">
                            <?php else: ?>
                                <p>Archivo adjunto: <a href="<?php echo e($r['image_path']); ?>" target="_blank"><?php echo basename($r['image_path']); ?></a></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (current_user_id()): ?>
        <h4 class="mb-3">Leave your opinion</h4>
        <form method="post" enctype="multipart/form-data" class="mb-5">
            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">

            <div class="mb-3">
                <label>Points (1-5):
                    <input type="number" min="1" max="5" name="rating" class="form-control" style="max-width:100px;">
                </label>
            </div>

            <div class="mb-3">
                <label>Comment:
                    <textarea name="comment" class="form-control"></textarea>
                </label>
            </div>

            <div class="mb-3">
                <label>Upload file:
                    <input type="file" name="image" class="form-control">
                </label>
            </div>

            <button type="submit" class="btn" style="background-color:#d8a7ca; color:#ffffff;">Send</button>
        </form>
    <?php else: ?>
        <p>Leave an opinion, <a href="login.php">log in</a>.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
