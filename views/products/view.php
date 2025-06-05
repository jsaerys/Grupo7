<div class="row">
    <div class="col-md-6">
        <img src="<?php echo !empty($product['imagen']) ? '/proyectos/colaborativo/Grupo7/assets/img/products/' . htmlspecialchars($product['imagen']) : 'https://via.placeholder.com/600x400.png?text=No+Image'; ?>" 
             class="img-fluid rounded shadow" 
             alt="<?php echo htmlspecialchars($product['nombre']); ?>">
    </div>
    <div class="col-md-6">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/proyectos/colaborativo/Grupo7/index.php?page=products">Productos</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['nombre']); ?></li>
            </ol>
        </nav>

        <h1 class="mb-4"><?php echo htmlspecialchars($product['nombre']); ?></h1>
        
        <div class="mb-4">
            <span class="h2 text-primary">$<?php echo number_format($product['precio'], 2); ?></span>
            <span class="badge bg-<?php echo $product['stock'] > 0 ? 'success' : 'danger'; ?> ms-2">
                <?php echo $product['stock'] > 0 ? 'En Stock' : 'Agotado'; ?>
            </span>
        </div>

        <div class="mb-4">
            <h5>Descripción:</h5>
            <p class="lead"><?php echo nl2br(htmlspecialchars($product['descripcion'])); ?></p>
        </div>

        <?php if ($product['stock'] > 0): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label for="quantity" class="form-label mb-0">Cantidad:</label>
                        </div>
                        <div class="col">
                            <select class="form-select" id="quantity">
                                <?php for ($i = 1; $i <= min(10, $product['stock']); $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button onclick="addToCart(<?php echo $product['id']; ?>, document.getElementById('quantity').value)" 
                                    class="btn btn-primary">
                                <i class="fas fa-cart-plus me-2"></i>Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin'): ?>
            <div class="btn-group">
                <a href="/proyectos/colaborativo/Grupo7/index.php?page=products&action=edit&id=<?php echo $product['id']; ?>" 
                   class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Editar
                </a>
                <button onclick="confirmDeleteProduct(<?php echo $product['id']; ?>)" 
                        class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Eliminar
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDeleteProduct(productId) {
    confirmAction(
        '¿Eliminar producto?',
        '¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.',
        function() {
            $.ajax({
                url: '/proyectos/colaborativo/Grupo7/index.php?page=products&action=delete',
                method: 'POST',
                data: { id: productId },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '/proyectos/colaborativo/Grupo7/index.php?page=products';
                    } else {
                        showNotification('Error', 'No se pudo eliminar el producto', 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'No se pudo eliminar el producto', 'error');
                }
            });
        }
    );
}
</script> 