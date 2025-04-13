-- Create the database
CREATE DATABASE IF NOT EXISTS mydelights;
USE mydelights;
-- Create clients table
CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    sexo ENUM('masculino', 'femenino', 'otro') NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    tipo_cliente ENUM('regular', 'vip', 'premium') DEFAULT 'regular',
    puntos INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;
-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
-- Create menu items table
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
    tipo ENUM('carta', 'corriente') NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE = InnoDB;
-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    total DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) DEFAULT 0.00,
    estado ENUM(
        'pendiente',
        'en_proceso',
        'entregado',
        'cancelado'
    ) DEFAULT 'pendiente',
    fecha_orden TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega TIMESTAMP NULL,
    direccion_entrega VARCHAR(200),
    metodo_pago VARCHAR(50),
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE
    SET NULL
) ENGINE = InnoDB;
-- Create order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE
    SET NULL
) ENGINE = InnoDB;
-- Create events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
-- Create quotations table
CREATE TABLE IF NOT EXISTS quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    event_id INT,
    numero_personas INT NOT NULL,
    fecha_evento DATE NOT NULL,
    hora_evento TIME NOT NULL,
    servicios_adicionales TEXT,
    cotizacion_total DECIMAL(10, 2) NOT NULL,
    descuento DECIMAL(10, 2) DEFAULT 0.00,
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    fecha_cotizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta TIMESTAMP NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE
    SET NULL,
        FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE
    SET NULL
) ENGINE = InnoDB;
-- Insert default categories
INSERT INTO categories (nombre, descripcion)
VALUES ('Entradas', 'Platos para iniciar tu comida'),
    (
        'Platos Principales',
        'Nuestros mejores platos principales'
    ),
    ('Postres', 'Deliciosos postres para terminar'),
    (
        'Bebidas',
        'Refrescantes bebidas para acompañar tu comida'
    );
-- Insert some default menu items
INSERT INTO menu_items (
        category_id,
        nombre,
        descripcion,
        precio,
        imagen,
        tipo
    )
VALUES (
        1,
        'Ensalada César',
        'Ensalada fresca con lechuga romana, crutones, pollo a la parrilla y aderezo César',
        18000,
        'ensalada_cesar.jpg',
        'carta'
    ),
    (
        2,
        'Salmón a la Parrilla',
        'Delicioso salmón a la parrilla con salsa de limón y hierbas frescas',
        28000,
        'salmon_parrilla.jpg',
        'carta'
    ),
    (
        2,
        'Pasta Carbonara',
        'Pasta al dente con salsa carbonara cremosa, panceta y queso parmesano',
        22000,
        'pasta_carbonara.jpg',
        'carta'
    ),
    (
        2,
        'Bandeja Paisa',
        'Plato tradicional colombiano con frijoles, arroz, carne molida, chicharrón, huevo frito y aguacate',
        15000,
        'bandeja_paisa.jpg',
        'corriente'
    ),
    (
        2,
        'Ajiaco Bogotano',
        'Sopa tradicional colombiana con papas, maíz, pollo y guascas',
        14000,
        'ajiaco.jpg',
        'corriente'
    ),
    (
        3,
        'Tiramisú',
        'Postre italiano clásico con capas de bizcocho empapado en café y crema de mascarpone',
        12000,
        'tiramisu.jpg',
        'carta'
    ),
    (
        4,
        'Limonada',
        'Limonada fresca con menta',
        5000,
        'limonada.jpg',
        'carta'
    );
-- Insert default events
INSERT INTO events (nombre, descripcion, precio_base, imagen)
VALUES (
        'Cumpleaños',
        'Celebra tu cumpleaños con nosotros. Incluye decoración temática y torta',
        500000,
        'cumpleanos.jpg'
    ),
    (
        'Boda',
        'Haz tu boda con nosotros. Incluye decoración, catering y servicio de meseros',
        1500000,
        'boda.jpg'
    ),
    (
        'Evento Corporativo',
        'Organiza tu evento empresarial. Incluye coffee break y equipos audiovisuales',
        800000,
        'corporativo.jpg'
    );