    </main>

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>My Delights</h5>
                    <p>Ofreciendo la mejor experiencia gastronómica desde 2010.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Contacto</h5>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> Calle Principal #123, Ciudad</p>
                        <p><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                        <p><i class="fas fa-envelope me-2"></i> info@mydelights.com</p>
                    </address>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Síguenos</h5>
                    <div class="social-icons">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr />
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> My Delights. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

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