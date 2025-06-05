<?php
class CartController extends Controller {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    public function index() {
        $this->requireLogin();
        
        $cartItems = $this->cartModel->getCartByUser($_SESSION['user_id']);
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        $this->render('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function add() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;
            
            if ($productId) {
                // Verificar stock
                $product = $this->productModel->findById($productId);
                if (!$product || $product['stock'] < $quantity) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'No hay suficiente stock disponible'
                    ]);
                    exit();
                }

                if ($this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity)) {
                    $cartCount = $this->cartModel->getCartCount($_SESSION['user_id']);
                    echo json_encode([
                        'success' => true,
                        'cartCount' => $cartCount
                    ]);
                    exit();
                }
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }

    public function update() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? null;
            
            if ($productId && $quantity !== null) {
                // Verificar stock
                $product = $this->productModel->findById($productId);
                if (!$product || $product['stock'] < $quantity) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'No hay suficiente stock disponible'
                    ]);
                    exit();
                }

                $cartItem = $this->cartModel->getCartItem($_SESSION['user_id'], $productId);
                if ($cartItem && $this->cartModel->updateQuantity($cartItem['id'], $quantity)) {
                    $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
                    echo json_encode([
                        'success' => true,
                        'total' => $total
                    ]);
                    exit();
                }
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }

    public function remove() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            
            if ($productId && $this->cartModel->removeFromCart($_SESSION['user_id'], $productId)) {
                $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
                $cartCount = $this->cartModel->getCartCount($_SESSION['user_id']);
                echo json_encode([
                    'success' => true,
                    'total' => $total,
                    'cartCount' => $cartCount
                ]);
                exit();
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }

    public function clear() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->cartModel->clearCart($_SESSION['user_id'])) {
                echo json_encode(['success' => true]);
                exit();
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }

    public function checkout() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar stock antes de proceder
            $invalidItems = $this->cartModel->validateStock($_SESSION['user_id']);
            
            if (!empty($invalidItems)) {
                $this->render('cart/index', [
                    'error' => 'Algunos productos no tienen suficiente stock',
                    'cartItems' => $this->cartModel->getCartByUser($_SESSION['user_id']),
                    'total' => $this->cartModel->getCartTotal($_SESSION['user_id'])
                ]);
                return;
            }

            // Procesar el pedido
            $cartItems = $this->cartModel->getCartByUser($_SESSION['user_id']);
            foreach ($cartItems as $item) {
                // Actualizar stock
                $this->productModel->updateStock($item['producto_id'], -$item['cantidad']);
            }

            // Limpiar carrito
            $this->cartModel->clearCart($_SESSION['user_id']);

            // Redirigir con mensaje de éxito
            header("Location: /proyectos/colaborativo/Grupo7/index.php?page=cart&success=1");
            exit();
        }

        // Si no es POST, redirigir al carrito
        header("Location: /proyectos/colaborativo/Grupo7/index.php?page=cart");
        exit();
    }
} 