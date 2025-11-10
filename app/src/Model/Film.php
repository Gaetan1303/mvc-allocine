<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class Film
{
    public int $id;
    public string $nom;
    public ?string $date_sortie;
    public ?string $genre;
    public ?string $auteur;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->nom = $data['nom'] ?? '';
        $this->date_sortie = $data['date_sortie'] ?? null;
        $this->genre = $data['genre'] ?? null;
        $this->auteur = $data['auteur'] ?? null;
    }

    public static function all(PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM film ORDER BY id DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new self($r), $rows);
    }

    public static function find(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare('SELECT * FROM film WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public function save(PDO $pdo): void
    {
        if ($this->id > 0) {
            $stmt = $pdo->prepare('UPDATE film SET nom = :nom, date_sortie = :date_sortie, genre = :genre, auteur = :auteur WHERE id = :id');
            $stmt->execute([
                ':nom' => $this->nom,
                ':date_sortie' => $this->date_sortie,
                ':genre' => $this->genre,
                ':auteur' => $this->auteur,
                ':id' => $this->id,
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO film (nom, date_sortie, genre, auteur) VALUES (:nom, :date_sortie, :genre, :auteur)');
            $stmt->execute([
                ':nom' => $this->nom,
                ':date_sortie' => $this->date_sortie,
                ':genre' => $this->genre,
                ':auteur' => $this->auteur,
            ]);
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    public static function delete(PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare('DELETE FROM film WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
