    </main>

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h5>My Delights</h5>
                    <p>Ofreciendo la mejor experiencia gastronómica desde 2010.</p>
                    <p>Especialistas en comida gourmet, eventos y catering de alta calidad.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Enlaces rápidos</h5>
                    <ul class="footer-links">
                        <li><a href="index.php" title="Página de inicio">Inicio</a></li>
                        <li><a href="menu-carta.php" title="Nuestro menú a la carta">Menú a la Carta</a></li>
                        <li><a href="menu-corriente.php" title="Comidas corrientes del día">Comida Corriente</a></li>
                        <li><a href="eventos.php" title="Servicios para eventos">Eventos</a></li>
                        <li><a href="registro.php" title="Regístrate o inicia sesión">Registro/Login</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Contacto</h5>
                    <address itemscope itemtype="https://schema.org/Restaurant">
                        <span itemprop="name" class="d-none">My Delights</span>
                        <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                            <p><i class="fas fa-map-marker-alt me-2"></i> <span itemprop="streetAddress">Calle Principal #123</span>, <span itemprop="addressLocality">Ciudad</span></p>
                            <meta itemprop="addressRegion" content="Región">
                            <meta itemprop="postalCode" content="12345">
                            <meta itemprop="addressCountry" content="CO">
                        </div>
                        <p><i class="fas fa-phone me-2"></i> <span itemprop="telephone">(123) 456-7890</span></p>
                        <p><i class="fas fa-envelope me-2"></i> <a href="mailto:info@mydelights.com" itemprop="email" class="text-white">info@mydelights.com</a></p>
                    </address>
                </div>
                <div class="col-md-3 mb-3">
                    <h5>Síguenos</h5>
                    <div class="social-icons">
                        <a href="https://facebook.com/mydelights" rel="noopener" aria-label="Facebook" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com/mydelights" rel="noopener" aria-label="Instagram" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="https://twitter.com/mydelights" rel="noopener" aria-label="Twitter" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="https://linkedin.com/company/mydelights" rel="noopener" aria-label="LinkedIn" class="text-white me-2"><i class="fab fa-linkedin"></i></a>
                    </div>
                    <div class="mt-3">
                        <h6>Horario de atención:</h6>
                        <p itemprop="openingHours">Lun-Vie: 12:00 - 22:00</p>
                        <p itemprop="openingHours">Sáb-Dom: 12:00 - 23:00</p>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?php echo date('Y'); ?> My Delights. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="footer-links-inline">
                        <li><a href="politicas-privacidad.php" title="Políticas de privacidad">Privacidad</a></li>
                        <li><a href="terminos-condiciones.php" title="Términos y condiciones">Términos</a></li>
                        <li><a href="sitemap.xml" title="Mapa del sitio">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Botón de WhatsApp flotante -->
    <a href="https://wa.me/573001234567" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Contactar por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="js/scripts.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle navbar on mobile
        const navbarToggler = document.getElementById('navbar-toggler');
        const navbarCollapse = document.getElementById('navbarNav');
        
        if (navbarToggler && navbarCollapse) {
            navbarToggler.addEventListener('click', function() {
                navbarCollapse.classList.toggle('show');
            });
        }
        
        // Add any custom JavaScript functionality here
    });
    </script>
</body>
</html> 