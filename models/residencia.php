<?php

require_once __DIR__ . '/Database.php'; 

class Residencia {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM residencia ORDER BY nome");
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM residencia WHERE id = ?");
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nome, $endereco) {
        $stmt = $this->db->prepare("INSERT INTO residencia (nome, endereco) VALUES (?, ?)");
        return $stmt->execute([$nome, $endereco]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM residencia WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>