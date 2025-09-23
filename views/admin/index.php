<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>

<body>
    <h1>Page d'administration</h1>

    <?php if (!$is_admin): ?>
        <div class="alert alert-warning">
            Vous devez être connecté en tant qu'administrateur pour accéder aux fonctionnalités d'administration.<br>
            <a href="<?= url('auth/login') ?>">Se connecter</a>
        </div>
    <?php else: ?>
        <!-- Média-->

        <section>
            <h3> Ajouter un Média</h3>
            <form method="post" action="<?= url('admin/add_media') ?>" class="admin-media-form" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Titre" required>
                <select name="type" required>
                    <option value="">Type</option>
                    <option value="book">Livre</option>
                    <option value="movie">Film</option>
                    <option value="game">Jeu</option>
                </select>
                <select name="genre" required>
                    <option value="">Genre</option>
                    <option value="Roman">Roman</option>
                    <option value="Policier">Policier</option>
                    <option value="Science-fiction">Science-fiction</option>
                    <option value="Fantastique">Fantastique</option>
                    <option value="Biographie">Biographie</option>
                    <option value="Histoire">Histoire</option>
                    <option value="Jeunesse">Jeunesse</option>
                    <option value="Aventure">Aventure</option>
                    <option value="Drame">Drame</option>
                    <option value="Comédie">Comédie</option>
                    <option value="Action">Action</option>
                    <option value="Animation">Animation</option>
                    <option value="Documentaire">Documentaire</option>
                    <option value="Horreur">Horreur</option>
                    <option value="Sport">Sport</option>
                    <option value="Autre">Autre</option>
                </select>
                <input type="number" name="stock" value="1" min="1" required>
                <input type="file" name="cover" accept="image/*">
                <hr>
                <b>Champs Livre</b><br>
                <input type="text" name="author" placeholder="Auteur">
                <input type="text" name="isbn" placeholder="ISBN (10 ou 13 chiffres)">
                <input type="number" name="pages" placeholder="Nombre de pages" min="1" max="9999">
                <input type="number" name="year_book" placeholder="Année de publication" min="1900" max="<?= date('Y') ?>">
                <textarea name="summary" placeholder="Résumé du livre"></textarea>
                <hr>
                <b>Champs Film</b><br>
                <input type="text" name="director" placeholder="Réalisateur">
                <input type="number" name="duration" placeholder="Durée (min)" min="1" max="999">
                <input type="number" name="year_movie" placeholder="Année" min="1900" max="<?= date('Y') ?>">
                <select name="classification">
                    <option value="">Classification</option>
                    <option value="Tous publics">Tous publics</option>
                    <option value="-12">-12</option>
                    <option value="-16">-16</option>
                    <option value="-18">-18</option>
                </select>
                <textarea name="synopsis" placeholder="Synopsis du film"></textarea>
                <hr>
                <b>Champs Jeu</b><br>
                <input type="text" name="editor" placeholder="Éditeur">
                <select name="platform">
                    <option value="">Plateforme</option>
                    <option value="PC">PC</option>
                    <option value="PlayStation">PlayStation</option>
                    <option value="Xbox">Xbox</option>
                    <option value="Nintendo">Nintendo</option>
                    <option value="Mobile">Mobile</option>
                </select>
                <select name="age_min">
                    <option value="">Âge minimum</option>
                    <option value="3">3</option>
                    <option value="7">7</option>
                    <option value="12">12</option>
                    <option value="16">16</option>
                    <option value="18">18</option>
                </select>
                <textarea name="description" placeholder="Description du jeu"></textarea>
                <br><br>
                <button type="submit">Ajouter</button>
            </form>
        </section>
        <script>
            function showFields() {
                var type = document.querySelector('[name="type"]').value;
                document.getElementById('fields_book').style.display = (type === 'book') ? 'block' : 'none';
                document.getElementById('fields_movie').style.display = (type === 'movie') ? 'block' : 'none';
                document.getElementById('fields_game').style.display = (type === 'game') ? 'block' : 'none';
            }
            window.onload = showFields;
        </script>

        <!-- Statistiques -->
        <section>
            <h2>Statistiques</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Médias</h3>
                    <p><?= $stats['total_medias'] ?? '?' ?></p>
                </div>
                <div class="stat-card">
                    <h3>Utilisateurs</h3>
                    <p><?= $stats['total_users'] ?? '?' ?></p>
                </div>
                <div class="stat-card">
                    <h3>Emprunts en cours</h3>
                    <p><?= $stats['total_borrows'] ?? '?' ?></p>
                </div>
                <div class="stat-card warning">
                    <h3>Emprunts en retard</h3>
                    <p><?= $stats['late_borrows'] ?? '?' ?></p>
                </div>
                <div class="stat-card info">
                    <h3>Médias par catégorie</h3>
                    <ul style="list-style:none;padding:0;margin:0;">
                        <?php foreach (($medias_by_type ?? []) as $type => $total): ?>
                            <li><b><?= htmlspecialchars(ucfirst($type)) ?> :</b> <?= $total ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Statistiques d'utilisation par utilisateur -->
        <section>
            <h2>Statistiques d'utilisation par utilisateur</h2>
            <div class="table-responsive">
                <table border="1" cellpadding="3">
                    <tr>
                        <th>Email</th>
                        <th>Emprunts totaux</th>
                        <th>Emprunts en cours</th>
                        <th>Emprunts rendus</th>
                    </tr>
                    <?php foreach (($user_borrow_stats ?? []) as $stat): ?>
                        <tr>
                            <td><?= htmlspecialchars($stat['email']) ?></td>
                            <td><?= $stat['total_borrows'] ?></td>
                            <td><?= $stat['active_borrows'] ?></td>
                            <td><?= $stat['returned_borrows'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>

        <!-- Gestion des emprunts -->
        <section>
            <h2>Emprunts en cours</h2>
            <div class="table-responsive">
                <table border="1" cellpadding="3">
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Média</th>
                        <th>Date d'emprunt</th>
                        <th>Date retour prévue</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($borrows as $b): ?>
                        <tr>
                            <td><?= $b['id'] ?></td>
                            <td><?= htmlspecialchars($b['user_email']) ?></td>
                            <td><?= htmlspecialchars($b['media_title']) ?></td>
                            <td><?= htmlspecialchars($b['borrowed_at']) ?></td>
                            <td><?= htmlspecialchars($b['due_date']) ?></td>
                            <td>
                                <form method="post" action="<?= url('admin/force_return/' . $b['id']) ?>" style="margin:0;">
                                    <button type="submit">Forcer retour</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>

        <!-- Médias -->
        <section>
            <h2>Gestion des Médias</h2>
            <div class="table-responsive">
                <table border="1" cellpadding="3">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Genre</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($medias as $m): ?>
                        <?php
                        $book = $m['type'] === 'book' && isset($m['book']) ? $m['book'] : [];
                        $movie = $m['type'] === 'movie' && isset($m['movie']) ? $m['movie'] : [];
                        $game = $m['type'] === 'game' && isset($m['game']) ? $m['game'] : [];
                        ?>
                        <tr>
                            <form method="post" action="<?= url('admin/update_media') ?>" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                <td><?= $m['id'] ?></td>
                                <td><input type="text" name="title" value="<?= htmlspecialchars($m['title']) ?>"></td>
                                <td>
                                    <select name="type" class="edit-type-select" data-row="<?= $m['id'] ?>">
                                        <option value="book" <?= $m['type'] == 'book' ? 'selected' : '' ?>>Livre</option>
                                        <option value="movie" <?= $m['type'] == 'movie' ? 'selected' : '' ?>>Film</option>
                                        <option value="game" <?= $m['type'] == 'game' ? 'selected' : '' ?>>Jeu</option>
                                    </select>
                                </td>
                                <td><input type="text" name="genre" value="<?= htmlspecialchars($m['genre']) ?>"></td>
                                <td><input type="number" name="stock" value="<?= $m['stock'] ?>" min="1"></td>
                                <td>
                                    <input type="file" name="cover" accept="image/*">
                                    <input type="hidden" name="cover_old" value="<?= htmlspecialchars($m['cover']) ?>">
                                    <?php if (!empty($m['cover'])): ?>
                                        <br><img src="<?= url('assets/uploads/covers/' . basename(str_replace('\\', '/', $m['cover']))) ?>?v=<?= time() ?>"
                                            alt="cover"
                                            style="max-width:60px;max-height:60px;display:block;margin-top:4px;">
                                    <?php endif; ?>


                                    <!-- Champs spécifiques -->
                                    <div class="edit-fields-book" style="display:<?= $m['type'] == 'book' ? 'block' : 'none' ?>;margin-top:8px;">
                                        <b>Livre</b><br>
                                        <input type="text" name="author" placeholder="Auteur" value="<?= htmlspecialchars($book['author'] ?? '') ?>">
                                        <input type="text" name="isbn" placeholder="ISBN (10 ou 13 chiffres)" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
                                        <input type="number" name="pages" placeholder="Nombre de pages" min="1" max="9999" value="<?= htmlspecialchars($book['pages'] ?? '') ?>">
                                        <input type="number" name="year_book" placeholder="Année de publication" min="1900" max="<?= date('Y') ?>" value="<?= htmlspecialchars($book['year'] ?? '') ?>">
                                        <textarea name="summary" placeholder="Résumé du livre"><?= htmlspecialchars($book['summary'] ?? '') ?></textarea>
                                    </div>
                                    <div class="edit-fields-movie" style="display:<?= $m['type'] == 'movie' ? 'block' : 'none' ?>;margin-top:8px;">
                                        <b>Film</b><br>
                                        <input type="text" name="director" placeholder="Réalisateur" value="<?= htmlspecialchars($movie['director'] ?? '') ?>">
                                        <input type="number" name="duration" placeholder="Durée (min)" min="1" max="999" value="<?= htmlspecialchars($movie['duration'] ?? '') ?>">
                                        <input type="number" name="year_movie" placeholder="Année" min="1900" max="<?= date('Y') ?>" value="<?= htmlspecialchars($movie['year'] ?? '') ?>">
                                        <select name="classification">
                                            <option value="">Classification</option>
                                            <option value="Tous publics" <?= (isset($movie['classification']) && $movie['classification'] == 'Tous publics') ? 'selected' : '' ?>>Tous publics</option>
                                            <option value="-12" <?= (isset($movie['classification']) && $movie['classification'] == '-12') ? 'selected' : '' ?>>-12</option>
                                            <option value="-16" <?= (isset($movie['classification']) && $movie['classification'] == '-16') ? 'selected' : '' ?>>-16</option>
                                            <option value="-18" <?= (isset($movie['classification']) && $movie['classification'] == '-18') ? 'selected' : '' ?>>-18</option>
                                        </select>
                                        <textarea name="synopsis" placeholder="Synopsis du film"><?= htmlspecialchars($movie['synopsis'] ?? '') ?></textarea>
                                    </div>
                                    <div class="edit-fields-game" style="display:<?= $m['type'] == 'game' ? 'block' : 'none' ?>;margin-top:8px;">
                                        <b>Jeu</b><br>
                                        <input type="text" name="editor" placeholder="Éditeur" value="<?= htmlspecialchars($game['publisher'] ?? '') ?>">
                                        <select name="platform">
                                            <option value="">Plateforme</option>
                                            <option value="PC" <?= (isset($game['platform']) && $game['platform'] == 'PC') ? 'selected' : '' ?>>PC</option>
                                            <option value="PlayStation" <?= (isset($game['platform']) && $game['platform'] == 'PlayStation') ? 'selected' : '' ?>>PlayStation</option>
                                            <option value="Xbox" <?= (isset($game['platform']) && $game['platform'] == 'Xbox') ? 'selected' : '' ?>>Xbox</option>
                                            <option value="Nintendo" <?= (isset($game['platform']) && $game['platform'] == 'Nintendo') ? 'selected' : '' ?>>Nintendo</option>
                                            <option value="Mobile" <?= (isset($game['platform']) && $game['platform'] == 'Mobile') ? 'selected' : '' ?>>Mobile</option>
                                        </select>
                                        <select name="age_min">
                                            <option value="">Âge minimum</option>
                                            <option value="3" <?= (isset($game['min_age']) && $game['min_age'] == '3') ? 'selected' : '' ?>>3</option>
                                            <option value="7" <?= (isset($game['min_age']) && $game['min_age'] == '7') ? 'selected' : '' ?>>7</option>
                                            <option value="12" <?= (isset($game['min_age']) && $game['min_age'] == '12') ? 'selected' : '' ?>>12</option>
                                            <option value="16" <?= (isset($game['min_age']) && $game['min_age'] == '16') ? 'selected' : '' ?>>16</option>
                                            <option value="18" <?= (isset($game['min_age']) && $game['min_age'] == '18') ? 'selected' : '' ?>>18</option>
                                        </select>
                                        <textarea name="description" placeholder="Description du jeu"><?= htmlspecialchars($game['description'] ?? '') ?></textarea>
                                    </div>
                                    <button type="submit">Sauvegarder</button>
                                    <a href="<?= url('admin/delete_media/' . $m['id']) ?>" onclick="return confirm('Supprimer ce média ?')">Supprimez</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>

        <!-- Utilisateurs -->
        <section>
            <h2>Gestion des Utilisateurs</h2>
            <div class="table-responsive">
                <table border="1" cellpadding="3">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['lastname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <a href="<?= url('admin/delete_user/' . $u['id']) ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <body>
        <html>