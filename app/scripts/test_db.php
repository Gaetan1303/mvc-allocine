<?php

require __DIR__ . '/../src/Database.php';

try {
    $pdo = Database::connect();
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table';")->fetchAll(PDO::FETCH_COLUMN);
    echo "OK - connexion PDO réussie. Tables existantes: " . implode(', ', $tables) . "\n";
    exit(0);
} catch (Throwable $e) {
    echo "ERREUR - ", $e->getMessage(), "\n";
    exit(1);
}
