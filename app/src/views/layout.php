<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AlloCiné' ?></title>
    <link rel="stylesheet" href="/styles/layout.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="/"> AlloCiné</a></li>
            <li><a href="/">Films</a></li>
            <li><a href="/diffusions">Diffusions</a></li>
            <li><a href="/film/create">Ajouter un film</a></li>
        </ul>
    </nav>
    <div class="container">
        <?= $content ?>
    </div>
</body>
</html>