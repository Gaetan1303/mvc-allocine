<?php
// $film is available
?>
<div class="card">
    <h1><?= htmlspecialchars($film->nom) ?></h1>
    <p><strong>Date de sortie:</strong> <?= htmlspecialchars($film->date_sortie ?? '—') ?></p>
    <p><strong>Genre:</strong> <?= htmlspecialchars($film->genre ?? '—') ?></p>
    <p><strong>Auteur:</strong> <?= htmlspecialchars($film->auteur ?? '—') ?></p>
    
    <hr />
    <h2>Diffusions</h2>
    <?php if (!empty($diffusions)): ?>
        <ul>
            <?php foreach ($diffusions as $d): ?>
                <li><?= htmlspecialchars($d->date_diffusion ?? '—') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune diffusion enregistrée pour ce film.</p>
    <?php endif; ?>

    <form method="post" style="margin-top:1rem;">
        <label for="date_diffusion">Ajouter une diffusion :</label>
        <input type="datetime-local" id="date_diffusion" name="date_diffusion" required>
        <button class="btn" type="submit">Ajouter</button>
    </form>
    <p>
        <a class="btn" href="/film/edit?id=<?= $film->id ?>">Modifier</a>
        <a class="btn btn-secondary" href="/">Retour</a>
    </p>
</div>
