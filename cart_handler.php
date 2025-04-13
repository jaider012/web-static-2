<?php
/**
 * Cart Handler
 * Processes AJAX requests for cart operations
 */

// Include necessary files
require_once 'config/database.php';
require_once 'includes/cart.php';
require_once 'includes/menu.php';

// Set header to JSON
header('Content-Type: application/json');

// Check if request is AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the action from the request
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Process the action
switch ($action) {
    case 'add':
        // Add item to cart
        $item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        
        if ($item_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de producto inválido']);
            exit;
        }
        
        $result = addToCart($item_id, $quantity);
        echo json_encode($result);
        break;
        
    case 'update':
        // Update item quantity
        $item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;
        
        if ($item_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de producto inválido']);
            exit;
        }
        
        $result = updateCartItem($item_id, $quantity);
        
        // Get updated cart total
        $total = getCartTotal();
        $result['total'] = $total;
        $result['formatted_total'] = formatPrice($total);
        
        echo json_encode($result);
        break;
        
    case 'remove':
        // Remove item from cart
        $item_id = isset($_POST['item_id']) ? (int) $_POST['item_id'] : 0;
        
        if ($item_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID de producto inválido']);
            exit;
        }
        
        $result = removeFromCart($item_id);
        
        // Get updated cart total
        $total = getCartTotal();
        $result['total'] = $total;
        $result['formatted_total'] = formatPrice($total);
        $result['cart_count'] = getCartCount();
        
        echo json_encode($result);
        break;
        
    case 'clear':
        // Clear cart
        $result = clearCart();
        $result['total'] = 0;
        $result['formatted_total'] = formatPrice(0);
        $result['cart_count'] = 0;
        
        echo json_encode($result);
        break;
        
    case 'get':
        // Get cart contents
        $cart = getCart();
        $total = getCartTotal();
        
        echo json_encode([
            'success' => true,
            'cart' => $cart,
            'total' => $total,
            'formatted_total' => formatPrice($total),
            'cart_count' => count($cart)
        ]);
        break;
        
    default:
        // Invalid action
        echo json_encode(['success' => false, 'message' => 'Acción inválida']);
        break;
}
?> 