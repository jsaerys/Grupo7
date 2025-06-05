<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Carrito de Compras</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">
                ¡Compra realizada con éxito! Gracias por tu compra.
            </div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="alert alert-info">
                Tu carrito está vacío. <a href="/proyectos/colaborativo/Grupo7/index.php?page=products">Ver productos</a>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <tr id="cart-item-<?php echo $item['producto_id']; ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo !empty($item['imagen']) ? '/proyectos/colaborativo/Grupo7/assets/img/products/' . htmlspecialchars($item['imagen']) : 'https://via.placeholder.com/50x50.png?text=No+Image'; ?>" 
                                                     alt="<?php echo htmlspecialchars($item['nombre']); ?>"
                                                     class="img-thumbnail me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['nombre']); ?></h6>
                                                    <?php if ($item['cantidad'] > $item['stock']): ?>
                                                        <small class="text-danger">Stock insuficiente</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                                        <td style="width: 150px;">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button"
                                                        onclick="updateCartQuantity(<?php echo $item['producto_id']; ?>, Math.max(1, parseInt(document.getElementById('quantity-<?php echo $item['producto_id']; ?>').value) - 1))">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       class="form-control text-center" 
                                                       id="quantity-<?php echo $item['producto_id']; ?>"
                                                       value="<?php echo $item['cantidad']; ?>"
                                                       min="1"
                                                       max="<?php echo $item['stock']; ?>"
                                                       onchange="updateCartQuantity(<?php echo $item['producto_id']; ?>, this.value)">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button"
                                                        onclick="updateCartQuantity(<?php echo $item['producto_id']; ?>, Math.min(<?php echo $item['stock']; ?>, parseInt(document.getElementById('quantity-<?php echo $item['producto_id']; ?>').value) + 1))">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" 
                                                    onclick="removeFromCart(<?php echo $item['producto_id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="2"><strong id="cart-total">$<?php echo number_format($total, 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button class="btn btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>Vaciar Carrito
                        </button>
                        <div>
                            <a href="/proyectos/colaborativo/Grupo7/index.php?page=products" class="btn btn-outline-primary me-2">
                                <i class="fas fa-shopping-bag me-2"></i>Seguir Comprando
                            </a>
                            <form action="/proyectos/colaborativo/Grupo7/index.php?page=cart&action=checkout" method="POST" class="d-inline">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Finalizar Compra
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function clearCart() {
    confirmAction(
        '¿Vaciar carrito?',
        '¿Estás seguro de que deseas vaciar el carrito?',
        function() {
            $.ajax({
                url: '/proyectos/colaborativo/Grupo7/index.php?page=cart&action=clear',
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        showNotification('Error', 'No se pudo vaciar el carrito', 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'No se pudo vaciar el carrito', 'error');
                }
            });
        }
    );
}
</script> 