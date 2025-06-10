<?php
require_once '../modelo/Producto.php';
require_once '../config/database.php';

class ProductoController {
    private $producto;
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->producto = new Producto($this->conn);
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
        
        switch ($action) {
            case 'create':
                $this->createProducto();
                break;
            case 'update':
                $this->updateProducto();
                break;
            case 'get':
                $this->getProducto();
                break;
            case 'getAll':
                $this->getAllProductos();
                break;
            case 'toggleStatus':
                $this->toggleStatus();
                break;
            case 'search':
                $this->searchProductos();
                break;
            default:
                $this->sendResponse(false, 'Acción no válida');
        }
    }

    private function createProducto() {
        try {
            $data = $this->getProductData();
            
            // Manejar la carga de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $data['imagen'] = $this->handleImageUpload($_FILES['imagen']);
            }
            
            if ($this->producto->create($data)) {
                $this->sendResponse(true, 'Producto creado exitosamente');
            } else {
                $this->sendResponse(false, 'Error al crear el producto');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function updateProducto() {
        try {
            $data = $this->getProductData();
            
            // Manejar la carga de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $data['imagen'] = $this->handleImageUpload($_FILES['imagen']);
            }
            
            if ($this->producto->update($data)) {
                $this->sendResponse(true, 'Producto actualizado exitosamente');
            } else {
                $this->sendResponse(false, 'Error al actualizar el producto');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function getProducto() {
        try {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception('ID de producto no proporcionado');
            }
            
            $producto = $this->producto->getById($id);
            if ($producto) {
                $this->sendResponse(true, 'Producto encontrado', ['producto' => $producto]);
            } else {
                $this->sendResponse(false, 'Producto no encontrado');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function getAllProductos() {
        try {
            error_log('Iniciando getAllProductos...');
            $productos = $this->producto->getAll();
            error_log('Productos obtenidos: ' . print_r($productos, true));
            
            if ($productos === false) {
                error_log('Error: No se pudieron obtener los productos');
                throw new Exception('Error al obtener los productos');
            }
            
            error_log('Enviando respuesta exitosa');
            $this->sendResponse(true, 'Productos obtenidos exitosamente', ['productos' => $productos]);
        } catch (Exception $e) {
            error_log('Error en getAllProductos: ' . $e->getMessage());
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function toggleStatus() {
        try {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $activo = isset($_POST['activo']) ? $_POST['activo'] : null;
            
            if ($id === null || $activo === null) {
                throw new Exception('Datos incompletos');
            }
            
            if ($this->producto->toggleStatus($id, $activo)) {
                $this->sendResponse(true, 'Estado actualizado exitosamente');
            } else {
                $this->sendResponse(false, 'Error al actualizar el estado');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function searchProductos() {
        try {
            $term = isset($_GET['term']) ? $_GET['term'] : '';
            $productos = $this->producto->search($term);
            $this->sendResponse(true, 'Búsqueda completada', ['productos' => $productos]);
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function getProductData() {
        $data = [
            'nombre' => $_POST['nombre'] ?? null,
            'descripcion' => $_POST['descripcion'] ?? null,
            'precio' => $_POST['precio'] ?? null,
            'stock' => $_POST['stock'] ?? null,
            'categoria' => $_POST['categoria'] ?? null
        ];

        if (isset($_POST['id'])) {
            $data['id'] = $_POST['id'];
        }

        // Validar datos requeridos
        foreach ($data as $key => $value) {
            if ($value === null && $key !== 'id') {
                throw new Exception("El campo {$key} es requerido");
            }
        }

        return $data;
    }

    private function handleImageUpload($file) {
        $targetDir = "../assets/productos/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validar tipo de archivo
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            throw new Exception('Solo se permiten archivos JPG, JPEG, PNG & GIF');
        }

        // Validar tamaño (5MB máximo)
        if ($file['size'] > 5000000) {
            throw new Exception('El archivo es demasiado grande (máximo 5MB)');
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        } else {
            throw new Exception('Error al subir la imagen');
        }
    }

    private function sendResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode(array_merge(
            ['success' => $success, 'message' => $message],
            $data
        ));
        exit;
    }
}

// Iniciar el controlador
$controller = new ProductoController();
$controller->handleRequest(); 