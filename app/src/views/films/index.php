<?php
// $films is available, each is instance of Film
?>
<div class="card">
    <h1>Liste des films</h1>
    <p><a class="btn" href="/film/create">Ajouter un film</a></p>

    <?php if (empty($films)): ?>
        <div class="alert alert-info">Aucun film pour le moment.</div>
    <?php else: ?>
        <?php foreach ($films as $f): ?>
            <div class="card" style="margin-bottom:0.5rem;">
                <h3><?= htmlspecialchars($f->nom) ?> <small>(<?= htmlspecialchars($f->date_sortie ?? '') ?>)</small></h3>
                <p>Genre: <?= htmlspecialchars($f->genre ?? '') ?> — Auteur: <?= htmlspecialchars($f->auteur ?? '') ?></p>
                <p>
                    <a class="btn" href="/film/show?id=<?= $f->id ?>">Voir</a>
                    <a class="btn btn-secondary" href="/film/edit?id=<?= $f->id ?>">Modifier</a>
                    <form method="post" action="/film/delete" style="display:inline-block;margin-left:0.5rem;">
                        <input type="hidden" name="id" value="<?= $f->id ?>">
                        <button class="btn btn-danger" type="submit" onclick="return confirm('Supprimer ce film ?')">Supprimer</button>
                    </form>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
