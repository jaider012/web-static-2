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
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - My Delights' : 'My Delights - Restaurante de Comida Gourmet y Eventos'; ?></title>
    
    <!-- Meta SEO -->
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'My Delights ofrece la mejor experiencia culinaria con menú a la carta, comida corriente y servicios para eventos. Disfruta de nuestros platos preparados con ingredientes frescos y de alta calidad.'; ?>" />
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? $pageKeywords : 'restaurante, comida gourmet, eventos, menú a la carta, comida corriente, My Delights, cocina local'; ?>" />
    <meta name="author" content="My Delights" />
    <meta name="robots" content="index, follow" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - My Delights' : 'My Delights - Restaurante'; ?>" />
    <meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'My Delights ofrece la mejor experiencia culinaria con menú a la carta, comida corriente y servicios para eventos.'; ?>" />
    <meta property="og:image" content="<?php echo isset($pageImage) ? $pageImage : 'images/logo.png'; ?>" />
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
    <meta property="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - My Delights' : 'My Delights - Restaurante'; ?>" />
    <meta property="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'My Delights ofrece la mejor experiencia culinaria con menú a la carta, comida corriente y servicios para eventos.'; ?>" />
    <meta property="twitter:image" content="<?php echo isset($pageImage) ? $pageImage : 'images/logo.png'; ?>" />
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/seo.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Structured Data for Restaurant -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Restaurant",
      "name": "My Delights",
      "image": "images/logo.png",
      "url": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>",
      "telephone": "+573001234567",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Calle Principal #123",
        "addressLocality": "Ciudad",
        "addressRegion": "Región",
        "postalCode": "12345",
        "addressCountry": "CO"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 4.7110,
        "longitude": -74.0721
      },
      "servesCuisine": ["Gourmet", "Local", "Internacional"],
      "priceRange": "$$-$$$",
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
          "opens": "12:00",
          "closes": "22:00"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Saturday", "Sunday"],
          "opens": "12:00",
          "closes": "23:00"
        }
      ],
      "menu": "<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>/menu-carta.php",
      "acceptsReservations": "True"
    }
    </script>
</head>
<body>
    <!-- Enlace de accesibilidad para saltar al contenido principal -->
    <a href="#main-content" class="skip-to-content">Saltar al contenido principal</a>
    
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
                <button class="navbar-toggler" type="button" id="navbar-toggler" aria-label="Menú de navegación">
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php" title="Página de inicio">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'menu-carta.php' ? 'active' : ''; ?>" href="menu-carta.php" title="Nuestro menú a la carta">Menú a la Carta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'menu-corriente.php' ? 'active' : ''; ?>" href="menu-corriente.php" title="Comida corriente del día">Comida Corriente</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'eventos.php' ? 'active' : ''; ?>" href="eventos.php" title="Servicios para eventos">Eventos</a>
                        </li>
                        <?php if (!$isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'registro.php' ? 'active' : ''; ?>" href="registro.php" title="Regístrate o inicia sesión">Registro/Login</a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'perfil.php' ? 'active' : ''; ?>" href="perfil.php" title="Accede a tu perfil">Mi Perfil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'cotizaciones.php' ? 'active' : ''; ?>" href="cotizaciones.php" title="Solicita una cotización">Cotizaciones</a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'carrito.php' ? 'active' : ''; ?>" href="carrito.php" title="Ver carrito de compras">
                                <i class="fas fa-shopping-cart"></i>
                                <span id="cart-count" class="badge"><?php echo $cartCount; ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content"> 