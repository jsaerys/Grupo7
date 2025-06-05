<?php
class Product extends Model {
    protected $table = 'productos';

    public function __construct() {
        parent::__construct();
    }

    public function getFeatured($limit = 4) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE activo = 1 AND stock > 0 
                 ORDER BY RAND() 
                 LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $data = $this->sanitize($data);
        
        $query = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) 
                 VALUES (:nombre, :descripcion, :precio, :stock, :imagen)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':imagen', $data['imagen']);

        return $stmt->execute();
    }

    public function update($id, $data) {
        $data = $this->sanitize($data);
        
        $query = "UPDATE productos SET 
                 nombre = :nombre,
                 descripcion = :descripcion,
                 precio = :precio,
                 stock = :stock";
        
        if (isset($data['imagen']) && !empty($data['imagen'])) {
            $query .= ", imagen = :imagen";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':id', $id);

        if (isset($data['imagen']) && !empty($data['imagen'])) {
            $stmt->bindParam(':imagen', $data['imagen']);
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updateStock($id, $quantity) {
        $query = "UPDATE {$this->table} 
                 SET stock = stock - :quantity 
                 WHERE id = :id AND stock >= :quantity";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function search($term) {
        $term = "%{$term}%";
        $query = "SELECT * FROM {$this->table} 
                 WHERE (nombre LIKE :term OR descripcion LIKE :term) 
                 AND activo = 1 AND stock > 0";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':term', $term);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getInStock() {
        $query = "SELECT * FROM productos WHERE stock > 0";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLowStock($threshold = 5) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE stock <= :threshold 
                 ORDER BY stock ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($category) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE categoria = :category AND activo = 1 AND stock > 0";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
} 