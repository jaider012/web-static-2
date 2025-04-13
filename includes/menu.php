<?php
/**
 * Menu Functions
 * Handles menu items, categories, and related operations
 */

// Get all menu categories
function getAllCategories() {
    require_once '../config/database.php';
    
    $sql = "SELECT * FROM categories ORDER BY nombre";
    
    $conn = getDbConnection();
    $result = $conn->query($sql);
    
    $categories = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    
    $conn->close();
    return $categories;
}

// Get menu items by type (carta or corriente)
function getMenuItemsByType($tipo) {
    require_once '../config/database.php';
    
    $sql = "SELECT m.*, c.nombre as categoria 
            FROM menu_items m 
            JOIN categories c ON m.category_id = c.id 
            WHERE m.tipo = ? AND m.disponible = 1 
            ORDER BY c.nombre, m.nombre";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $items;
}

// Get menu items by category
function getMenuItemsByCategory($category_id) {
    require_once '../config/database.php';
    
    $sql = "SELECT * FROM menu_items WHERE category_id = ? AND disponible = 1 ORDER BY nombre";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $items;
}

// Get featured menu items
function getFeaturedItems($limit = 6) {
    require_once '../config/database.php';
    
    $sql = "SELECT m.*, c.nombre as categoria 
            FROM menu_items m 
            JOIN categories c ON m.category_id = c.id 
            WHERE m.disponible = 1 
            ORDER BY RAND() 
            LIMIT ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $items;
}

// Get a single menu item by ID
function getMenuItemById($item_id) {
    require_once '../config/database.php';
    
    $sql = "SELECT m.*, c.nombre as categoria 
            FROM menu_items m 
            JOIN categories c ON m.category_id = c.id 
            WHERE m.id = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return null;
    }
    
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $item = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $item;
    }
    
    $stmt->close();
    $conn->close();
    return null;
}

// Format price for display
function formatPrice($price) {
    return '$' . number_format($price, 0, ',', '.');
}

// Group menu items by category
function groupMenuItemsByCategory($items) {
    $grouped = [];
    
    foreach ($items as $item) {
        $category = $item['categoria'];
        if (!isset($grouped[$category])) {
            $grouped[$category] = [];
        }
        $grouped[$category][] = $item;
    }
    
    return $grouped;
}

// Get all events
function getAllEvents() {
    require_once '../config/database.php';
    
    $sql = "SELECT * FROM events WHERE disponible = 1 ORDER BY nombre";
    
    $conn = getDbConnection();
    $result = $conn->query($sql);
    
    $events = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    
    $conn->close();
    return $events;
}

// Get event by ID
function getEventById($event_id) {
    require_once '../config/database.php';
    
    $sql = "SELECT * FROM events WHERE id = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return null;
    }
    
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $event = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $event;
    }
    
    $stmt->close();
    $conn->close();
    return null;
}

// Calculate event quotation
function calculateEventQuotation($event_id, $num_personas, $servicios_adicionales) {
    $event = getEventById($event_id);
    
    if (!$event) {
        return 0;
    }
    
    $precio_base = $event['precio_base'];
    $precio_por_persona = 25000; // Precio base por persona
    
    $cotizacion = $precio_base + ($precio_por_persona * $num_personas);
    
    // Add additional services
    if (!empty($servicios_adicionales)) {
        // Here you can add logic to calculate costs for additional services
        // For example, if servicios_adicionales is an array of service IDs
        $cotizacion += count($servicios_adicionales) * 100000;
    }
    
    return $cotizacion;
}

// Submit a quotation
function submitQuotation($client_id, $event_id, $num_personas, $fecha_evento, $hora_evento, $servicios_adicionales) {
    require_once '../config/database.php';
    
    // Calculate total quotation
    $cotizacion_total = calculateEventQuotation($event_id, $num_personas, $servicios_adicionales);
    
    // Get client type for discount
    $sql_client = "SELECT tipo_cliente FROM clients WHERE id = ?";
    $conn = getDbConnection();
    $stmt_client = $conn->prepare($sql_client);
    
    if (!$stmt_client) {
        return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
    }
    
    $stmt_client->bind_param("i", $client_id);
    $stmt_client->execute();
    $result_client = $stmt_client->get_result();
    
    if ($result_client && $result_client->num_rows === 1) {
        $client = $result_client->fetch_assoc();
        $tipo_cliente = $client['tipo_cliente'];
        
        // Calculate discount based on client type
        $descuento = 0;
        if ($tipo_cliente === 'premium') {
            $descuento = $cotizacion_total * 0.15; // 15% discount
        } elseif ($tipo_cliente === 'vip') {
            $descuento = $cotizacion_total * 0.10; // 10% discount
        } elseif ($tipo_cliente === 'regular') {
            $descuento = $cotizacion_total * 0.05; // 5% discount
        }
        
        // Convert array to string for storage
        $servicios_str = is_array($servicios_adicionales) ? implode(',', $servicios_adicionales) : $servicios_adicionales;
        
        // Insert quotation
        $sql = "INSERT INTO quotations (client_id, event_id, numero_personas, fecha_evento, hora_evento, 
                                      servicios_adicionales, cotizacion_total, descuento) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            $stmt_client->close();
            $conn->close();
            return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
        }
        
        $stmt->bind_param("iiissidi", $client_id, $event_id, $num_personas, $fecha_evento, $hora_evento, 
                         $servicios_str, $cotizacion_total, $descuento);
        
        if ($stmt->execute()) {
            $quotation_id = $stmt->insert_id;
            $stmt->close();
            $stmt_client->close();
            $conn->close();
            return ['success' => true, 'quotation_id' => $quotation_id, 'discount' => $descuento];
        } else {
            $error = $stmt->error;
            $stmt->close();
            $stmt_client->close();
            $conn->close();
            return ['success' => false, 'message' => 'Error al enviar la cotización: ' . $error];
        }
    } else {
        $stmt_client->close();
        $conn->close();
        return ['success' => false, 'message' => 'No se encontró información del cliente.'];
    }
}

// Get quotations for a client
function getClientQuotations($client_id) {
    require_once '../config/database.php';
    
    $sql = "SELECT q.*, e.nombre as evento_nombre 
            FROM quotations q 
            JOIN events e ON q.event_id = e.id 
            WHERE q.client_id = ? 
            ORDER BY q.fecha_cotizacion DESC";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $quotations = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quotations[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    return $quotations;
}
?> 