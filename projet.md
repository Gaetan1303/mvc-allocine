# Point projet — mvc-allocine

Date : 10 novembre 2025

Ce document fait l'état des lieux du projet, ce qui a été réalisé, ce qui manque par rapport au `Readme.md`, et les prochaines actions recommandées avec commandes utiles pour reproduire le travail localement.

## 1) État général

- Application PHP minimale en architecture MVC disponible sous `app/`.
- Serveur de développement : testé avec le serveur PHP intégré (`php -S`) sur `http://localhost:8080`.
- Base de données : fallback SQLite utilisé automatiquement (fichier `app/var/database.sqlite`). Le code supporte MySQL via variables d'environnement.
- Docker : fichiers `compose.yaml` et `web/Dockerfile` ajoutés mais non testés end-to-end (démon Docker non démarré lors des essais).

## 2) Ce qui fonctionne aujourd'hui

- Front-controller : `app/public/index.php` (router minimal).
- Modèle `Film` : `app/src/Model/Film.php` (méthodes `all`, `find`, `save`, `delete`).
- Contrôleur `FilmController` : `app/src/Controller/FilmController.php` (actions `index`, `create`, `edit`, `show`, `delete`).
- Vues : `app/src/views/layout.php` et `app/views/films/{index,create,edit,show}.php`.
- Connexion DB : `app/src/Database.php` (utilise `DB_DRIVER` pour MySQL, sinon SQLite). Schéma minimal pour `film` et `diffusion` créé automatiquement pour SQLite.
- Script de test DB : `app/scripts/test_db.php`.
- Flux CRUD Film testé : création via POST, affichage sur `/`, suppression et édition endpoints implémentés.

## 3) Ce qui manque / différences avec le `Readme.md`

Priorité haute
- CRUD complet des `diffusions` (modèle, contrôleur, vues) — actuellement la table `diffusion` est créée, mais il n'y a pas d'interface ni contrôleur.
- Activation explicite des `foreign_keys` pour SQLite (exécuter `PRAGMA foreign_keys = ON;` après connexion) : nécessaire pour garantir le comportement `ON DELETE CASCADE` attendu.

Fonctionnalités attendues mais non encore implémentées
- Recherche de film (filtrage par nom, genre, auteur, date).
- Page détail d'un film listant ses diffusions (liée au point CRUD diffusions).
- Validation côté serveur et messages flash pour formulaires (UX).
- Tests automatisés (PHPUnit/unitaires + tests d'intégration HTTP).

Ops / infra
- Tests Docker/MySQL end-to-end non réalisés (démon Docker non démarré sur la machine utilisée pour les essais).
- Script de migration / seed SQL pour MySQL manquant.

## 4) Actions recommandées (priorisées)

1. Correction technique (critique) — activer `PRAGMA foreign_keys = ON` pour SQLite juste après la connexion dans `app/src/Database.php`. (~10–20 min)
2. Implémenter CRUD `Diffusion` + lier la liste des diffusions au `FilmController::show`. (~60–120 min)
3. Ajouter validations server-side et messages de retour (flash). (~30–60 min)
4. Préparer un script de migration SQL pour MySQL et `.env.example`, puis tester via Docker Compose. (~60–120 min, dépend de Docker)
5. Ajouter tests automatisés (PHPUnit + petits scripts cURL). (~2–4 heures)

Je peux appliquer (1) immédiatement si vous me donnez l'accord. Ensuite je recommande d'implémenter (2).

## 5) Comment lancer le projet localement (quick-start)

1. Serveur PHP intégré (développement) — recommandé pour développement sans Docker :

```bash
# depuis la racine du dépôt
php -S localhost:8080 -t app/public
```

Puis ouvrir : http://localhost:8080

2. Tester la connexion DB (script livré) :

```bash
DB_DRIVER=sqlite php app/scripts/test_db.php
```

3. Créer un film via curl (exemple) :

```bash
curl -X POST http://localhost:8080/film/create \
  -d "nom=MonFilm&date_sortie=2025-11-10&genre=Drame&auteur=Moi"
```

4. Docker (si vous préférez conteneurs) — build & up (nécessite démon Docker fonctionnel) :

```bash
docker compose -f compose.yaml up --build -d
```

Ports exposés par `compose.yaml` (configuration actuelle) :
- Application web (Apache/PHP) : 8080 -> 80
- phpMyAdmin : 8081 -> 80
- MariaDB : 3306 -> 3306

Si vous activez Docker, il faudra adapter `app/src/Database.php` pour utiliser les variables d'environnement MySQL (DB_DRIVER=mysql, DB_HOST=bdd, DB_NAME, DB_USER, DB_PASS) ou laisser le code tel quel (il lit les variables si définies).

## 6) Tests effectués (rapide)

- Vérification syntaxe PHP : `php -l` sur fichiers modifiés — OK.
- Lancement serveur intégré et test GET / — 200 OK.
- Exécution de `app/scripts/test_db.php` (SQLite) — OK, tables `film` et `diffusion` présentes.
- POST `/film/create` via curl — 302 redirection et film présent dans la liste (vérifié).

## 7) Prochaines étapes immédiates — proposition

Donnez-moi votre accord pour que j'applique la correction SQLite `PRAGMA foreign_keys = ON` et j'implémente ensuite le CRUD `Diffusion` (modèle, contrôleur, vues) afin que le projet respecte pleinement le cahier des charges du README.

---

Fichier créé automatiquement par l'agent d'assistance. Pour toute modification, dites exactement ce que vous souhaitez : je peux implémenter les éléments listés, écrire des tests, ou préparer l'intégration Docker/MySQL.
