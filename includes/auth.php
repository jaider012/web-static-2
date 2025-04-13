<?php
/**
 * Authentication Functions
 * Handles login, registration, and session management
 */

// Start session if not already started
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Register new user
function registerUser($cedula, $nombre, $sexo, $fecha_nacimiento, $direccion, $telefono, $email, $password) {
    require_once '../config/database.php';
    
    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare SQL query with parameterized statement for security
    $sql = "INSERT INTO clients (cedula, nombre, sexo, fecha_nacimiento, direccion, telefono, email, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
    }
    
    $stmt->bind_param("ssssssss", $cedula, $nombre, $sexo, $fecha_nacimiento, $direccion, $telefono, $email, $hashed_password);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
        return ['success' => true, 'user_id' => $user_id];
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        
        // Check for duplicate entry
        if (strpos($error, 'Duplicate entry') !== false) {
            if (strpos($error, 'email') !== false) {
                return ['success' => false, 'message' => 'Este correo electrónico ya está registrado.'];
            } elseif (strpos($error, 'cedula') !== false) {
                return ['success' => false, 'message' => 'Esta cédula ya está registrada.'];
            }
        }
        
        return ['success' => false, 'message' => 'Error al registrar: ' . $error];
    }
}

// Login user
function loginUser($email, $password) {
    require_once '../config/database.php';
    
    $sql = "SELECT id, cedula, nombre, password, tipo_cliente FROM clients WHERE email = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session
            startSession();
            
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['cedula'] = $user['cedula'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['tipo_cliente'] = $user['tipo_cliente'];
            $_SESSION['logged_in'] = true;
            
            $stmt->close();
            $conn->close();
            
            return ['success' => true, 'user' => $user];
        } else {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => 'Contraseña incorrecta.'];
        }
    } else {
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => 'No existe un usuario con este correo electrónico.'];
    }
}

// Check if user is logged in
function isLoggedIn() {
    startSession();
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Get current user data
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    require_once '../config/database.php';
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT id, cedula, nombre, sexo, fecha_nacimiento, direccion, telefono, email, 
                   tipo_cliente, puntos, created_at 
            FROM clients 
            WHERE id = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return null;
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user;
    }
    
    $stmt->close();
    $conn->close();
    return null;
}

// Logout user
function logoutUser() {
    startSession();
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
    exit;
}

// Calculate discount based on client type
function calculateDiscount($total, $tipo_cliente) {
    switch ($tipo_cliente) {
        case 'premium':
            return $total * 0.15; // 15% discount
        case 'vip':
            return $total * 0.10; // 10% discount
        case 'regular':
            return $total * 0.05; // 5% discount
        default:
            return 0;
    }
}

// Add points to client
function addPointsToClient($client_id, $points) {
    require_once '../config/database.php';
    
    $sql = "UPDATE clients SET puntos = puntos + ? WHERE id = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("ii", $points, $client_id);
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $success;
}

// Upgrade client type based on points
function upgradeClientType($client_id) {
    require_once '../config/database.php';
    
    // First get current points
    $sql = "SELECT puntos FROM clients WHERE id = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows !== 1) {
        $stmt->close();
        $conn->close();
        return false;
    }
    
    $row = $result->fetch_assoc();
    $points = $row['puntos'];
    
    // Determine new client type based on points
    $new_type = 'regular';
    if ($points >= 1000) {
        $new_type = 'premium';
    } elseif ($points >= 500) {
        $new_type = 'vip';
    }
    
    // Update client type
    $update_sql = "UPDATE clients SET tipo_cliente = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if (!$update_stmt) {
        $stmt->close();
        $conn->close();
        return false;
    }
    
    $update_stmt->bind_param("si", $new_type, $client_id);
    $success = $update_stmt->execute();
    
    $update_stmt->close();
    $stmt->close();
    $conn->close();
    
    // Update session if current user
    if (isLoggedIn() && $_SESSION['user_id'] == $client_id) {
        $_SESSION['tipo_cliente'] = $new_type;
    }
    
    return $success;
}
?> 