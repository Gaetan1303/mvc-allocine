<?php
// $film is available
?>
<div class="card">
    <h1>Modifier le film</h1>
    <form method="post">
        <p><input name="nom" value="<?= htmlspecialchars($film->nom) ?>" required></p>
        <p><input name="date_sortie" value="<?= htmlspecialchars($film->date_sortie ?? '') ?>" placeholder="YYYY-MM-DD"></p>
        <p><input name="genre" value="<?= htmlspecialchars($film->genre ?? '') ?>" placeholder="Genre"></p>
        <p><input name="auteur" value="<?= htmlspecialchars($film->auteur ?? '') ?>" placeholder="Auteur"></p>
        <p><button class="btn" type="submit">Enregistrer</button></p>
    </form>
</div>
