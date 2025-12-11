<?php
// functions.php

// OHIKO AKATSA (XSS): datu dinamikoak zuzenean echo egitea.
// Hemen beti escapatuko dugu.
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// CSRF token sinple bat:
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function check_csrf_token(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

// IDOR saihesteko helper:
// OHIKO AKATSA (IDOR): /order.php?id=123 -> ez egiaztatzea eskaera hori norena den.
function user_owns_order(PDO $pdo, int $orderId, int $userId): bool {
    $stmt = $pdo->prepare("SELECT 1 FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$orderId, $userId]);
    return (bool)$stmt->fetchColumn();
}
