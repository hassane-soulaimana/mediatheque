<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
</head>

<body>

    <h2>Recherche</h2>
    <form method="GET" action="">
        <!-- Ajout de Type-->
        <input type="text" name="q" placeholder="Titre ou mot-clé..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <select name="type">
            <option value="">Type</option>
            <option value="game" <?= (($_GET['type'] ?? '') == 'game') ? 'selected' : '' ?>>Jeu</option>
            <option value="movie" <?= (($_GET['type'] ?? '') == 'movie') ? 'selected' : '' ?>>Film</option>
            <option value="book" <?= (($_GET['type'] ?? '') == 'book') ? 'selected' : '' ?>>Livre</option>
        </select>
        <!-- Ajoute de genre-->
        <select name="genre">
            <option value="">Genre</option>
            <option value="Action" <?= (($_GET['genre'] ?? '') == 'Action') ? 'selected' : '' ?>>Action</option>
            <option value="Aventure" <?= (($_GET['genre'] ?? '') == 'Aventure') ? 'selected' : '' ?>>Aventure</option>
            <option value="Drame" <?= (($_GET['genre'] ?? '') == 'Drame') ? 'selected' : '' ?>>Drame</option>
            <option value="Thriller" <?= (($_GET['genre'] ?? '') == 'Thriller') ? 'selected' : '' ?>>Thriller</option>
            <option value="Comédie" <?= (($_GET['genre'] ?? '') == 'Comédie') ? 'selected' : '' ?>>Comédie</option>
            <option value="Fantastique" <?= (($_GET['genre'] ?? '') == 'Fantastique') ? 'selected' : '' ?>>Fantastique</option>

        </select>
        <select name="dispo">
            <option value="">Disponibilité</option>
            <option value="1" <?= (($_GET['dispo'] ?? '') == '1') ? 'selected' : '' ?>>Disponible</option>
            <option value="0" <?= (($_GET['dispo'] ?? '') == '0') ? 'selected' : '' ?>>Emprunté</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>
    <h1><?= htmlspecialchars($title) ?></h1>

    <?php if (empty($medias)): ?>
        <p>Aucun média à afficher.</p>
    <?php else: ?>
        <div class="catalogue-grid-cat">
            <?php foreach ($medias as $media): ?>
                <div class="catalogue-card-cat">
                    <img src="<?= !empty($media['cover'])
                                    ? url('assets/uploads/covers/' . basename(str_replace('\\', '/', $media['cover'])))
                                    : url('assets/images/default_cover.jpg') ?>"
                        alt="<?= htmlspecialchars($media['title']) ?>">

                    <h3><?= htmlspecialchars($media['title']) ?></h3>
                    <p>Type : <?= htmlspecialchars($media['type']) ?></p>
                    <p>Genre : <?= htmlspecialchars($media['genre']) ?></p>
                    <?php
                    // Correction : un média est 'Emprunté' seulement si stock=0 ET qu'il existe un emprunt en cours
                    $is_borrowed = false;
                    if ($media['stock'] == 0) {
                    
                        $db = db_connect();
                        $stmt = $db->prepare("SELECT COUNT(*) FROM borrows WHERE media_id = ? AND returned_at IS NULL AND status = 'En cours'");
                        $stmt->execute([$media['id']]);
                        $is_borrowed = $stmt->fetchColumn() > 0;
                    }
                    ?>
                    <p>
                        <?= ($media['stock'] > 0 || !$is_borrowed) ? 'Disponible' : 'Emprunté' ?>
                        <span style="color: #888; font-size: 0.95em;">(Stock : <?= $media['stock'] ?>)</span>
                    </p>
                    <div class="card-actions-cat">
                        <a href="<?= url('detail?id=' . urlencode($media['id'])) ?>" class="btn-cat">Voir détails</a>
                        <?php if ($media['stock'] > 0): ?>
                            <form method="POST" action="<?= url('borrow/borrow/' . $media['id']) ?>" onsubmit="return confirm('Confirmer l\'emprunt de ce média ?');">
                                <button type="submit" class="btn-emprunts-cat">Emprunter</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Pagination -->
        <?php
        $nbPages = ceil($total / $perPage);
        if ($nbPages > 1):
        ?>
            <nav class="pagination">
                <?php for ($i = 1; $i <= $nbPages; $i++): ?>
                    <?php
                    $params = $_GET;
                    $params['page'] = $i;
                    $url = '?' . http_build_query($params);
                    ?>
                    <a href="<?= $url ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>