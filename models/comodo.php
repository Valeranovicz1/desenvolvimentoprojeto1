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

    public function update($id, $nome) {
        $stmt = $this->db->prepare("UPDATE comodo SET Nome = ? WHERE Id = ?");
        return $stmt->execute([$nome, $id]);
    }

    public function getAllWithAvgMeasurements($residencia_id) {
        

        $sql = "SELECT  c.Id, c.Nome, AVG(m.Nivel_Sinal) AS avg_sinal, AVG(m.Velocidade) AS avg_velocidade, AVG(m.Interferencia) AS avg_interferenciaROM comodo cLEFT JOIN medicoes m ON c.Id = m.IdComodo WHERE c.IdResidencia = ? GROUP BY c.Id, c.Nome ORDER BY c.Nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$residencia_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>  