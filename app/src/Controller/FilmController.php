<?php

declare(strict_types=1);

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Model/Film.php';
require_once __DIR__ . '/../Model/Diffusion.php';

class FilmController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function index(): void
    {
        $films = Film::all($this->pdo);
        $title = 'Films';
        ob_start();
    require __DIR__ . '/../views/films/index.php';
        $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film = new Film([
                'nom' => $_POST['nom'] ?? '',
                'date_sortie' => $_POST['date_sortie'] ?: null,
                'genre' => $_POST['genre'] ?: null,
                'auteur' => $_POST['auteur'] ?: null,
            ]);
            $film->save($this->pdo);
            header('Location: /');
            exit;
        }

        $title = 'Ajouter un film';
        ob_start();
    require __DIR__ . '/../views/films/create.php';
        $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
    }

    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $film = Film::find($this->pdo, $id);
        if (!$film) {
            http_response_code(404);
            echo '<h1>Film non trouvé</h1>';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $film->nom = $_POST['nom'] ?? $film->nom;
            $film->date_sortie = $_POST['date_sortie'] ?: null;
            $film->genre = $_POST['genre'] ?: null;
            $film->auteur = $_POST['auteur'] ?: null;
            $film->save($this->pdo);
            header('Location: /');
            exit;
        }

        $title = 'Modifier le film';
        ob_start();
    require __DIR__ . '/../views/films/edit.php';
        $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
    }

    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $film = Film::find($this->pdo, $id);
        if (!$film) {
            http_response_code(404);
            echo '<h1>Film non trouvé</h1>';
            return;
        }

        // handle adding a diffusion via POST from the film detail page
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date_diffusion'])) {
            $raw = trim((string)($_POST['date_diffusion'] ?? ''));
            if ($raw !== '') {
                // convert HTML datetime-local (YYYY-MM-DDTHH:MM) to MySQL DATETIME
                $date = $raw;
                if (strpos($raw, 'T') !== false) {
                    $date = str_replace('T', ' ', $raw);
                    if (strlen($date) <= 16) {
                        $date .= ':00';
                    }
                }
                Diffusion::create($this->pdo, $film->id, $date);
            }
            header('Location: /film/show?id=' . $film->id);
            exit;
        }

        $diffusions = Diffusion::allByFilm($this->pdo, $film->id);

        $title = 'Détail du film';
        ob_start();
    require __DIR__ . '/../views/films/show.php';
        $content = ob_get_clean();
    require __DIR__ . '/../views/layout.php';
    }

    public function delete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Film::delete($this->pdo, $id);
        }
        header('Location: /');
        exit;
    }
}
