<?php
// Set page title
$pageTitle = "Inicio";

// Include necessary files
require_once 'config/database.php';
require_once 'includes/menu.php';

// Get featured menu items
$featuredItems = getFeaturedItems(3); // Get 3 random items

// Include header
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h1>Bienvenidos a My Delights</h1>
                <p class="lead">
                    Disfruta de la mejor experiencia gastronómica con nuestros
                    deliciosos platos preparados con ingredientes frescos y de alta
                    calidad.
                </p>
                <a href="menu-carta.php" class="btn btn-primary btn-lg">Ver Menú</a>
            </div>
            <div class="col-6">
                <img
                    src="https://jumboalacarta.com.ar/wp-content/uploads/2019/06/Captura-de-pantalla-2019-06-12-a-las-14.52.29.png"
                    alt="Plato destacado"
                    class="rounded"
                />
            </div>
        </div>
    </div>
</section>

<!-- Featured Dishes -->
<section class="featured-dishes">
    <div class="container">
        <h2 class="text-center mb-5">Platos Destacados</h2>
        <div class="row">
            <?php if (!empty($featuredItems)): ?>
                <?php foreach ($featuredItems as $item): ?>
                    <div class="col-4">
                        <div class="card h-100">
                            <img
                                src="<?php echo !empty($item['imagen']) ? 'images/' . htmlspecialchars($item['imagen']) : 'https://jumboalacarta.com.ar/wp-content/uploads/2019/06/Captura-de-pantalla-2019-06-12-a-las-14.52.29.png'; ?>"
                                class="card-img-top"
                                alt="<?php echo htmlspecialchars($item['nombre']); ?>"
                            />
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['nombre']); ?></h5>
                                <p class="card-text">
                                    <?php echo htmlspecialchars($item['descripcion']); ?>
                                </p>
                                <p class="price"><?php echo formatPrice($item['precio']); ?></p>
                                <button
                                    class="btn btn-primary add-to-cart"
                                    data-id="<?php echo $item['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($item['nombre']); ?>"
                                    data-price="<?php echo $item['precio']; ?>"
                                >
                                    Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No hay platos destacados disponibles en este momento.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- About Us -->
<section class="about-us bg-light">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <img
                    src="images/restaurant.jpg"
                    alt="Nuestro restaurante"
                    class="rounded"
                />
            </div>
            <div class="col-6">
                <h2>Sobre Nosotros</h2>
                <p>
                    En My Delights, nos dedicamos a ofrecer una experiencia
                    culinaria excepcional. Nuestro chef ejecutivo y su equipo
                    preparan cada plato con pasión y dedicación, utilizando
                    ingredientes frescos y de temporada.
                </p>
                <p>
                    Ofrecemos una variedad de opciones, desde platos a la carta
                    hasta menús de comida corriente, y servicios para eventos
                    especiales.
                </p>
                <a href="eventos.php" class="btn btn-outline-primary"
                    >Conoce nuestros servicios</a
                >
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="testimonials py-5">
    <div class="container">
        <h2 class="text-center mb-5">Lo que dicen nuestros clientes</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img
                                src="https://jumboalacarta.com.ar/wp-content/uploads/2019/06/Captura-de-pantalla-2019-06-12-a-las-14.52.29.png"
                                alt="Cliente 1"
                                class="rounded-circle me-3"
                                width="50"
                            />
                            <div>
                                <h5 class="mb-0">María González</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            "La comida es exquisita y el servicio es excepcional.
                            Definitivamente volveré pronto."
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img
                                src="https://jumboalacarta.com.ar/wp-content/uploads/2019/06/Captura-de-pantalla-2019-06-12-a-las-14.52.29.png"
                                alt="Cliente 2"
                                class="rounded-circle me-3"
                                width="50"
                            />
                            <div>
                                <h5 class="mb-0">Carlos Rodríguez</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            "Organizaron mi evento de cumpleaños y todo fue perfecto. La
                            comida estaba deliciosa y todos mis invitados quedaron
                            encantados."
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img
                                src="https://jumboalacarta.com.ar/wp-content/uploads/2019/06/Captura-de-pantalla-2019-06-12-a-las-14.52.29.png"
                                alt="Cliente 3"
                                class="rounded-circle me-3"
                                width="50"
                            />
                            <div>
                                <h5 class="mb-0">Ana Martínez</h5>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="card-text">
                            "El menú de comida corriente es variado y delicioso.
                            Perfecto para almorzar durante la semana laboral."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include 'includes/footer.php';
?> 