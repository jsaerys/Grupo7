<?php
require_once '../../modelo/mascota.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$mascota = new Mascota();
$mascotas = $mascota->listarPorUsuario($_SESSION['user']['id']);

function getAvatarByType($tipo) {
    // Normalizar el tipo a minúsculas y quitar espacios
    $tipo = strtolower(trim($tipo));
    
    // Mapeo de tipos de mascota
    $tipoMap = [
        'gato' => 'gato',
        'cat' => 'gato',
        'perro' => 'perro',
        'dog' => 'perro',
        'ave' => 'ave',
        'bird' => 'ave',
        'pajaro' => 'ave'
    ];
    
    // Normalizar el tipo usando el mapeo
    $tipoNormalizado = isset($tipoMap[$tipo]) ? $tipoMap[$tipo] : 'otro';
    
    // Construir la ruta de la imagen
    return 'recursos/avatares/' . $tipoNormalizado . '.jpg';
}
?>

<style>
.pet-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
    margin-bottom: 1rem;
}

.pet-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.pet-avatar {
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
    padding: 1.5rem;
    text-align: center;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pet-avatar img {
    max-height: 120px;
    max-width: 120px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.pet-card:hover .pet-avatar img {
    transform: scale(1.1);
}

.pet-info {
    padding: 1.5rem;
    background: white;
}

.pet-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    text-transform: capitalize;
}

.pet-details {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
}

.pet-details strong {
    color: #2c3e50;
    font-weight: 600;
}

.btn-outline-danger {
    border-width: 2px;
    font-weight: 500;
    padding: 0.375rem 1rem;
}

.btn-outline-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(220,53,69,0.2);
}
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Mis Mascotas</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPetModal">
            <i class="bi bi-plus-circle me-2"></i>Agregar Mascota
        </button>
    </div>

    <?php if (empty($mascotas)): ?>
        <div class="alert alert-info">
            No tienes mascotas registradas. ¡Agrega una!
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($mascotas as $mascota): ?>
                <div class="col">
                    <div class="card h-100 pet-card bg-white">
                        <div class="pet-avatar bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <?php $avatarSrc = getAvatarByType($mascota['tipo']); ?>
                            <img src="<?php echo $avatarSrc; ?>" 
                                 alt="Avatar de <?php echo htmlspecialchars($mascota['nombre']); ?>"
                                 class="img-fluid"
                                 style="width: 150px; height: 150px; object-fit: contain;"
                                 onerror="this.onerror=null; this.src='recursos/avatares/otro.jpg';">
                        </div>
                        <div class="card-body pet-info">
                            <h5 class="pet-name text-primary"><?php echo htmlspecialchars($mascota['nombre']); ?></h5>
                            <div class="pet-details mb-3">
                                <div class="mb-2"><strong>Tipo:</strong> <?php echo htmlspecialchars($mascota['tipo']); ?></div>
                                <div><strong>Raza:</strong> <?php echo htmlspecialchars($mascota['raza']); ?></div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-danger btn-sm" 
                                        onclick="if(confirm('¿Estás seguro de eliminar esta mascota?')) window.location.href='../../controlador/mascotacontroller.php?action=delete&id=<?php echo $mascota['id']; ?>'">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para agregar mascota -->
<div class="modal fade" id="addPetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nueva Mascota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controlador/mascotacontroller.php?action=create" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" name="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="gato">Gato</option>
                            <option value="perro">Perro</option>
                            <option value="ave">Ave</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="raza" class="form-label">Raza</label>
                        <input type="text" class="form-control" id="raza" name="raza" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
