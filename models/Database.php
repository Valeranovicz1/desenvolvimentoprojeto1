<?php

class Database {
    private static $pdo;

    public static function getConnection() {
        if (!self::$pdo) {
            try {
        
                $dsn = 'mysql:host=localhost;dbname=gerenciador_de_sinal;charset=utf8';
                
                self::$pdo = new PDO($dsn, 'root', '');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}