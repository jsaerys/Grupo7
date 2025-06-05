<?php
class AdminController extends Controller {
    private $userModel;
    private $productModel;

    public function __construct() {
        $this->userModel = new User();
        $this->productModel = new Product();
    }

    public function index() {
        $this->requireAdmin();
        $this->render('admin/dashboard');
    }

    public function users() {
        $this->requireAdmin();
        $users = $this->userModel->findAll();
        $this->render('admin/users', ['users' => $users]);
    }

    public function products() {
        $this->requireAdmin();
        $products = $this->productModel->findAll();
        $this->render('admin/products', ['products' => $products]);
    }
} 