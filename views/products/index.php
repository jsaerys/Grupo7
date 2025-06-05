<?php require_once 'views/templates/header.php'; ?>

<div class="container mt-4">
    <h2>Productos</h2>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
    <div class="mb-3">
        <a href="<?= BASE_URL ?>/index.php?page=products&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($product['imagen'])): ?>
                        <img src="<?= BASE_URL ?>/assets/img/products/<?= $product['imagen'] ?>" 
                             class="card-img-top product-image" 
                             alt="<?= htmlspecialchars($product['nombre']) ?>"
                             style="height: 200px; object-fit: cover; cursor: pointer;"
                             data-bs-toggle="modal" 
                             data-bs-target="#imageModal"
                             data-bs-img="<?= BASE_URL ?>/assets/img/products/<?= $product['imagen'] ?>"
                             data-bs-title="<?= htmlspecialchars($product['nombre']) ?>">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['descripcion']) ?></p>
                        <p class="card-text">
                            <strong>Precio:</strong> $<?= number_format($product['precio'], 2) ?><br>
                            <strong>Stock:</strong> <?= $product['stock'] ?>
                        </p>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <div class="btn-group">
                                <a href="<?= BASE_URL ?>/index.php?page=products&action=edit&id=<?= $product['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= BASE_URL ?>/index.php?page=products&action=delete&id=<?= $product['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal para mostrar imagen -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" id="modalImage" alt="">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imgSrc = button.getAttribute('data-bs-img');
            const title = button.getAttribute('data-bs-title');
            
            const modalTitle = this.querySelector('.modal-title');
            const modalImage = this.querySelector('#modalImage');
            
            modalTitle.textContent = title;
            modalImage.src = imgSrc;
        });
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 