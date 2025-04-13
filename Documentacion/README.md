# My Delights - Sistema de Restaurante

Este proyecto es parte de la Etapa 3 del curso "Desarrollo de Aplicaciones para la Web" de la UNAD. Consiste en una aplicación web dinámica para un restaurante, implementada con PHP y MySQL.

## Requisitos

- AppServ 9.3.0 (que incluye Apache, PHP, MySQL y phpMyAdmin)
- Navegador web moderno

## Instalación

1. **Instalar AppServ 9.3.0**:
   - Descargar AppServ desde [su sitio oficial](https://www.appserv.org/en/)
   - Seguir las instrucciones de instalación
   - Asegurarse de que el servicio Apache y MySQL estén en ejecución

2. **Importar la base de datos**:
   - Abrir phpMyAdmin (normalmente en http://localhost/phpmyadmin/)
   - Crear una nueva base de datos llamada `mydelights`
   - Importar el archivo `Database/mydelights.sql`

3. **Configurar el proyecto**:
   - Copiar todos los archivos del proyecto a la carpeta `www` de AppServ (usualmente en `C:\AppServ\www\mydelights`)
   - Si es necesario, ajustar la configuración de conexión a la base de datos en `config/database.php`

4. **Acceder al sitio**:
   - Abrir un navegador y navegar a http://localhost/mydelights/

## Estructura del Proyecto

- **config/**: Archivos de configuración
  - `database.php`: Configuración de conexión a la base de datos
  
- **includes/**: Componentes reutilizables
  - `auth.php`: Funciones de autenticación y gestión de usuarios
  - `cart.php`: Funciones para el carrito de compras
  - `menu.php`: Funciones para gestionar el menú y categorías
  - `header.php`: Encabezado común para todas las páginas
  - `footer.php`: Pie de página común para todas las páginas
  
- **Documentacion/**: Documentación del proyecto
  - `README.md`: Este archivo
  - `Anexo 4.docx`: Documento de requisitos y especificaciones
  
- **Database/**: Archivos relacionados con la base de datos
  - `mydelights.sql`: Script SQL para crear e inicializar la base de datos
  
- **js/**: Archivos JavaScript
  - `scripts.js`: Funciones JavaScript del lado del cliente
  
- **css/**: Hojas de estilo
  - `styles.css`: Estilos CSS para el sitio
  
- **images/**: Imágenes del sitio

## Funcionalidades Principales

1. **Gestión de Clientes**:
   - Registro e inicio de sesión
   - Perfil de usuario con datos personales
   - Sistema de niveles de cliente (Regular, VIP, Premium)
   - Acumulación de puntos por compras

2. **Menú y Productos**:
   - Visualización de menú a la carta
   - Visualización de menú de comida corriente
   - Categorización de productos
   - Detalles de cada plato

3. **Carrito de Compras**:
   - Agregar productos al carrito
   - Actualizar cantidades
   - Eliminar productos
   - Proceso de checkout

4. **Cotizaciones para Eventos**:
   - Solicitud de cotizaciones
   - Cálculo automático basado en tipo de cliente
   - Historial de cotizaciones

5. **Descuentos**:
   - Sistema de descuentos basado en nivel de cliente
   - Regular: 5%
   - VIP: 10%
   - Premium: 15%

## Usuarios de Prueba

Para probar el sistema, puede usar los siguientes usuarios predefinidos:

| Email | Contraseña | Tipo de Cliente |
|-------|------------|-----------------|
| cliente@mydelights.com | 123456 | Regular |
| vip@mydelights.com | 123456 | VIP |
| premium@mydelights.com | 123456 | Premium |
| admin@mydelights.com | admin123 | Admin |

## Notas Adicionales

- Para crear nuevos productos o categorías, utilice phpMyAdmin para insertar registros directamente en la base de datos.
- Las imágenes de los productos deben colocarse en la carpeta `images/` y su nombre debe coincidir con el valor almacenado en la base de datos.
- El sistema de puntos otorga 1 punto por cada $1,000 gastados en el restaurante. 