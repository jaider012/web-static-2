# Configuración del Entorno de Desarrollo

## Requisitos Previos
- MAMP (versión 6.x o superior)
- Editor de código (VS Code recomendado)
- Navegador web moderno

## Instalación y Configuración de MAMP

### 1. Instalación de MAMP
1. Descargar MAMP desde la página oficial: https://www.mamp.info/
2. Ejecutar el instalador y seguir los pasos del asistente
3. Instalar MAMP en la ubicación predeterminada

### 2. Configuración Inicial de MAMP
1. Abrir MAMP
2. Configurar los puertos:
   - Apache: 8888 (o el puerto de tu preferencia)
   - MySQL: 8889
3. Iniciar los servidores desde el panel de control de MAMP

### 3. Configuración del Proyecto

#### Estructura de Directorios
```
Project Root/
├── includes/          # Componentes PHP reutilizables
├── Database/         # Archivos de base de datos
├── css/             # Hojas de estilo
├── js/              # Archivos JavaScript
├── Documentacion/   # Documentación del proyecto
└── config/          # Archivos de configuración
```

#### Configuración de la Base de Datos
1. Acceder a phpMyAdmin: http://localhost:8888/phpMyAdmin
2. Crear una nueva base de datos llamada 'mydelights'
3. Importar el archivo `Database/mydelights.sql`

## Desarrollo Local

### Acceso al Proyecto
- URL local: http://localhost:8888/[nombre-del-proyecto]
- Directorio del proyecto: /Applications/MAMP/htdocs/[nombre-del-proyecto]

### Archivos de Configuración Importantes
1. `includes/auth.php`: Gestión de autenticación
2. `includes/header.php`: Encabezado común
3. `includes/footer.php`: Pie de página común
4. `includes/menu.php`: Navegación
5. `includes/cart.php`: Funcionalidad del carrito

### Buenas Prácticas Implementadas
1. Separación de componentes en la carpeta `includes/`
2. Archivos de estilo centralizados en `css/`
3. Sistema modular de autenticación
4. Gestión de sesiones para el carrito de compras
5. Estructura MVC simplificada

## Consideraciones de Seguridad
- Validación de entrada de usuarios
- Prevención de SQL Injection
- Manejo seguro de sesiones
- Protección contra XSS

## Mantenimiento
- Respaldos regulares de la base de datos
- Actualización periódica de MAMP
- Revisión de logs de errores
- Optimización de consultas SQL

## Solución de Problemas Comunes
1. Problemas de conexión a la base de datos:
   - Verificar que MAMP esté ejecutándose
   - Comprobar credenciales en archivos de configuración
   
2. Errores de permisos:
   - Verificar permisos de carpetas y archivos
   - Asegurar acceso de escritura en directorios necesarios

3. Problemas de sesión:
   - Limpiar caché del navegador
   - Verificar configuración de PHP para sesiones 