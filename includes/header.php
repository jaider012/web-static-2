<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$userName = $isLoggedIn ? $_SESSION['nombre'] : '';
$userType = $isLoggedIn ? $_SESSION['tipo_cliente'] : '';
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Get current page for active link
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - My Delights' : 'My Delights - Restaurante'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php if ($isLoggedIn): ?>
    <div class="test-user-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <h5>Bienvenido, <?php echo htmlspecialchars($userName); ?></h5>
            <p>Tipo de cliente: <strong><?php echo ucfirst(htmlspecialchars($userType)); ?></strong></p>
            <a href="logout.php" class="test-user-btn">Cerrar Sesión</a>
        </div>
    </div>
    <?php endif; ?>
    
    <header>
        <nav class="navbar">
            <div class="container">
                <button class="navbar-toggler" type="button" id="navbar-toggler">
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'menu-carta.php' ? 'active' : ''; ?>" href="menu-carta.php">Menú a la Carta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'menu-corriente.php' ? 'active' : ''; ?>" href="menu-corriente.php">Comida Corriente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'eventos.php' ? 'active' : ''; ?>" href="eventos.php">Eventos</a>
                        </li>
                        <?php if (!$isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'registro.php' ? 'active' : ''; ?>" href="registro.php">Registro/Login</a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'perfil.php' ? 'active' : ''; ?>" href="perfil.php">Mi Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'cotizaciones.php' ? 'active' : ''; ?>" href="cotizaciones.php">Cotizaciones</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'carrito.php' ? 'active' : ''; ?>" href="carrito.php">
                                <i class="fas fa-shopping-cart"></i>
                                <span id="cart-count" class="badge"><?php echo $cartCount; ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main> 