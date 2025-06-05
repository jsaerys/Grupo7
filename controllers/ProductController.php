<?php
class ProductController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        $products = $this->productModel->findAll();
        $this->render('products/index', ['products' => $products]);
    }

    public function view($id = null) {
        if (!$id) {
            $id = $_GET['id'] ?? null;
        }

        if (!$id) {
            header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products");
            exit();
        }

        $product = $this->productModel->findById($id);
        
        if (!$product) {
            header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products");
            exit();
        }

        $this->render('products/view', ['product' => $product]);
    }

    public function create() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'stock' => $_POST['stock'] ?? 0,
                'imagen' => ''
            ];

            // Manejo de la imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $uploadDir = 'assets/img/products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid() . '_' . basename($_FILES['imagen']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $productData['imagen'] = $fileName;
                }
            }

            if ($this->productModel->create($productData)) {
                header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products&success=1");
                exit();
            } else {
                $this->render('products/create', [
                    'error' => 'Error al crear el producto',
                    'data' => $productData
                ]);
            }
        }

        $this->render('products/create');
    }

    public function edit() {
        $this->requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products");
            exit();
        }

        $product = $this->productModel->findById($id);
        if (!$product) {
            header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productData = [
                'nombre' => $_POST['nombre'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'precio' => $_POST['precio'] ?? 0,
                'stock' => $_POST['stock'] ?? 0
            ];

            // Manejo de la imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $uploadDir = 'assets/img/products/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = uniqid() . '_' . basename($_FILES['imagen']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $productData['imagen'] = $fileName;
                    
                    // Eliminar imagen anterior
                    if (!empty($product['imagen'])) {
                        $oldFile = $uploadDir . $product['imagen'];
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                }
            }

            if ($this->productModel->update($id, $productData)) {
                header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products&success=2");
                exit();
            } else {
                $this->render('products/edit', [
                    'error' => 'Error al actualizar el producto',
                    'product' => $product
                ]);
            }
        }

        $this->render('products/edit', ['product' => $product]);
    }

    public function delete() {
        $this->requireAdmin();
        
        $id = $_GET['id'] ?? null;
        if ($id) {
            $product = $this->productModel->findById($id);
            if ($product && !empty($product['imagen'])) {
                $imagePath = 'assets/img/products/' . $product['imagen'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            if ($this->productModel->delete($id)) {
                header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products&success=3");
                exit();
            }
        }
        
        header("Location: /proyectos/colaborativo/Grupo7/index.php?page=products&error=1");
        exit();
    }
} 