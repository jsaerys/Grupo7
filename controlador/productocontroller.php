<?php
require_once '../modelo/producto.php';

class ProductoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }

    public function listar() {
        return $this->modelo->obtenerTodos();
    }

    public function obtener($id) {
        return $this->modelo->obtenerPorId($id);
    }

    public function crear($datos) {
        // Manejo de la imagen
        $imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $nombre_temporal = $_FILES['imagen']['tmp_name'];
            $nombre_imagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $ruta_destino = '../assets/img/products/' . $nombre_imagen;
            
            if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
                $imagen = $nombre_imagen;
            }
        }

        $datos['imagen'] = $imagen;
        return $this->modelo->crear($datos);
    }

    public function actualizar($datos) {
        // Manejo de la imagen si se sube una nueva
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $nombre_temporal = $_FILES['imagen']['tmp_name'];
            $nombre_imagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $ruta_destino = '../assets/img/products/' . $nombre_imagen;
            
            if (move_uploaded_file($nombre_temporal, $ruta_destino)) {
                // Si hay una imagen anterior, la eliminamos
                if (!empty($datos['imagen_actual'])) {
                    $ruta_imagen_anterior = '../assets/img/products/' . $datos['imagen_actual'];
                    if (file_exists($ruta_imagen_anterior)) {
                        unlink($ruta_imagen_anterior);
                    }
                }
                $datos['imagen'] = $nombre_imagen;
            }
        }

        return $this->modelo->actualizar($datos);
    }

    public function eliminar($id) {
        // Obtener información del producto para eliminar la imagen
        $producto = $this->modelo->obtenerPorId($id);
        if ($producto && !empty($producto['imagen'])) {
            $ruta_imagen = '../assets/img/products/' . $producto['imagen'];
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }
        
        return $this->modelo->eliminar($id);
    }

    public function buscar($termino) {
        return $this->modelo->buscar($termino);
    }
}

// Manejo de las peticiones
if (isset($_GET['action'])) {
    $controller = new ProductoController();
    header('Content-Type: application/json');
    
    try {
        switch ($_GET['action']) {
            case 'listar':
                $productos = $controller->listar();
                echo json_encode($productos);
                break;
                
            case 'obtener':
                if (isset($_GET['id'])) {
                    $producto = $controller->obtener($_GET['id']);
                    echo json_encode($producto);
                } else {
                    throw new Exception('ID no proporcionado');
                }
                break;
                
            case 'crear':
                $resultado = $controller->crear($_POST);
                echo json_encode(['success' => $resultado]);
                break;
                
            case 'actualizar':
                if (isset($_POST['id'])) {
                    $resultado = $controller->actualizar($_POST);
                    echo json_encode(['success' => $resultado]);
                } else {
                    throw new Exception('ID no proporcionado');
                }
                break;
                
            case 'eliminar':
                if (isset($_GET['id'])) {
                    $resultado = $controller->eliminar($_GET['id']);
                    echo json_encode(['success' => $resultado]);
                } else {
                    throw new Exception('ID no proporcionado');
                }
                break;
                
            case 'buscar':
                if (isset($_GET['termino'])) {
                    $productos = $controller->buscar($_GET['termino']);
                    echo json_encode($productos);
                } else {
                    throw new Exception('Término de búsqueda no proporcionado');
                }
                break;
                
            default:
                throw new Exception('Acción no válida');
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} 