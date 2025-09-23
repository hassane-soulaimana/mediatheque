<div class="media-details">
    <div class="media-header">
        <div class="media-cover">
            <img src="<?php
                        if (!empty($media['cover'])) {
                            $cover = str_replace('\\', '/', $media['cover']);
                            // Si le chemin contient 'uploads/covers', on l'utilise avec le helper url
                            if (strpos($cover, 'uploads/covers') !== false) {
                                echo url('assets/uploads/covers/' . basename($cover));
                            } elseif (strpos($cover, 'assets/images/default_cover.jpg') !== false) {
                                echo url('assets/images/default_cover.jpg');
                            } else {
                                // Si c'est déjà un chemin complet (ex: /mediatheque_paris_grp2/public/assets/uploads/covers/...)
                                echo url(ltrim(str_replace(['public/', 'mediatheque_paris_grp2/'], ['', ''], $cover), '/'));
                            }
                        } else {
                            echo url('assets/images/default_cover.jpg');
                        }
                        ?>"
                alt="<?= htmlspecialchars($media['title']) ?>">
        </div>

        <div class="media-info">
            <h1><?= htmlspecialchars($media['title']) ?></h1>

            <div class="general-info">
                <p><strong>Type:</strong> <?= ucfirst(htmlspecialchars($media['type'])) ?></p>
                <p><strong>Genre:</strong> <?= htmlspecialchars($media['genre']) ?></p>
                <p><strong>Disponibilité:</strong>
                    <span class="<?= ($media['stock'] > 0) ? 'disponible' : 'indisponible' ?>">
                        <?= ($media['stock'] > 0) ? 'Disponible' : 'Indisponible' ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="specific-details">
        <?php if (!empty($media['__missing_details'])): ?>
            <div class="alert alert-warning">
                <strong>Attention :</strong> Les détails spécifiques de ce média sont manquants dans la base de données.<br>
                Merci de vérifier la cohérence des données (table <?= htmlspecialchars($media['type']) ?>s).
            </div>
        <?php endif; ?>
        <?php if ($media['type'] === 'book'): ?>
            <h2>Détails du livre</h2>
            <p><strong>Auteur:</strong> <?= htmlspecialchars($media['author'] ?? 'Non spécifié') ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($media['isbn'] ?? 'Non spécifié') ?></p>
            <p><strong>Nombre de pages:</strong> <?= $media['pages'] ?? 'Non spécifié' ?></p>
            <p><strong>Année de publication:</strong> <?= $media['year'] ?? 'Non spécifié' ?></p>
            <?php if (!empty($media['summary'])): ?>
                <p><strong>Résumé:</strong></p>
                <div class="summary"><?= nl2br(htmlspecialchars($media['summary'])) ?></div>
            <?php endif; ?>

        <?php elseif ($media['type'] === 'movie'): ?>
            <h2>Détails du film</h2>
            <p><strong>Réalisateur:</strong> <?= htmlspecialchars($media['director'] ?? 'Non spécifié') ?></p>
            <p><strong>Année:</strong> <?= $media['year'] ?? 'Non spécifié' ?></p>
            <p><strong>Durée:</strong> <?= $media['duration'] ?? 'Non spécifié' ?> minutes</p>
            <p><strong>Classification:</strong> <?= htmlspecialchars($media['classification'] ?? 'Non spécifié') ?></p>
            <?php if (!empty($media['synopsis'])): ?>
                <p><strong>Synopsis:</strong></p>
                <div class="synopsis"><?= nl2br(htmlspecialchars($media['synopsis'])) ?></div>
            <?php endif; ?>

        <?php elseif ($media['type'] === 'game'): ?>
            <h2>Détails du jeu</h2>
            <p><strong>Éditeur:</strong> <?= htmlspecialchars($media['publisher'] ?? 'Non spécifié') ?></p>
            <p><strong>Plateforme:</strong> <?= htmlspecialchars($media['platform'] ?? 'Non spécifié') ?></p>
            <p><strong>Âge minimum:</strong> <?= $media['min_age'] ?? 'Non spécifié' ?> ans</p>
            <?php if (!empty($media['description'])): ?>
                <p><strong>Description:</strong></p>
                <div class="description"><?= nl2br(htmlspecialchars($media['description'])) ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="action-buttons">
        <a href="<?= url('catalogue') ?>" class="btn btn-secondary">← Retour au catalogue</a>

        <?php if ($media['stock'] > 0 && isset($_SESSION['user_id'])): ?>
            <form method="POST" action="<?= url('borrow/borrow/' . $media['id']) ?>" class="d-inline">
                <button type="submit" class="btn btn-primary">Emprunter</button>
            </form>
        <?php endif; ?>
    </div>
</div>