<?php

require_once 'Database.php';

class Comodo {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllByResidencia($residencia_id) {
        $stmt = $this->db->prepare("SELECT * FROM comodo WHERE idResidencia = ? ORDER BY nome");
        $stmt->execute([$residencia_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM comodo WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($nome, $residencia_id) {
        $stmt = $this->db->prepare("INSERT INTO comodo (nome, idResidencia) VALUES (?, ?)");
        return $stmt->execute([$nome, $residencia_id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comodo WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>