// Variables globales
let cart = [];
let userType = "nuevo"; // nuevo, casual, permanente

// Función para inicializar la aplicación
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el navbar
    initNavbar();
    
    // Cargar tipo de usuario desde localStorage
    loadUserType();
    
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
});

// Cargar tipo de usuario
function loadUserType() {
    const savedUserType = localStorage.getItem('myDelightsUserType');
    if (savedUserType) {
        userType = savedUserType;
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
    if (!creditoOption) return;
    
    if (userType === 'permanente') {
        creditoOption.disabled = false;
        document.getElementById('credito-container').classList.add('text-dark');
        document.getElementById('credito-container').classList.remove('text-muted');
    } else {
        creditoOption.disabled = true;
        document.getElementById('credito-container').classList.add('text-muted');
        document.getElementById('credito-container').classList.remove('text-dark');
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
            
            // Validar campos
            const cedula = document.getElementById('cedula').value;
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
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
            
            // Validar nombre (al menos 2 palabras)
            if (nombre.trim().split(' ').filter(word => word.length > 0).length < 2) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ingresa tu nombre completo (nombre y apellido)',
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
            
            // Si todo está bien, mostrar mensaje de éxito
            Swal.fire({
                title: '¡Registro exitoso!',
                text: 'Tu cuenta ha sido creada correctamente',
                icon: 'success',
                confirmButtonText: 'Iniciar sesión'
            }).then(() => {
                // Limpiar formulario
                registroForm.reset();
                
                // Simular inicio de sesión
                userType = 'nuevo';
                localStorage.setItem('myDelightsUserType', userType);
                
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
            
            // Validar campos
            const email = document.getElementById('login-email').value;
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
            
            // Simular inicio de sesión exitoso
            Swal.fire({
                title: '¡Bienvenido!',
                text: 'Has iniciado sesión correctamente',
                icon: 'success',
                confirmButtonText: 'Continuar'
            }).then(() => {
                // Limpiar formulario
                loginForm.reset();
                
                // Simular tipo de usuario (para demostración)
                userType = 'casual';
                localStorage.setItem('myDelightsUserType', userType);
                
                // Redirigir a la página principal
                window.location.href = 'index.html';
            });
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
    // Botón de editar perfil
    const editarPerfilBtn = document.getElementById('btn-editar-perfil');
    if (editarPerfilBtn) {
        editarPerfilBtn.addEventListener('click', function() {
            // Obtener datos actuales
            const nombre = document.getElementById('perfil-nombre').textContent;
            const cedula = document.getElementById('perfil-cedula').textContent;
            const email = document.getElementById('perfil-email').textContent;
            const telefono = document.getElementById('perfil-telefono').textContent;
            const direccion = document.getElementById('perfil-direccion').textContent;
            
            // Mostrar formulario de edición
            Swal.fire({
                title: 'Editar Perfil',
                html: `
                    <div class="form-group">
                        <label for="edit-nombre">Nombre:</label>
                        <input type="text" id="edit-nombre" class="swal2-input" value="${nombre}">
                    </div>
                    <div class="form-group">
                        <label for="edit-cedula">Cédula:</label>
                        <input type="text" id="edit-cedula" class="swal2-input" value="${cedula}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email:</label>
                        <input type="email" id="edit-email" class="swal2-input" value="${email}">
                    </div>
                    <div class="form-group">
                        <label for="edit-telefono">Teléfono:</label>
                        <input type="tel" id="edit-telefono" class="swal2-input" value="${telefono}">
                    </div>
                    <div class="form-group">
                        <label for="edit-direccion">Dirección:</label>
                        <input type="text" id="edit-direccion" class="swal2-input" value="${direccion}">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    return {
                        nombre: document.getElementById('edit-nombre').value,
                        email: document.getElementById('edit-email').value,
                        telefono: document.getElementById('edit-telefono').value,
                        direccion: document.getElementById('edit-direccion').value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Actualizar datos en la página
                    document.getElementById('perfil-nombre').textContent = result.value.nombre;
                    document.getElementById('perfil-email').textContent = result.value.email;
                    document.getElementById('perfil-telefono').textContent = result.value.telefono;
                    document.getElementById('perfil-direccion').textContent = result.value.direccion;
                    
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
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar mi cuenta',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Cuenta eliminada',
                        text: 'Tu cuenta ha sido eliminada correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Limpiar datos y redirigir
                        localStorage.removeItem('myDelightsUserType');
                        window.location.href = 'index.html';
                    });
                }
            });
        });
    }
} 