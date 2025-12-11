<?php
require_once 'config.php';
require_once 'functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $address  = $_POST['address'] ?? '';
    $phone    = $_POST['phone'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = "Username, email y password son obligatorios.";
    } else {

        $queryCheck = "
            SELECT id FROM users 
            WHERE username = '$username' 
               OR email = '$email'
        ";
        $result = $pdo->query($queryCheck);

        if ($result->fetch()) {
            $errors[] = "El usuario o email ya están en uso.";
        } else {

            $queryInsert = "
                INSERT INTO users (username, email, password_hash, address, phone)
                VALUES ('$username', '$email', '$password', '$address', '$phone')
            ";

            $pdo->exec($queryInsert);

            header("Location: login.php");
            exit;
        }
    }
}

?>
<?php include 'header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center" style="background-color:#d8a7ca; color:#fff;">
                    <h2>Registro de usuario</h2>
                </div>
                <div class="card-body">
                    <?php foreach ($errors as $e): ?>
                        <div class="alert alert-danger"><?php echo e($e); ?></div>
                    <?php endforeach; ?>

                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-lg" style="background-color:#d8a7ca; color:#fff;">Registrarse</button>
                        </div>
                    </form>

                    <p class="text-center mt-3">
                        ¿Ya tienes cuenta? <a href="login.php" style="color:#d8a7ca;">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
