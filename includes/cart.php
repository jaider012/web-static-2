<?php
/**
 * Cart Functions
 * Handles shopping cart operations
 */

// Initialize cart in session if not exists
function initCart() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Add item to cart
function addToCart($item_id, $quantity = 1) {
    initCart();
    
    require_once '../config/database.php';
    require_once 'menu.php';
    
    // Get item details
    $item = getMenuItemById($item_id);
    
    if (!$item) {
        return ['success' => false, 'message' => 'Producto no encontrado.'];
    }
    
    // Check if item already in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $item_id) {
            $cart_item['quantity'] += $quantity;
            $cart_item['subtotal'] = $cart_item['quantity'] * $cart_item['precio'];
            $found = true;
            break;
        }
    }
    
    // If not found, add new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $item['id'],
            'nombre' => $item['nombre'],
            'precio' => $item['precio'],
            'quantity' => $quantity,
            'subtotal' => $item['precio'] * $quantity,
            'imagen' => $item['imagen']
        ];
    }
    
    return ['success' => true, 'message' => 'Producto agregado al carrito.', 'cart_count' => count($_SESSION['cart'])];
}

// Update item quantity in cart
function updateCartItem($item_id, $quantity) {
    initCart();
    
    if ($quantity <= 0) {
        return removeFromCart($item_id);
    }
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $item_id) {
            $item['quantity'] = $quantity;
            $item['subtotal'] = $item['quantity'] * $item['precio'];
            return ['success' => true, 'message' => 'Cantidad actualizada.'];
        }
    }
    
    return ['success' => false, 'message' => 'Producto no encontrado en el carrito.'];
}

// Remove item from cart
function removeFromCart($item_id) {
    initCart();
    
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $item_id) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
            return ['success' => true, 'message' => 'Producto eliminado del carrito.'];
        }
    }
    
    return ['success' => false, 'message' => 'Producto no encontrado en el carrito.'];
}

// Clear cart
function clearCart() {
    initCart();
    $_SESSION['cart'] = [];
    return ['success' => true, 'message' => 'Carrito vaciado.'];
}

// Get cart contents
function getCart() {
    initCart();
    return $_SESSION['cart'];
}

// Get cart total
function getCartTotal() {
    initCart();
    
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['subtotal'];
    }
    
    return $total;
}

// Get cart count (number of items)
function getCartCount() {
    initCart();
    return count($_SESSION['cart']);
}

// Process order
function processOrder($client_id, $direccion_entrega, $metodo_pago) {
    initCart();
    
    require_once '../config/database.php';
    require_once 'auth.php';
    
    // Get cart total
    $total = getCartTotal();
    
    if ($total <= 0 || empty($_SESSION['cart'])) {
        return ['success' => false, 'message' => 'El carrito está vacío.'];
    }
    
    // Get client information for discount
    $conn = getDbConnection();
    $sql_client = "SELECT tipo_cliente FROM clients WHERE id = ?";
    $stmt_client = $conn->prepare($sql_client);
    
    if (!$stmt_client) {
        return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
    }
    
    $stmt_client->bind_param("i", $client_id);
    $stmt_client->execute();
    $result_client = $stmt_client->get_result();
    
    $descuento = 0;
    if ($result_client && $result_client->num_rows === 1) {
        $client = $result_client->fetch_assoc();
        $tipo_cliente = $client['tipo_cliente'];
        
        // Calculate discount based on client type
        $descuento = calculateDiscount($total, $tipo_cliente);
    }
    
    $stmt_client->close();
    
    // Create order
    $sql_order = "INSERT INTO orders (client_id, total, descuento, direccion_entrega, metodo_pago) 
                 VALUES (?, ?, ?, ?, ?)";
    
    $stmt_order = $conn->prepare($sql_order);
    
    if (!$stmt_order) {
        $conn->close();
        return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
    }
    
    $stmt_order->bind_param("iddss", $client_id, $total, $descuento, $direccion_entrega, $metodo_pago);
    
    if (!$stmt_order->execute()) {
        $error = $stmt_order->error;
        $stmt_order->close();
        $conn->close();
        return ['success' => false, 'message' => 'Error al crear el pedido: ' . $error];
    }
    
    $order_id = $stmt_order->insert_id;
    $stmt_order->close();
    
    // Add order items
    $sql_item = "INSERT INTO order_items (order_id, menu_item_id, cantidad, precio_unitario, subtotal) 
                VALUES (?, ?, ?, ?, ?)";
    
    $stmt_item = $conn->prepare($sql_item);
    
    if (!$stmt_item) {
        // Rollback by deleting the order
        $conn->query("DELETE FROM orders WHERE id = $order_id");
        $conn->close();
        return ['success' => false, 'message' => 'Error preparing statement: ' . $conn->error];
    }
    
    foreach ($_SESSION['cart'] as $item) {
        $item_id = $item['id'];
        $quantity = $item['quantity'];
        $precio = $item['precio'];
        $subtotal = $item['subtotal'];
        
        $stmt_item->bind_param("iiidd", $order_id, $item_id, $quantity, $precio, $subtotal);
        
        if (!$stmt_item->execute()) {
            $error = $stmt_item->error;
            $stmt_item->close();
            // Rollback by deleting the order and items
            $conn->query("DELETE FROM order_items WHERE order_id = $order_id");
            $conn->query("DELETE FROM orders WHERE id = $order_id");
            $conn->close();
            return ['success' => false, 'message' => 'Error al guardar los productos: ' . $error];
        }
    }
    
    $stmt_item->close();
    
    // Add points to client (1 point per $1000 spent)
    $points_to_add = floor(($total - $descuento) / 1000);
    if ($points_to_add > 0) {
        addPointsToClient($client_id, $points_to_add);
        
        // Check if client type needs upgrade
        upgradeClientType($client_id);
    }
    
    // Clear cart
    clearCart();
    
    $conn->close();
    
    return [
        'success' => true, 
        'message' => 'Pedido realizado con éxito.', 
        'order_id' => $order_id, 
        'total' => $total,
        'descuento' => $descuento,
        'points_earned' => $points_to_add
    ];
}

// Get order history for a client
function getClientOrders($client_id) {
    require_once '../config/database.php';
    
    $sql = "SELECT * FROM orders WHERE client_id = ? ORDER BY fecha_orden DESC";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return [];
    }
    
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $orders = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Get order items
            $order_id = $row['id'];
            $sql_items = "SELECT oi.*, m.nombre FROM order_items oi 
                         LEFT JOIN menu_items m ON oi.menu_item_id = m.id 
                         WHERE oi.order_id = ?";
            
            $stmt_items = $conn->prepare($sql_items);
            
            if ($stmt_items) {
                $stmt_items->bind_param("i", $order_id);
                $stmt_items->execute();
                $result_items = $stmt_items->get_result();
                
                $items = [];
                if ($result_items && $result_items->num_rows > 0) {
                    while ($item = $result_items->fetch_assoc()) {
                        $items[] = $item;
                    }
                }
                
                $row['items'] = $items;
                $stmt_items->close();
            }
            
            $orders[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    
    return $orders;
}

// Get order details
function getOrderDetails($order_id) {
    require_once '../config/database.php';
    
    $sql = "SELECT o.*, c.nombre as cliente_nombre, c.cedula, c.email, c.telefono 
            FROM orders o 
            LEFT JOIN clients c ON o.client_id = c.id 
            WHERE o.id = ?";
    
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        return null;
    }
    
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $order = $result->fetch_assoc();
        
        // Get order items
        $sql_items = "SELECT oi.*, m.nombre, m.imagen FROM order_items oi 
                     LEFT JOIN menu_items m ON oi.menu_item_id = m.id 
                     WHERE oi.order_id = ?";
        
        $stmt_items = $conn->prepare($sql_items);
        
        if ($stmt_items) {
            $stmt_items->bind_param("i", $order_id);
            $stmt_items->execute();
            $result_items = $stmt_items->get_result();
            
            $items = [];
            if ($result_items && $result_items->num_rows > 0) {
                while ($item = $result_items->fetch_assoc()) {
                    $items[] = $item;
                }
            }
            
            $order['items'] = $items;
            $stmt_items->close();
        }
        
        $stmt->close();
        $conn->close();
        
        return $order;
    }
    
    $stmt->close();
    $conn->close();
    
    return null;
}
?> 