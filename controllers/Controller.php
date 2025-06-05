<?php
class Controller {
    protected $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    protected function render($view, $data = []) {
        // Extraer variables para que estén disponibles en la vista
        extract($data);
        
        // Incluir la vista
        $viewPath = BASE_PATH . '/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new Exception("Vista no encontrada: " . $view);
        }
    }

    protected function redirect($page, $params = []) {
        $url = BASE_URL . '/index.php?page=' . $page;
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= "&{$key}={$value}";
            }
        }
        header('Location: ' . $url);
        exit();
    }

    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth', ['action' => 'login']);
        }
    }

    protected function requireAdmin() {
        $this->requireLogin();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('HTTP/1.0 403 Forbidden');
            include(BASE_PATH . '/views/403.php');
            exit();
        }
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'role' => $_SESSION['user_role']
            ];
        }
        return null;
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
} 