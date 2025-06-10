<?php
// No necesitamos iniciar la sesión aquí ya que este archivo es incluido por panel.php
// que ya tiene la sesión iniciada

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../modelo/mascota.php';

$mascota = new Mascota();
$resultado = $mascota->listarPorUsuario($_SESSION['user']['id']);
$mascotas = [];
while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
    $mascotas[] = $row;
}

function getAvatarByType($especie) {
    // Normalizar el tipo a minúsculas y quitar espacios
    $especie = strtolower(trim($especie));
    
    // Mapeo de tipos de mascota
    $especieMap = [
        'gato' => 'gato',
        'cat' => 'gato',
        'perro' => 'perro',
        'dog' => 'perro',
        'ave' => 'ave',
        'bird' => 'ave',
        'pajaro' => 'ave'
    ];
    
    // Normalizar el tipo usando el mapeo
    $especieNormalizada = isset($especieMap[$especie]) ? $especieMap[$especie] : 'otro';
    
    // Construir la ruta de la imagen
    return 'recursos/avatares/' . $especieNormalizada . '.jpg';
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
                            <?php $avatarSrc = getAvatarByType($mascota['especie']); ?>
                            <img src="<?php echo $avatarSrc; ?>" 
                                 alt="Avatar de <?php echo htmlspecialchars($mascota['nombre']); ?>"
                                 class="img-fluid"
                                 style="width: 150px; height: 150px; object-fit: contain;"
                                 onerror="this.onerror=null; this.src='recursos/avatares/otro.jpg';">
                        </div>
                        <div class="card-body pet-info">
                            <h5 class="pet-name text-primary"><?php echo htmlspecialchars($mascota['nombre']); ?></h5>
                            <div class="pet-details mb-3">
                                <div class="mb-2"><strong>Especie:</strong> <?php echo htmlspecialchars($mascota['especie']); ?></div>
                                <div><strong>Raza:</strong> <?php echo htmlspecialchars($mascota['raza']); ?></div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-danger btn-sm eliminar-mascota" 
                                        data-id="<?php echo $mascota['id']; ?>"
                                        data-nombre="<?php echo htmlspecialchars($mascota['nombre']); ?>">
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
            <form id="formAgregarMascota" action="/controlador/mascotacontroller.php?action=crear" method="POST" class="needs-validation" novalidate>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el nombre de la mascota
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="especie" class="form-label">Especie</label>
                        <select class="form-select" id="especie" name="especie" required>
                            <option value="">Seleccione una especie</option>
                            <option value="perro">Perro</option>
                            <option value="gato">Gato</option>
                            <option value="ave">Ave</option>
                            <option value="otro">Otro</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione una especie
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="raza" class="form-label">Raza</label>
                        <input type="text" class="form-control" id="raza" name="raza" required>
                        <div class="invalid-feedback">
                            Por favor ingrese la raza de la mascota
                        </div>
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

<script>
document.getElementById('formAgregarMascota').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    if (!this.checkValidity()) {
        event.stopPropagation();
        this.classList.add('was-validated');
        return;
    }

    try {
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Error al agregar la mascota');
        }

        if (data.success) {
            // Cerrar el modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPetModal'));
            modal.hide();
            
            // Mostrar mensaje de éxito
            await Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Mascota agregada correctamente',
                showConfirmButton: false,
                timer: 1500
            });

            // Crear y agregar la nueva tarjeta de mascota
            const newPetCard = createPetCard({
                id: data.mascota.id,
                nombre: formData.get('nombre'),
                especie: formData.get('especie'),
                raza: formData.get('raza')
            });

            // Si no hay mascotas, limpiar el mensaje de "no hay mascotas"
            const noPetsMessage = document.querySelector('.alert.alert-info');
            if (noPetsMessage) {
                const container = noPetsMessage.parentElement;
                container.innerHTML = '<div class="row row-cols-1 row-cols-md-3 g-4"></div>';
            }

            // Agregar la nueva tarjeta al contenedor
            const petsContainer = document.querySelector('.row.row-cols-1.row-cols-md-3.g-4');
            if (petsContainer) {
                petsContainer.insertAdjacentHTML('afterbegin', newPetCard);
                // Agregar el event listener al nuevo botón de eliminar
                const newDeleteButton = petsContainer.querySelector('.eliminar-mascota');
                if (newDeleteButton) {
                    addDeleteHandler(newDeleteButton);
                }
            }

            // Limpiar el formulario
            this.reset();
            this.classList.remove('was-validated');
        } else {
            throw new Error(data.message || 'Error al agregar la mascota');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al agregar la mascota'
        });
    }
});

// Función para crear el HTML de una tarjeta de mascota
function createPetCard(mascota) {
    const avatarSrc = getAvatarByType(mascota.especie);
    return `
        <div class="col">
            <div class="card h-100 pet-card bg-white">
                <div class="pet-avatar bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <img src="${avatarSrc}" 
                         alt="Avatar de ${mascota.nombre}"
                         class="img-fluid"
                         style="width: 150px; height: 150px; object-fit: contain;"
                         onerror="this.onerror=null; this.src='recursos/avatares/otro.jpg';">
                </div>
                <div class="card-body pet-info">
                    <h5 class="pet-name text-primary">${mascota.nombre}</h5>
                    <div class="pet-details mb-3">
                        <div class="mb-2"><strong>Especie:</strong> ${mascota.especie}</div>
                        <div><strong>Raza:</strong> ${mascota.raza}</div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-danger btn-sm eliminar-mascota" 
                                data-id="${mascota.id}"
                                data-nombre="${mascota.nombre}">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Función para obtener la ruta del avatar según la especie
function getAvatarByType(especie) {
    especie = especie.toLowerCase().trim();
    const especieMap = {
        'gato': 'gato',
        'cat': 'gato',
        'perro': 'perro',
        'dog': 'perro',
        'ave': 'ave',
        'bird': 'ave',
        'pajaro': 'ave'
    };
    const especieNormalizada = especieMap[especie] || 'otro';
    return `recursos/avatares/${especieNormalizada}.jpg`;
}

// Función para agregar el manejador de eliminar a un botón
function addDeleteHandler(button) {
    button.addEventListener('click', async function() {
        const mascotaId = this.dataset.id;
        const mascotaNombre = this.dataset.nombre;
        const buttonElement = this;

        // Mostrar confirmación
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar a ${mascotaNombre}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const formData = new FormData();
                formData.append('id', mascotaId);

                const response = await fetch('/controlador/mascotacontroller.php?action=eliminar', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al eliminar la mascota');
                }

                if (data.success) {
                    // Mostrar mensaje de éxito
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'La mascota ha sido eliminada correctamente.',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Eliminar la tarjeta de la mascota del DOM
                    const cardElement = buttonElement.closest('.col');
                    if (cardElement) {
                        cardElement.remove();
                    }

                    // Verificar si no quedan mascotas
                    const remainingPets = document.querySelectorAll('.pet-card').length;
                    if (remainingPets === 0) {
                        const container = document.querySelector('.container.mt-4');
                        if (container) {
                            container.innerHTML = `
                                <div class="alert alert-info">
                                    No tienes mascotas registradas. ¡Agrega una!
                                </div>
                            `;
                        }
                    }
                } else {
                    throw new Error(data.message || 'Error al eliminar la mascota');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo eliminar la mascota. Por favor, intente nuevamente.'
                });
            }
        }
    });
}

// Reiniciar validación al cerrar el modal
document.getElementById('addPetModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('formAgregarMascota');
    form.classList.remove('was-validated');
    form.reset();
});
</script>
