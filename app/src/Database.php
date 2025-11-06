<?php

class Database {
    private static ?PDO $pdo = null;
    
    public static function connect(): PDO {
        if (self::$pdo === null) {
            $host = 'bdd';
            $dbname = 'allocine';
            $username = 'allocine_user';
            $password = 'allocine_pass';
            
            try {
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        
        return self::$pdo;
    }
}