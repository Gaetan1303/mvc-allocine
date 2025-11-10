<?php

declare(strict_types=1);

class DiffusionController
{
    public function index(): void
    {
        $title = 'Diffusions';
        ob_start();
        require __DIR__ . '/../views/diffusions/index.php';
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }
}
