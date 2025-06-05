<?php require_once 'views/templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3>Editar Producto</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/index.php?page=products&action=edit&id=<?= $product['id'] ?>" 
                          method="POST" 
                          enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= htmlspecialchars($product['nombre']) ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="3" 
                                      required><?= htmlspecialchars($product['descripcion']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="precio" 
                                   name="precio" 
                                   step="0.01" 
                                   value="<?= htmlspecialchars($product['precio']) ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="stock" 
                                   name="stock" 
                                   value="<?= htmlspecialchars($product['stock']) ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <?php if (!empty($product['imagen'])): ?>
                                <div class="mb-2">
                                    <img src="<?= BASE_URL ?>/assets/img/products/<?= $product['imagen'] ?>" 
                                         alt="Imagen actual" 
                                         class="img-thumbnail" 
                                         style="max-height: 200px;">
                                    <div class="form-text">Imagen actual</div>
                                </div>
                            <?php endif; ?>
                            <input type="file" 
                                   class="form-control" 
                                   id="imagen" 
                                   name="imagen" 
                                   accept="image/*">
                            <div class="form-text">Seleccione una nueva imagen para reemplazar la actual (opcional)</div>
                        </div>

                        <div class="mb-3">
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 200px;">
                                <div class="form-text">Vista previa de la nueva imagen</div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                            <a href="<?= BASE_URL ?>/index.php?page=products" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('imagen').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?php require_once 'views/templates/footer.php'; ?> 