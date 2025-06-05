<?php
class HomeController extends Controller {
    private $productModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
    }

    public function index() {
        // Obtener productos destacados
        $featuredProducts = $this->productModel->getFeatured(4);
        
        // Datos para la vista
        $data = [
            'pageTitle' => 'Inicio',
            'featuredProducts' => $featuredProducts
        ];

        $this->render('home/index', $data);
    }
} 