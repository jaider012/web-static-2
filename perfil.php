<?php
// Set page title
$pageTitle = "Mi Perfil";

// Include necessary files
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/cart.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Redirect to login page
    header('Location: registro.php');
    exit;
}

// Get user data
$user = getCurrentUser();

// Get user order history
$orders = getClientOrders($_SESSION['user_id']);

// Determine user level benefits
$benefits = [];
$nextLevel = '';
$pointsNeeded = 0;
$progressPercent = 0;

switch ($user['tipo_cliente']) {
    case 'regular':
        $benefits = [
            '5% de descuento en todas las compras',
            'Acceso a promociones especiales'
        ];
        $nextLevel = 'VIP';
        $pointsNeeded = 500 - $user['puntos'];
        $progressPercent = ($user['puntos'] / 500) * 100;
        break;
        
    case 'vip':
        $benefits = [
            '10% de descuento en todas las compras',
            'Acceso a promociones especiales',
            'Delivery gratuito',
            'Reservas prioritarias'
        ];
        $nextLevel = 'Premium';
        $pointsNeeded = 1000 - $user['puntos'];
        $progressPercent = ($user['puntos'] / 1000) * 100;
        break;
        
    case 'premium':
        $benefits = [
            '15% de descuento en todas las compras',
            'Acceso a promociones especiales',
            'Delivery gratuito',
            'Reservas prioritarias',
            'Menú de degustación exclusivo',
            'Invitaciones a eventos especiales'
        ];
        $nextLevel = '';
        $pointsNeeded = 0;
        $progressPercent = 100;
        break;
}

// Include header
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="datos-personales">
                <h2>Mis Datos</h2>
                <div class="perfil-info">
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre']); ?></p>
                    <p><strong>Cédula:</strong> <?php echo htmlspecialchars($user['cedula']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($user['telefono']); ?></p>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($user['direccion']); ?></p>
                    <p><strong>Tipo de Cliente:</strong> <span class="text-primary"><?php echo ucfirst(htmlspecialchars($user['tipo_cliente'])); ?></span></p>
                    <p><strong>Puntos Acumulados:</strong> <span class="text-primary"><?php echo $user['puntos']; ?></span></p>
                </div>
                
                <?php if ($nextLevel): ?>
                <div class="mt-4">
                    <h4>Progreso hacia nivel <?php echo $nextLevel; ?></h4>
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $progressPercent; ?>%" 
                             aria-valuenow="<?php echo $progressPercent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="mt-2">Necesitas <?php echo $pointsNeeded; ?> puntos más para alcanzar el nivel <?php echo $nextLevel; ?>.</p>
                </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="#" class="btn btn-outline-primary">Editar Perfil</a>
                </div>
            </div>
            
            <div class="beneficios-cliente mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Beneficios de Cliente <?php echo ucfirst(htmlspecialchars($user['tipo_cliente'])); ?></h3>
                        <ul class="beneficios-lista">
                            <?php foreach ($benefits as $benefit): ?>
                                <li><?php echo $benefit; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="historial-pedidos">
                <h2>Historial de Pedidos</h2>
                
                <?php if (empty($orders)): ?>
                    <div class="alert alert-info">Aún no has realizado ningún pedido.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Orden #</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Descuento</th>
                                    <th>Estado</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['fecha_orden'])); ?></td>
                                        <td><?php echo formatPrice($order['total']); ?></td>
                                        <td><?php echo formatPrice($order['descuento']); ?></td>
                                        <td>
                                            <span class="estado-pedido <?php echo 'estado-' . $order['estado']; ?>">
                                                <?php 
                                                    switch ($order['estado']) {
                                                        case 'pendiente':
                                                            echo 'Pendiente';
                                                            break;
                                                        case 'en_proceso':
                                                            echo 'En Proceso';
                                                            break;
                                                        case 'entregado':
                                                            echo 'Entregado';
                                                            break;
                                                        case 'cancelado':
                                                            echo 'Cancelado';
                                                            break;
                                                        default:
                                                            echo $order['estado'];
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary ver-detalles" data-id="<?php echo $order['id']; ?>">
                                                Ver Detalles
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="cotizaciones-cliente mt-4">
                <h2>Mis Cotizaciones</h2>
                
                <?php
                // Get user quotations
                require_once 'includes/menu.php';
                $quotations = getClientQuotations($_SESSION['user_id']);
                ?>
                
                <?php if (empty($quotations)): ?>
                    <div class="alert alert-info">Aún no has solicitado ninguna cotización para eventos.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cotización #</th>
                                    <th>Evento</th>
                                    <th>Fecha Solicitada</th>
                                    <th>Total</th>
                                    <th>Descuento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotations as $quotation): ?>
                                    <tr>
                                        <td><?php echo $quotation['id']; ?></td>
                                        <td><?php echo htmlspecialchars($quotation['evento_nombre']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($quotation['fecha_evento'])); ?></td>
                                        <td><?php echo formatPrice($quotation['cotizacion_total']); ?></td>
                                        <td><?php echo formatPrice($quotation['descuento']); ?></td>
                                        <td>
                                            <span class="estado-pedido <?php echo 'estado-' . $quotation['estado']; ?>">
                                                <?php 
                                                    switch ($quotation['estado']) {
                                                        case 'pendiente':
                                                            echo 'Pendiente';
                                                            break;
                                                        case 'aprobada':
                                                            echo 'Aprobada';
                                                            break;
                                                        case 'rechazada':
                                                            echo 'Rechazada';
                                                            break;
                                                        default:
                                                            echo $quotation['estado'];
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="eventos.php" class="btn btn-primary">Solicitar Nueva Cotización</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de pedido -->
<div class="modal fade" id="detallesPedidoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detallesPedidoBody">
                <!-- Aquí se cargarán los detalles del pedido -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?> 