<?php
// Simple front-controller for local testing (MVC-like)
require __DIR__ . '/../src/Controller/FilmController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// normalize trailing slash
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}

$controller = new FilmController();

switch ($path) {
    case '/':
        $controller->index();
        break;
    case '/diffusions':
        // minimal route handled by a dedicated controller
        require __DIR__ . '/../src/Controller/DiffusionController.php';
        $diffCtrl = new DiffusionController();
        $diffCtrl->index();
        break;
    case '/film/create':
        $controller->create();
        break;
    case '/film/edit':
        $controller->edit();
        break;
    case '/film/show':
        $controller->show();
        break;
    case '/film/delete':
        // expects POST
        $controller->delete();
        break;
    default:
        http_response_code(404);
        echo '<h1>404 — Page non trouvée</h1>';
        break;
}
