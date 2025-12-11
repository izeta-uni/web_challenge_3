<?php
// header.php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Handmade products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Solo cambiar el color de la barra a malva */
        .navbar-custom {
            background-color: #d8a7ca; /* rosa empolvado / malva */
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff; /* texto blanco */
        }
        .navbar-custom .nav-link:hover {
            color: #f8f0f8; /* hover más claro */
        }
    </style>
</head>
<body>

<!-- Navbar con color malva -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Hanmade products</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
                <?php if (current_user_id()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Log out</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Create account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Log in</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<header class="container mt-4 mb-4 text-center">
    <h1 class="display-5">Handmade art shop</h1>
</header>
