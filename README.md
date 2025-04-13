# Restaurante My Delights

Este proyecto es una aplicación web completa para el restaurante My Delights, desarrollada como parte del curso "Desarrollo de Aplicaciones para la Web" de la UNAD. La aplicación permite a los usuarios ver el menú, realizar pedidos, gestionar eventos y mantener un perfil personalizado.

## Características Principales

- 🍽️ Menú digital completo con imágenes
- 🛒 Sistema de carrito de compras
- 👤 Registro y autenticación de usuarios
- 🎉 Gestión de eventos y banquetes
- 💰 Sistema de descuentos y puntos
- 📱 Diseño responsive

## Estructura de la Base de Datos

La aplicación utiliza una base de datos MySQL con la siguiente estructura:

![Estructura de la Base de Datos](Documentacion/db_structure.png)

### Tablas Principales:

1. **clients**
   - Gestión de usuarios y clientes
   - Sistema de puntos y tipos de cliente
   - Información personal y de contacto

2. **menu_items**
   - Productos disponibles
   - Precios y descripciones
   - Categorización de productos

3. **orders**
   - Pedidos de clientes
   - Estado y seguimiento
   - Información de entrega

4. **events**
   - Gestión de eventos especiales
   - Reservas y cotizaciones
   - Precios base y disponibilidad

5. **categories**
   - Clasificación de productos
   - Organización del menú

## Tecnologías Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Base de Datos**: MySQL
- **Servidor**: Apache (MAMP)

## Requisitos de Instalación

1. MAMP (versión 6.x o superior)
2. PHP 7.4 o superior
3. MySQL 5.7 o superior
4. Navegador web moderno

## Configuración del Proyecto

### 1. Clonar el Repositorio

```bash
git clone https://github.com/jaider012/web-static-2.git
cd web-static-2
```

### 2. Configurar Base de Datos

1. Acceder a phpMyAdmin (http://localhost:8888/phpMyAdmin)
2. Crear una nueva base de datos llamada 'mydelights'
3. Importar el archivo `Database/mydelights.sql`

### 3. Configurar MAMP

1. Copiar el proyecto a la carpeta htdocs de MAMP
2. Configurar los puertos:
   - Apache: 8888
   - MySQL: 8889
3. Iniciar los servidores

## Estructura del Proyecto

```
Project Root/
├── includes/          # Componentes PHP
│   ├── auth.php      # Autenticación
│   ├── header.php    # Encabezado común
│   ├── footer.php    # Pie de página
│   ├── menu.php      # Navegación
│   └── cart.php      # Carrito de compras
├── Database/         # Archivos de base de datos
├── css/             # Estilos
├── js/              # JavaScript
├── Documentacion/   # Documentación
└── config/          # Configuración
```

## Documentación Adicional

Para información más detallada sobre la configuración y desarrollo, consulta:
- [Configuración del Entorno](Documentacion/configuracion_desarrollo.md)
- [Manual de Usuario](Documentacion/manual_usuario.md)

## Seguridad

- Validación de entrada de usuarios
- Prevención de SQL Injection
- Manejo seguro de sesiones
- Protección contra XSS
- Encriptación de contraseñas

## Mantenimiento

- Respaldos automáticos de la base de datos
- Logs de errores
- Monitoreo de rendimiento
- Actualizaciones de seguridad

## Contacto y Soporte

Para soporte técnico o consultas:
- 📧 Email: jaiderandres901@hotmail.com
- 📱 Teléfono: 3023902452

## Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.
