# Estructura de la Base de Datos

## Diagrama Entidad-Relación

El diagrama muestra la estructura completa de la base de datos del sistema, incluyendo todas las tablas, sus relaciones y campos.

## Descripción de Tablas

### 1. clients
- **id**: Identificador único del cliente (INT, PK)
- **cedula**: Número de identificación (VARCHAR(20))
- **nombre**: Nombre completo (VARCHAR(100))
- **sexo**: Género (ENUM: masculino, femenino, otro)
- **fecha_nacimiento**: Fecha de nacimiento (DATE)
- **direccion**: Dirección de residencia (VARCHAR(200))
- **telefono**: Número de contacto (VARCHAR(20))
- **email**: Correo electrónico (VARCHAR(100))
- **password**: Contraseña encriptada (VARCHAR(255))
- **tipo_cliente**: Tipo de membresía (ENUM: regular, vip, premium)
- **puntos**: Sistema de puntos acumulados (INT)
- **created_at**: Fecha de registro (TIMESTAMP)
- **updated_at**: Última actualización (TIMESTAMP)

### 2. categories
- **id**: Identificador único de categoría (INT, PK)
- **nombre**: Nombre de la categoría (VARCHAR(50))
- **descripcion**: Descripción detallada (TEXT)
- **created_at**: Fecha de creación (TIMESTAMP)

### 3. menu_items
- **id**: Identificador único del ítem (INT, PK)
- **category_id**: Categoría asociada (INT, FK)
- **nombre**: Nombre del producto (VARCHAR(100))
- **descripcion**: Descripción del producto (TEXT)
- **precio**: Precio unitario (DECIMAL(10,2))
- **imagen**: URL de la imagen (VARCHAR(255))
- **tipo**: Tipo de producto (ENUM: carta, corriente)
- **disponible**: Estado de disponibilidad (BOOLEAN)
- **created_at**: Fecha de creación (TIMESTAMP)
- **updated_at**: Última actualización (TIMESTAMP)

### 4. orders
- **id**: Identificador único del pedido (INT, PK)
- **client_id**: Cliente asociado (INT, FK)
- **total**: Monto total (DECIMAL(10,2))
- **descuento**: Descuento aplicado (DECIMAL(10,2))
- **estado**: Estado del pedido (ENUM: pendiente, en_proceso, entregado, cancelado)
- **fecha_orden**: Fecha del pedido (TIMESTAMP)
- **fecha_entrega**: Fecha de entrega (TIMESTAMP)
- **direccion_entrega**: Dirección de entrega (VARCHAR(200))
- **metodo_pago**: Método de pago utilizado (VARCHAR(50))

### 5. events
- **id**: Identificador único del evento (INT, PK)
- **nombre**: Nombre del evento (VARCHAR(100))
- **descripcion**: Descripción del evento (TEXT)
- **precio_base**: Precio base (DECIMAL(10,2))
- **imagen**: URL de la imagen (VARCHAR(255))
- **disponible**: Disponibilidad (BOOLEAN)
- **created_at**: Fecha de creación (TIMESTAMP)

### 6. quotations
- **id**: Identificador único de la cotización (INT, PK)
- **client_id**: Cliente asociado (INT, FK)
- **event_id**: Evento asociado (INT, FK)
- **numero_personas**: Número de asistentes (INT)
- **fecha_evento**: Fecha del evento (DATE)
- **hora_evento**: Hora del evento (TIME)
- **servicios_adicionales**: Servicios extras (TEXT)
- **cotizacion_total**: Monto total (DECIMAL(10,2))
- **descuento**: Descuento aplicado (DECIMAL(10,2))
- **estado**: Estado de la cotización (ENUM: pendiente, aprobada, rechazada)
- **fecha_cotizacion**: Fecha de solicitud (TIMESTAMP)
- **fecha_respuesta**: Fecha de respuesta (TIMESTAMP)

### 7. order_items
- **id**: Identificador único (INT, PK)
- **order_id**: Pedido asociado (INT, FK)
- **menu_item_id**: Ítem del menú (INT, FK)
- **cantidad**: Cantidad solicitada (INT)
- **precio_unitario**: Precio por unidad (DECIMAL(10,2))
- **subtotal**: Subtotal calculado (DECIMAL(10,2)) 