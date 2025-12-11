<?php
require_once 'config.php';
require_once 'functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = "CSRF token okerra.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $query = "SELECT id, password_hash 
                FROM users 
                WHERE username = '$username'";

        $result = $pdo->query($query);
        $user = $result->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = "Kredentzial okerrak.";
        } else {
            $_SESSION['user_id'] = (int)$user['id'];
            header("Location: index.php");
            exit;
        }

    }
}
?>
<?php include 'header.php'; ?>

<h2>Saioa hasi</h2>

<?php foreach ($errors as $e): ?>
    <p style="color:red;"><?php echo e($e); ?></p>
<?php endforeach; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
    <label>Username:
        <input type="text" name="username">
    </label><br>
    <label>Password:
        <input type="password" name="password">
    </label><br>
    <button type="submit">Sartu</button>
</form>

<?php include 'footer.php'; ?>
