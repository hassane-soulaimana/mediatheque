<h2>Historique de mes emprunts</h2>
<table class="borrow-history">
    <tr>
        <th>Média</th>
        <th>Date d'emprunt</th>
        <th>Date de retour prévue</th>
        <th>Date de retour effective</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>
    <?php foreach ($history as $b): ?>
        <tr>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= htmlspecialchars($b['borrowed_at']) ?></td>
            <td><?= htmlspecialchars($b['due_date']) ?></td>
            <td><?= $b['returned_at'] ? htmlspecialchars($b['returned_at']) : '-' ?></td>
            <td>
                <?php if ($b['returned_at']): ?>
                    ✅ Rendu
                <?php else: ?>
                    ⏳ En cours
                <?php endif; ?>
            </td>
            <td>
                <?php if (!$b['returned_at']): ?>
                    <form method="post" action="<?= url('borrow/return/' . $b['id']) ?>" style="margin:0;">
                        <button type="submit" class="btn-return">Rendre</button>
                    </form>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>