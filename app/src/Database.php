<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Retourne une instance PDO configurée selon les variables d'environnement.
     * Si DB_DRIVER=mysql, utilise MySQL. Sinon fallback sur SQLite local (app/var/database.sqlite).
     *
     * @return PDO
     * @throws RuntimeException en cas d'erreur de connexion
     */
    public static function connect(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $driver = getenv('DB_DRIVER') ?: 'sqlite';

        if ($driver === 'mysql') {
            $host = getenv('DB_HOST') ?: 'bdd';
            $dbname = getenv('DB_NAME') ?: 'allocine';
            $username = getenv('DB_USER') ?: 'allocine_user';
            $password = getenv('DB_PASS') ?: 'allocine_pass';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new RuntimeException('Erreur de connexion MySQL : ' . $e->getMessage());
            }

            return self::$pdo;
        }

        // SQLite fallback
        $dir = __DIR__ . '/../var';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $path = $dir . '/database.sqlite';
        $dsn = 'sqlite:' . $path;

        try {
            self::$pdo = new PDO($dsn);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Create minimal schema if needed
            $initSql = <<<'SQL'
CREATE TABLE IF NOT EXISTS film (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nom TEXT NOT NULL,
  date_sortie TEXT,
  genre TEXT,
  auteur TEXT
);

CREATE TABLE IF NOT EXISTS diffusion (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  film_id INTEGER NOT NULL,
  date_diffusion TEXT,
  FOREIGN KEY(film_id) REFERENCES film(id) ON DELETE CASCADE
);
SQL;
            self::$pdo->exec($initSql);
        } catch (PDOException $e) {
            throw new RuntimeException('Erreur de connexion SQLite : ' . $e->getMessage());
        }

        return self::$pdo;
    }
}