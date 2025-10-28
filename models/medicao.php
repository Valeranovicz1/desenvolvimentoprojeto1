<?php

require_once 'Database.php';

class Medicao {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllByComodo($comodo_id) {
        $stmt = $this->db->prepare("SELECT * FROM medicoes WHERE idComodo = ? ORDER BY id");
        $stmt->execute([$comodo_id]);
        return $stmt->fetchAll();
    }
    
    public function create($nivel_sinal, $velocidade, $interferencia, $comodo_id) {
        $stmt = $this->db->prepare("INSERT INTO medicoes (nivel_sinal, velocidade, interferencia, idComodo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nivel_sinal, $velocidade, $interferencia, $comodo_id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM medicoes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>