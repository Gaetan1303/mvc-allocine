<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';

class Diffusion
{
    public int $id;
    public int $film_id;
    public ?string $date_diffusion;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : 0;
        $this->film_id = isset($data['film_id']) ? (int)$data['film_id'] : 0;
        $this->date_diffusion = $data['date_diffusion'] ?? null;
    }

    public static function allByFilm(PDO $pdo, int $filmId): array
    {
        $stmt = $pdo->prepare('SELECT * FROM diffusion WHERE film_id = :film_id ORDER BY date_diffusion DESC');
        $stmt->execute([':film_id' => $filmId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new self($r), $rows);
    }

    public static function create(PDO $pdo, int $filmId, ?string $dateDiffusion): self
    {
        $stmt = $pdo->prepare('INSERT INTO diffusion (film_id, date_diffusion) VALUES (:film_id, :date_diffusion)');
        $stmt->execute([
            ':film_id' => $filmId,
            ':date_diffusion' => $dateDiffusion,
        ]);
        $id = (int)$pdo->lastInsertId();
        return new self(['id' => $id, 'film_id' => $filmId, 'date_diffusion' => $dateDiffusion]);
    }

    public static function delete(PDO $pdo, int $id): void
    {
        $stmt = $pdo->prepare('DELETE FROM diffusion WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
