<h1>Page d'administration</h1>

<!-- ================== GESTION MEDIAS ================== -->
<section>
    <h2>Gestion des Médias</h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Type</th>
            <th>Genre</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($medias as $m): ?>
            <tr>
                <form method="post" action="/admin/media/update">
                    <td><?= $m['id'] ?><input type="hidden" name="id" value="<?= $m['id'] ?>"></td>
                    <td><input type="text" name="title" value="<?= htmlspecialchars($m['title']) ?>"></td>
                    <td>
                        <select name="type">
                            <option value="book" <?= $m['type'] == 'book' ? 'selected' : '' ?>>Livre</option>
                            <option value="movie" <?= $m['type'] == 'movie' ? 'selected' : '' ?>>Film</option>
                            <option value="game" <?= $m['type'] == 'game' ? 'selected' : '' ?>>Jeu</option>
                        </select>
                    </td>
                    <td><input type="text" name="genre" value="<?= htmlspecialchars($m['genre']) ?>"></td>
                    <td><input type="number" name="stock" value="<?= $m['stock'] ?>"></td>
                    <td>
                        <input type="text" name="cover" value="<?= htmlspecialchars($m['cover']) ?>" placeholder="URL cover">
                        <button type="submit">💾 Sauver</button>
                        <a href="/admin/media/delete?id=<?= $m['id'] ?>" onclick="return confirm('Supprimer ce média ?')">🗑️</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>➕ Ajouter un Média</h3>
    <form method="post" action="/admin/media/add">
        <input type="text" name="title" placeholder="Titre" required>
        <select name="type">
            <option value="book">Livre</option>
            <option value="movie">Film</option>
            <option value="game">Jeu</option>
        </select>
        <input type="text" name="genre" placeholder="Genre">
        <input type="number" name="stock" value="1" min="0">
        <input type="text" name="cover" placeholder="URL de couverture">
        <button type="submit">Ajouter</button>
    </form>
</section>

<hr>

<!-- ================== GESTION UTILISATEURS ================== -->
<section>
    <h2>Gestion des Utilisateurs</h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['lastname'] . " " . $u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td>
                    <a href="/admin/user/delete/<?= $u['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimez</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
/admin/user/delete/<?= $u['id'] ?>