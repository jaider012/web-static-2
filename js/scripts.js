// Variables globales
let cart = [];
let userType = "nuevo"; // nuevo, casual, permanente
let currentUser = null;
let users = [];

// Función para inicializar la aplicación
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el navbar
    initNavbar();
    
    // Cargar usuarios desde localStorage
    loadUsers();
    
    // Cargar usuario actual desde localStorage
    loadCurrentUser();
    
    // Inicializar el carrito desde localStorage
    loadCart();
    
    // Inicializar validaciones de formularios
    initFormValidations();
    
    // Inicializar funcionalidad del carrito
    initCartFunctionality();
    
    // Inicializar funcionalidad de perfil
    initProfileFunctionality();
    
    // Actualizar mensaje de tipo de cliente
    updateClientTypeMessage();
    
    // Habilitar opción de crédito para clientes permanentes
    updateCreditOption();
    
    // Inicializar barra de usuarios de prueba
    initTestUserBar();
});

// Cargar usuarios desde localStorage
function loadUsers() {
    const savedUsers = localStorage.getItem('myDelightsUsers');
    if (savedUsers) {
        users = JSON.parse(savedUsers);
    } else {
        // Crear algunos usuarios de prueba si no existen
        users = [
            {
                cedula: '1234567890',
                nombre: 'Cliente Nuevo',
                sexo: 'masculino',
                fechaNacimiento: '1990-01-01',
                direccion: 'Calle 123',
                telefono: '1234567890',
                email: 'nuevo@example.com',
                password: '123456',
                tipo: 'nuevo',
                fechaRegistro: new Date().toISOString()
            },
            {
                cedula: '0987654321',
                nombre: 'Cliente Casual',
                sexo: 'femenino',
                fechaNacimiento: '1985-05-15',
                direccion: 'Avenida 456',
                telefono: '0987654321',
                email: 'casual@example.com',
                password: '123456',
                tipo: 'casual',
                fechaRegistro: new Date(Date.now() - 90 * 24 * 60 * 60 * 1000).toISOString() // 90 días atrás
            },
            {
                cedula: '5678901234',
                nombre: 'Cliente Permanente',
                sexo: 'otro',
                fechaNacimiento: '1980-10-20',
                direccion: 'Plaza 789',
                telefono: '5678901234',
                email: 'permanente@example.com',
                password: '123456',
                tipo: 'permanente',
                fechaRegistro: new Date(Date.now() - 180 * 24 * 60 * 60 * 1000).toISOString() // 180 días atrás
            }
        ];
        saveUsers();
    }
}

// Guardar usuarios en localStorage
function saveUsers() {
    localStorage.setItem('myDelightsUsers', JSON.stringify(users));
}

// Cargar usuario actual
function loadCurrentUser() {
    const savedCurrentUser = localStorage.getItem('myDelightsCurrentUser');
    if (savedCurrentUser) {
        currentUser = JSON.parse(savedCurrentUser);
        userType = currentUser.tipo;
        
        // Actualizar elementos de la interfaz según el usuario logueado
        updateUIForLoggedUser();
    }
}

// Guardar usuario actual
function saveCurrentUser() {
    if (currentUser) {
        localStorage.setItem('myDelightsCurrentUser', JSON.stringify(currentUser));
    } else {
        localStorage.removeItem('myDelightsCurrentUser');
    }
}

// Actualizar interfaz para usuario logueado
function updateUIForLoggedUser() {
    const loginLinks = document.querySelectorAll('.nav-link[href="registro.html"]');
    const profileLinks = document.querySelectorAll('.nav-link[href="perfil.html"]');
    
    if (currentUser) {
        // Usuario logueado
        loginLinks.forEach(link => {
            link.textContent = 'Cerrar Sesión';
            link.setAttribute('href', '#');
            link.classList.add('logout-link');
        });
        
        // Agregar evento de logout
        document.querySelectorAll('.logout-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                logout();
            });
        });
        
        // Mostrar enlace de perfil
        profileLinks.forEach(link => {
            link.style.display = 'block';
        });
    } else {
        // Usuario no logueado
        loginLinks.forEach(link => {
            link.textContent = 'Registro/Login';
            link.setAttribute('href', 'registro.html');
            link.classList.remove('logout-link');
        });
        
        // Ocultar enlace de perfil
        profileLinks.forEach(link => {
            link.style.display = 'none';
        });
    }
}

// Función de logout
function logout() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: '¿Estás seguro de que deseas cerrar sesión?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            currentUser = null;
            userType = 'nuevo';
            saveCurrentUser();
            
            Swal.fire({
                title: 'Sesión cerrada',
                text: 'Has cerrado sesión correctamente',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'index.html';
            });
        }
    });
}

// Inicializar barra de usuarios de prueba
function initTestUserBar() {
    // Solo mostrar en la página de registro
    if (!window.location.pathname.includes('registro.html')) return;
    
    // Crear la barra de usuarios de prueba
    const testUserBar = document.createElement('div');
    testUserBar.className = 'test-user-bar alert alert-info mt-3';
    testUserBar.innerHTML = `
        <h5>Usuarios de prueba</h5>
        <p>Puedes usar estos usuarios para probar la aplicación:</p>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-sm btn-outline-primary test-user-btn" data-email="nuevo@example.com" data-password="123456">Cliente Nuevo</button>
            <button class="btn btn-sm btn-outline-primary test-user-btn" data-email="casual@example.com" data-password="123456">Cliente Casual</button>
            <button class="btn btn-sm btn-outline-primary test-user-btn" data-email="permanente@example.com" data-password="123456">Cliente Permanente</button>
        </div>
    `;
    
    // Insertar la barra después del formulario de login
    const loginSection = document.querySelector('.login');
    if (loginSection) {
        loginSection.appendChild(testUserBar);
        
        // Agregar eventos a los botones
        document.querySelectorAll('.test-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.getAttribute('data-email');
                const password = this.getAttribute('data-password');
                
                // Rellenar el formulario de login
                document.getElementById('login-email').value = email;
                document.getElementById('login-password').value = password;
                
                // Opcional: hacer login automáticamente
                Swal.fire({
                    title: 'Usuario de prueba',
                    text: `¿Deseas iniciar sesión como ${email}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, iniciar sesión',
                    cancelButtonText: 'No, solo rellenar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Buscar el usuario
                        const user = users.find(u => u.email === email && u.password === password);
                        if (user) {
                            loginUser(user);
                        }
                    }
                });
            });
        });
    }
}

// Función para iniciar sesión
function loginUser(user) {
    currentUser = user;
    userType = user.tipo;
    saveCurrentUser();
    
    Swal.fire({
        title: '¡Bienvenido!',
        text: `Has iniciado sesión como ${user.nombre}`,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'index.html';
    });
}

// Cargar tipo de usuario
function loadUserType() {
    if (currentUser) {
        userType = currentUser.tipo;
    } else {
        const savedUserType = localStorage.getItem('myDelightsUserType');
        if (savedUserType) {
            userType = savedUserType;
        }
    }
}

// Actualizar mensaje según tipo de cliente
function updateClientTypeMessage() {
    const clienteInfoElement = document.getElementById('cliente-tipo-mensaje');
    if (!clienteInfoElement) return;
    
    switch (userType) {
        case 'nuevo':
            clienteInfoElement.textContent = 'Eres un cliente nuevo. Compras mayores a $250,000 obtienen un 2% de descuento.';
            break;
        case 'casual':
            clienteInfoElement.textContent = 'Eres un cliente casual. Obtienes un 2% de descuento en todas tus compras y 6% en compras mayores a $200,000.';
            break;
        case 'permanente':
            clienteInfoElement.textContent = 'Eres un cliente permanente. Obtienes un 4% de descuento en todas tus compras y 10% en compras mayores a $150,000.';
            break;
    }
}

// Habilitar opción de crédito para clientes permanentes
function updateCreditOption() {
    const creditoOption = document.getElementById('credito');
    const creditoContainer = document.getElementById('credito-container');
    if (!creditoOption || !creditoContainer) return;
    
    if (userType === 'permanente') {
        creditoOption.disabled = false;
        creditoContainer.classList.remove('text-muted');
    } else {
        creditoOption.disabled = true;
        creditoContainer.classList.add('text-muted');
    }
}

// Funciones del navbar
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Set active link based on current page
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// Funciones del carrito
function initCartFunctionality() {
    // Agregar event listeners a los botones de "Agregar al carrito"
    const addButtons = document.querySelectorAll('.btn-agregar, .add-to-cart');
    if (addButtons.length > 0) {
        addButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = parseInt(this.getAttribute('data-price'));
                
                addToCart(id, name, price);
                
                // Mostrar alerta de éxito
                Swal.fire({
                    title: '¡Producto agregado!',
                    text: `${name} ha sido agregado al carrito`,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            });
        });
    }
    
    // Agregar event listeners a los botones de cantidad en el carrito
    const quantityButtons = document.querySelectorAll('.btn-cantidad');
    if (quantityButtons.length > 0) {
        quantityButtons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.textContent;
                const row = this.closest('tr');
                const productName = row.querySelector('td:first-child').textContent;
                
                if (action === '+') {
                    updateCartItemQuantity(productName, 1);
                } else if (action === '-') {
                    updateCartItemQuantity(productName, -1);
                }
                
                updateCartDisplay();
            });
        });
    }
    
    // Agregar event listeners a los botones de eliminar en el carrito
    const removeButtons = document.querySelectorAll('.btn-eliminar');
    if (removeButtons.length > 0) {
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const productName = row.querySelector('td:first-child').textContent;
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas eliminar ${productName} del carrito?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeFromCart(productName);
                        updateCartDisplay();
                        
                        Swal.fire({
                            title: 'Eliminado',
                            text: 'El producto ha sido eliminado del carrito',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            });
        });
    }
    
    // Agregar event listener al botón de finalizar compra
    const checkoutButton = document.getElementById('btn-finalizar');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            if (cart.length === 0) {
                Swal.fire({
                    title: 'Carrito vacío',
                    text: 'Agrega productos al carrito antes de finalizar la compra',
                    icon: 'info'
                });
                return;
            }
            
            // Verificar método de pago
            const metodoPago = document.querySelector('input[name="pago"]:checked').value;
            const entregaOption = document.querySelector('input[name="entrega"]:checked').value;
            
            // Mensaje personalizado según método de pago
            let mensajeAdicional = '';
            if (metodoPago === 'efectivo') {
                mensajeAdicional = 'Prepara el efectivo para el momento de la entrega.';
            } else if (metodoPago === 'tarjeta') {
                mensajeAdicional = 'Te redirigiremos a la pasarela de pago.';
            } else if (metodoPago === 'credito') {
                mensajeAdicional = 'El monto será cargado a tu cuenta de crédito.';
            }
            
            // Mensaje adicional para entrega
            let entregaMensaje = '';
            if (entregaOption === 'domicilio') {
                entregaMensaje = 'Tu pedido será entregado a domicilio en aproximadamente 45 minutos.';
            } else {
                entregaMensaje = 'Tu pedido estará listo para recoger en 30 minutos.';
            }
            
            Swal.fire({
                title: '¡Compra finalizada!',
                html: `Gracias por tu compra.<br>${entregaMensaje}<br>${mensajeAdicional}`,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Limpiar carrito y redirigir a la página principal
                cart = [];
                saveCart();
                updateCartCount();
                window.location.href = 'index.html';
            });
        });
    }
    
    // Agregar event listeners a las opciones de entrega y pago
    const deliveryOptions = document.querySelectorAll('input[name="entrega"], input[name="pago"]');
    if (deliveryOptions.length > 0) {
        deliveryOptions.forEach(option => {
            option.addEventListener('change', function() {
                updateCartSummary();
            });
        });
    }
    
    // Inicializar el resumen del carrito si estamos en la página del carrito
    if (window.location.pathname.includes('carrito.html')) {
        updateCartDisplay();
    }
}

// Funciones para manejar el carrito
function addToCart(id, name, price) {
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1
        });
    }
    
    saveCart();
    updateCartCount();
    animateCartIcon();
}

function removeFromCart(name) {
    cart = cart.filter(item => item.name !== name);
    saveCart();
    updateCartCount();
}

function updateCartItemQuantity(name, change) {
    const item = cart.find(item => item.name === name);
    
    if (item) {
        item.quantity += change;
        
        if (item.quantity <= 0) {
            removeFromCart(name);
        } else {
            saveCart();
        }
    }
    
    updateCartCount();
}

function saveCart() {
    localStorage.setItem('myDelightsCart', JSON.stringify(cart));
}

function loadCart() {
    const savedCart = localStorage.getItem('myDelightsCart');
    
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCartCount();
    }
}

function updateCartCount() {
    const cartCountElements = document.querySelectorAll('#cart-count');
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    
    cartCountElements.forEach(element => {
        element.textContent = count;
    });
}

function animateCartIcon() {
    const cartIcons = document.querySelectorAll('.fa-shopping-cart');
    
    cartIcons.forEach(icon => {
        icon.classList.add('animate__animated', 'animate__rubberBand');
        
        setTimeout(() => {
            icon.classList.remove('animate__animated', 'animate__rubberBand');
        }, 1000);
    });
}

function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('carrito-items');
    
    if (!cartItemsContainer) return;
    
    // Limpiar el contenedor
    cartItemsContainer.innerHTML = '';
    
    if (cart.length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `
            <td colspan="5" class="text-center">El carrito está vacío</td>
        `;
        cartItemsContainer.appendChild(emptyRow);
        
        // Actualizar el resumen del carrito
        updateCartSummary();
        
        return;
    }
    
    // Agregar cada producto al carrito
    cart.forEach(item => {
        const row = document.createElement('tr');
        const subtotal = item.price * item.quantity;
        
        row.innerHTML = `
            <td>${item.name}</td>
            <td>$${item.price.toLocaleString()}</td>
            <td>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary btn-cantidad me-2">-</button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary btn-cantidad ms-2">+</button>
                </div>
            </td>
            <td>$${subtotal.toLocaleString()}</td>
            <td><button class="btn btn-sm btn-danger btn-eliminar">Eliminar</button></td>
        `;
        
        cartItemsContainer.appendChild(row);
    });
    
    // Reinicializar los event listeners para los nuevos botones
    const quantityButtons = document.querySelectorAll('.btn-cantidad');
    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.textContent;
            const row = this.closest('tr');
            const productName = row.querySelector('td:first-child').textContent;
            
            if (action === '+') {
                updateCartItemQuantity(productName, 1);
            } else if (action === '-') {
                updateCartItemQuantity(productName, -1);
            }
            
            updateCartDisplay();
        });
    });
    
    const removeButtons = document.querySelectorAll('.btn-eliminar');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const productName = row.querySelector('td:first-child').textContent;
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Deseas eliminar ${productName} del carrito?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeFromCart(productName);
                    updateCartDisplay();
                    
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El producto ha sido eliminado del carrito',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    });
    
    // Actualizar el resumen del carrito
    updateCartSummary();
}

function updateCartSummary() {
    const subtotalElement = document.getElementById('subtotal');
    const descuentoElement = document.getElementById('descuento');
    const descuentoPorcentajeElement = document.getElementById('descuento-porcentaje');
    const recargoElement = document.getElementById('recargo');
    const totalElement = document.getElementById('total');
    
    if (!subtotalElement || !descuentoElement || !recargoElement || !totalElement) return;
    
    // Calcular subtotal
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    
    // Calcular descuento según tipo de cliente
    let descuentoPorcentaje = 0;
    
    switch (userType) {
        case 'nuevo':
            descuentoPorcentaje = subtotal >= 250000 ? 0.02 : 0;
            break;
        case 'casual':
            descuentoPorcentaje = subtotal >= 200000 ? 0.06 : 0.02;
            break;
        case 'permanente':
            descuentoPorcentaje = subtotal >= 150000 ? 0.10 : 0.04;
            break;
    }
    
    const descuento = subtotal * descuentoPorcentaje;
    
    // Calcular recargo por domicilio
    const domicilioChecked = document.getElementById('domicilio') && document.getElementById('domicilio').checked;
    const recargo = domicilioChecked ? subtotal * 0.02 : 0;
    
    // Calcular total
    const total = subtotal - descuento + recargo;
    
    // Actualizar elementos
    subtotalElement.textContent = `$${subtotal.toLocaleString()}`;
    descuentoElement.textContent = `$${descuento.toLocaleString()}`;
    descuentoPorcentajeElement.textContent = `${(descuentoPorcentaje * 100).toFixed(0)}%`;
    recargoElement.textContent = `$${recargo.toLocaleString()}`;
    totalElement.textContent = `$${total.toLocaleString()}`;
}

// Funciones para validación de formularios
function initFormValidations() {
    // Validación del formulario de registro
    const registroForm = document.getElementById('form-registro');
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores de los campos
            const cedula = document.getElementById('cedula').value.trim();
            const nombre = document.getElementById('nombre').value.trim();
            const sexo = document.getElementById('sexo').value;
            const fechaNacimiento = document.getElementById('fecha-nacimiento').value;
            const direccion = document.getElementById('direccion').value.trim();
            const telefono = document.getElementById('telefono').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validar cédula (solo números)
            if (!/^\d+$/.test(cedula)) {
                Swal.fire({
                    title: 'Error',
                    text: 'La cédula debe contener solo números',
                    icon: 'error'
                });
                return;
            }
            
            // Verificar si la cédula ya existe
            if (users.some(user => user.cedula === cedula)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Esta cédula ya está registrada',
                    icon: 'error'
                });
                return;
            }
            
            // Validar nombre (al menos 2 palabras)
            if (nombre.split(' ').filter(word => word.length > 0).length < 2) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa tu nombre completo (nombre y apellido)',
                    icon: 'error'
                });
                return;
            }
            
            // Validar fecha de nacimiento
            if (!fechaNacimiento) {
                Swal.fire({
                    title: 'Error',
                    text: 'Selecciona tu fecha de nacimiento',
                    icon: 'error'
                });
                return;
            }
            
            // Validar que sea mayor de edad
            const birthDate = new Date(fechaNacimiento);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 18) {
                Swal.fire({
                    title: 'Error',
                    text: 'Debes ser mayor de edad para registrarte',
                    icon: 'error'
                });
                return;
            }
            
            // Validar dirección
            if (direccion.length < 5) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa una dirección válida',
                    icon: 'error'
                });
                return;
            }
            
            // Validar teléfono
            if (!/^\d{7,10}$/.test(telefono.replace(/\D/g, ''))) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa un número de teléfono válido (7-10 dígitos)',
                    icon: 'error'
                });
                return;
            }
            
            // Validar email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa un correo electrónico válido',
                    icon: 'error'
                });
                return;
            }
            
            // Verificar si el email ya existe
            if (users.some(user => user.email === email)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Este correo electrónico ya está registrado',
                    icon: 'error'
                });
                return;
            }
            
            // Validar contraseña (al menos 6 caracteres)
            if (password.length < 6) {
                Swal.fire({
                    title: 'Error',
                    text: 'La contraseña debe tener al menos 6 caracteres',
                    icon: 'error'
                });
                return;
            }
            
            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                Swal.fire({
                    title: 'Error',
                    text: 'Las contraseñas no coinciden',
                    icon: 'error'
                });
                return;
            }
            
            // Crear nuevo usuario
            const newUser = {
                cedula,
                nombre,
                sexo,
                fechaNacimiento,
                direccion,
                telefono,
                email,
                password,
                tipo: 'nuevo', // Por defecto, todos los usuarios nuevos son de tipo "nuevo"
                fechaRegistro: new Date().toISOString()
            };
            
            // Agregar usuario a la lista
            users.push(newUser);
            saveUsers();
            
            // Iniciar sesión con el nuevo usuario
            currentUser = newUser;
            userType = newUser.tipo;
            saveCurrentUser();
            
            // Mostrar mensaje de éxito
            Swal.fire({
                title: '¡Registro exitoso!',
                text: 'Tu cuenta ha sido creada correctamente',
                icon: 'success',
                confirmButtonText: 'Continuar'
            }).then(() => {
                // Redirigir a la página principal
                window.location.href = 'index.html';
            });
        });
    }
    
    // Validación del formulario de login
    const loginForm = document.getElementById('form-login');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores de los campos
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;
            
            // Validar email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa un correo electrónico válido',
                    icon: 'error'
                });
                return;
            }
            
            // Validar contraseña (no vacía)
            if (password.trim() === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa tu contraseña',
                    icon: 'error'
                });
                return;
            }
            
            // Buscar usuario
            const user = users.find(u => u.email === email && u.password === password);
            
            if (user) {
                // Usuario encontrado, iniciar sesión
                loginUser(user);
            } else {
                // Usuario no encontrado o contraseña incorrecta
                Swal.fire({
                    title: 'Error',
                    text: 'Correo electrónico o contraseña incorrectos',
                    icon: 'error'
                });
            }
        });
    }
    
    // Validación del formulario de cotización
    const cotizacionForm = document.getElementById('form-cotizacion');
    if (cotizacionForm) {
        cotizacionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar campos
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const telefono = document.getElementById('telefono').value;
            const invitados = document.getElementById('invitados').value;
            
            // Validar nombre
            if (nombre.trim() === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa tu nombre',
                    icon: 'error'
                });
                return;
            }
            
            // Validar email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa un correo electrónico válido',
                    icon: 'error'
                });
                return;
            }
            
            // Validar teléfono
            if (!/^\d{7,10}$/.test(telefono.replace(/\D/g, ''))) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa un número de teléfono válido',
                    icon: 'error'
                });
                return;
            }
            
            // Validar número de invitados
            if (invitados < 10) {
                Swal.fire({
                    title: 'Error',
                    text: 'El número mínimo de invitados es 10',
                    icon: 'error'
                });
                return;
            }
            
            // Si todo está bien, mostrar mensaje de éxito
            Swal.fire({
                title: '¡Solicitud enviada!',
                text: 'Te contactaremos pronto con tu cotización personalizada',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                // Limpiar formulario
                cotizacionForm.reset();
            });
        });
    }
}

// Funciones para el perfil de usuario
function initProfileFunctionality() {
    // Cargar datos del perfil si estamos en la página de perfil
    if (window.location.pathname.includes('perfil.html')) {
        loadProfileData();
    }
    
    // Botón de editar perfil
    const editarPerfilBtn = document.getElementById('btn-editar-perfil');
    if (editarPerfilBtn) {
        editarPerfilBtn.addEventListener('click', function() {
            if (!currentUser) {
                Swal.fire({
                    title: 'Error',
                    text: 'Debes iniciar sesión para editar tu perfil',
                    icon: 'error'
                });
                return;
            }
            
            // Mostrar formulario de edición
            Swal.fire({
                title: 'Editar Perfil',
                html: `
                    <div class="form-group mb-3">
                        <label for="edit-nombre" class="form-label">Nombre:</label>
                        <input type="text" id="edit-nombre" class="form-control" value="${currentUser.nombre}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-cedula" class="form-label">Cédula:</label>
                        <input type="text" id="edit-cedula" class="form-control" value="${currentUser.cedula}" disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-email" class="form-label">Email:</label>
                        <input type="email" id="edit-email" class="form-control" value="${currentUser.email}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-telefono" class="form-label">Teléfono:</label>
                        <input type="tel" id="edit-telefono" class="form-control" value="${currentUser.telefono}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit-direccion" class="form-label">Dirección:</label>
                        <input type="text" id="edit-direccion" class="form-control" value="${currentUser.direccion}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    // Validar campos
                    const nombre = document.getElementById('edit-nombre').value.trim();
                    const email = document.getElementById('edit-email').value.trim();
                    const telefono = document.getElementById('edit-telefono').value.trim();
                    const direccion = document.getElementById('edit-direccion').value.trim();
                    
                    // Validar nombre
                    if (nombre.split(' ').filter(word => word.length > 0).length < 2) {
                        Swal.showValidationMessage('Ingresa tu nombre completo (nombre y apellido)');
                        return false;
                    }
                    
                    // Validar email
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        Swal.showValidationMessage('Ingresa un correo electrónico válido');
                        return false;
                    }
                    
                    // Validar teléfono
                    if (!/^\d{7,10}$/.test(telefono.replace(/\D/g, ''))) {
                        Swal.showValidationMessage('Ingresa un número de teléfono válido (7-10 dígitos)');
                        return false;
                    }
                    
                    // Validar dirección
                    if (direccion.length < 5) {
                        Swal.showValidationMessage('Ingresa una dirección válida');
                        return false;
                    }
                    
                    return {
                        nombre,
                        email,
                        telefono,
                        direccion
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Actualizar datos del usuario
                    currentUser.nombre = result.value.nombre;
                    currentUser.email = result.value.email;
                    currentUser.telefono = result.value.telefono;
                    currentUser.direccion = result.value.direccion;
                    
                    // Actualizar en la lista de usuarios
                    const userIndex = users.findIndex(u => u.cedula === currentUser.cedula);
                    if (userIndex !== -1) {
                        users[userIndex] = currentUser;
                        saveUsers();
                    }
                    
                    // Guardar usuario actual
                    saveCurrentUser();
                    
                    // Actualizar datos en la página
                    loadProfileData();
                    
                    Swal.fire({
                        title: '¡Perfil actualizado!',
                        text: 'Tus datos han sido actualizados correctamente',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });
    }
    
    // Botón de eliminar cuenta
    const eliminarCuentaBtn = document.getElementById('btn-eliminar-cuenta');
    if (eliminarCuentaBtn) {
        eliminarCuentaBtn.addEventListener('click', function() {
            if (!currentUser) {
                Swal.fire({
                    title: 'Error',
                    text: 'Debes iniciar sesión para eliminar tu cuenta',
                    icon: 'error'
                });
                return;
            }
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará permanentemente tu cuenta y todos tus datos',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar mi cuenta',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Eliminar usuario de la lista
                    users = users.filter(u => u.cedula !== currentUser.cedula);
                    saveUsers();
                    
                    // Cerrar sesión
                    currentUser = null;
                    userType = 'nuevo';
                    saveCurrentUser();
                    
                    Swal.fire({
                        title: 'Cuenta eliminada',
                        text: 'Tu cuenta ha sido eliminada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Redirigir a la página principal
                        window.location.href = 'index.html';
                    });
                }
            });
        });
    }
}

// Cargar datos del perfil
function loadProfileData() {
    if (!currentUser) return;
    
    // Elementos del perfil
    const nombreElement = document.getElementById('perfil-nombre');
    const cedulaElement = document.getElementById('perfil-cedula');
    const emailElement = document.getElementById('perfil-email');
    const telefonoElement = document.getElementById('perfil-telefono');
    const direccionElement = document.getElementById('perfil-direccion');
    const tipoClienteElement = document.getElementById('perfil-tipo-cliente');
    
    // Actualizar elementos si existen
    if (nombreElement) nombreElement.textContent = currentUser.nombre;
    if (cedulaElement) cedulaElement.textContent = currentUser.cedula;
    if (emailElement) emailElement.textContent = currentUser.email;
    if (telefonoElement) telefonoElement.textContent = currentUser.telefono;
    if (direccionElement) direccionElement.textContent = currentUser.direccion;
    
    if (tipoClienteElement) {
        let tipoTexto = '';
        switch (currentUser.tipo) {
            case 'nuevo':
                tipoTexto = 'Cliente Nuevo';
                break;
            case 'casual':
                tipoTexto = 'Cliente Casual';
                break;
            case 'permanente':
                tipoTexto = 'Cliente Permanente';
                break;
        }
        tipoClienteElement.textContent = tipoTexto;
    }
} 