<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Productos</h1>
            <div class="d-flex gap-2">
                <div class="input-group">
                    <input type="text" 
                           id="searchInput" 
                           class="form-control" 
                           placeholder="Buscar productos...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <a href="/proyectos/colaborativo/Grupo7/index.php?page=products&action=create" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Producto
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (empty($products)): ?>
                    <p class="text-muted">No hay productos registrados.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="productsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td>
                                            <img src="/proyectos/colaborativo/Grupo7/assets/img/products/<?php echo $product['imagen']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['nombre']); ?>"
                                                 class="img-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($product['categoria']); ?></td>
                                        <td>$<?php echo number_format($product['precio'], 2); ?></td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control stock-input" 
                                                       value="<?php echo $product['stock']; ?>"
                                                       min="0"
                                                       data-product-id="<?php echo $product['id']; ?>"
                                                       onchange="updateStock(<?php echo $product['id']; ?>, this.value)">
                                                <span class="input-group-text">unidades</span>
                                            </div>
                                            <?php if ($product['stock'] <= 5): ?>
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Stock bajo
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       <?php echo $product['activo'] ? 'checked' : ''; ?>
                                                       onchange="toggleProductStatus(<?php echo $product['id']; ?>, this.checked)">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" 
                                                        onclick="viewProductDetails(<?php echo $product['id']; ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="/proyectos/colaborativo/Grupo7/index.php?page=products&action=edit&id=<?php echo $product['id']; ?>" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="productDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="productDetails"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Búsqueda en tiempo real
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const table = document.getElementById('productsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length - 1; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(searchText) > -1) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
});

function updateStock(productId, newStock) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=updateProductStock',
        method: 'POST',
        data: {
            product_id: productId,
            stock: newStock
        },
        success: function(response) {
            if (response.success) {
                showNotification('¡Éxito!', 'Stock actualizado correctamente');
            } else {
                showNotification('Error', 'No se pudo actualizar el stock', 'error');
                // Revertir el valor en caso de error
                document.querySelector(`input[data-product-id="${productId}"]`).value = response.current_stock;
            }
        },
        error: function() {
            showNotification('Error', 'No se pudo actualizar el stock', 'error');
        }
    });
}

function toggleProductStatus(productId, status) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=toggleProductStatus',
        method: 'POST',
        data: {
            product_id: productId,
            status: status
        },
        success: function(response) {
            if (response.success) {
                showNotification('¡Éxito!', 'Estado del producto actualizado');
            } else {
                showNotification('Error', 'No se pudo actualizar el estado del producto', 'error');
            }
        },
        error: function() {
            showNotification('Error', 'No se pudo actualizar el estado del producto', 'error');
        }
    });
}

function viewProductDetails(productId) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=getProductDetails',
        method: 'GET',
        data: { product_id: productId },
        success: function(response) {
            if (response.success) {
                const product = response.product;
                let html = `
                    <div class="row">
                        <div class="col-md-4">
                            <img src="/proyectos/colaborativo/Grupo7/assets/img/products/${product.imagen}" 
                                 alt="${product.nombre}"
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <dl class="row">
                                <dt class="col-sm-4">Nombre:</dt>
                                <dd class="col-sm-8">${product.nombre}</dd>
                                
                                <dt class="col-sm-4">Categoría:</dt>
                                <dd class="col-sm-8">${product.categoria}</dd>
                                
                                <dt class="col-sm-4">Precio:</dt>
                                <dd class="col-sm-8">$${parseFloat(product.precio).toFixed(2)}</dd>
                                
                                <dt class="col-sm-4">Stock:</dt>
                                <dd class="col-sm-8">${product.stock} unidades</dd>
                                
                                <dt class="col-sm-4">Estado:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-${product.activo ? 'success' : 'danger'}">
                                        ${product.activo ? 'Activo' : 'Inactivo'}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Descripción:</dt>
                                <dd class="col-sm-8">${product.descripcion}</dd>
                            </dl>
                        </div>
                    </div>
                `;
                $('#productDetails').html(html);
                $('#productDetailsModal').modal('show');
            }
        }
    });
}

function deleteProduct(productId) {
    confirmAction(
        '¿Eliminar producto?',
        '¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.',
        function() {
            $.ajax({
                url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=deleteProduct',
                method: 'POST',
                data: { product_id: productId },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
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