<?php
// Set page title
$pageTitle = "Registro/Login";

// Include necessary files
require_once 'config/database.php';
require_once 'includes/auth.php';

// Initialize variables for form data and errors
$cedula = $nombre = $sexo = $fecha_nacimiento = $direccion = $telefono = $email = $password = $confirm_password = '';
$login_email = $login_password = '';
$register_error = $login_error = '';
$register_success = $login_success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Get form data
    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $sexo = $_POST['sexo'] ?? 'masculino';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate form data
    if (empty($cedula) || empty($nombre) || empty($fecha_nacimiento) || empty($direccion) || 
        empty($telefono) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = 'Por favor ingresa un correo electrónico válido.';
    } elseif ($password !== $confirm_password) {
        $register_error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $register_error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Register user
        $result = registerUser($cedula, $nombre, $sexo, $fecha_nacimiento, $direccion, $telefono, $email, $password);
        
        if ($result['success']) {
            $register_success = 'Registro exitoso. Ahora puedes iniciar sesión.';
            // Clear form data
            $cedula = $nombre = $sexo = $fecha_nacimiento = $direccion = $telefono = $email = $password = $confirm_password = '';
        } else {
            $register_error = $result['message'];
        }
    }
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Get form data
    $login_email = trim($_POST['login_email'] ?? '');
    $login_password = $_POST['login_password'] ?? '';
    
    // Validate form data
    if (empty($login_email) || empty($login_password)) {
        $login_error = 'Por favor ingresa tu correo electrónico y contraseña.';
    } else {
        // Login user
        $result = loginUser($login_email, $login_password);
        
        if ($result['success']) {
            // Redirect to home page
            header('Location: index.php');
            exit;
        } else {
            $login_error = $result['message'];
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="auth-container">
    <!-- Formulario de registro -->
    <section class="registro">
        <h2>Registro de Nuevo Cliente</h2>
        
        <?php if (!empty($register_error)): ?>
            <div class="alert alert-danger"><?php echo $register_error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($register_success)): ?>
            <div class="alert alert-success"><?php echo $register_success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="cedula" class="form-label">Cédula:</label>
                <input type="text" id="cedula" name="cedula" class="form-control" value="<?php echo htmlspecialchars($cedula); ?>" required />
            </div>
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" required />
            </div>
            <div class="form-group">
                <label for="sexo" class="form-label">Sexo:</label>
                <select id="sexo" name="sexo" class="form-control">
                    <option value="masculino" <?php if ($sexo === 'masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="femenino" <?php if ($sexo === 'femenino') echo 'selected'; ?>>Femenino</option>
                    <option value="otro" <?php if ($sexo === 'otro') echo 'selected'; ?>>Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" required />
            </div>
            <div class="form-group">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo htmlspecialchars($direccion); ?>" required />
            </div>
            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($telefono); ?>" required />
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required />
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
            </div>
            <button type="submit" name="register" class="btn btn-primary">Registrarse</button>
        </form>
    </section>

    <!-- Formulario de login -->
    <section class="login">
        <h2>Iniciar Sesión</h2>
        
        <?php if (!empty($login_error)): ?>
            <div class="alert alert-danger"><?php echo $login_error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="login_email" class="form-label">Email:</label>
                <input type="email" id="login_email" name="login_email" class="form-control" value="<?php echo htmlspecialchars($login_email); ?>" required />
            </div>
            <div class="form-group">
                <label for="login_password" class="form-label">Contraseña:</label>
                <input type="password" id="login_password" name="login_password" class="form-control" required />
            </div>
            <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </section>
</div>

<?php
// Include footer
include 'includes/footer.php';
?> 