</section>

<div style="text-align:center; margin-top:30px;">
    <a href="<?= url('borrow/history') ?>" class="btn">Voir mon historique d'emprunts</a>
</div> 
<?php if (has_flash_messages('success')): ?>
    <?php foreach (get_flash_messages('success') as $msg): ?>
        <p class="success"><?= esc($msg); ?></p>
    <?php endforeach; ?>
<?php elseif (has_flash_messages('error')): ?>
    <?php foreach (get_flash_messages('error') as $msg): ?>
        <p class="error"><?= esc($msg); ?></p>
    <?php endforeach; ?>
<?php endif; ?>
<section class="nouveautés">
    <form action="" method="POST">
        <label>Nom :</label>
        <input type="text" name="lastname" value="<?= isset($profile['lastname']) ? esc($profile['lastname']) : '' ?>" required>

        <label>Prénom :</label>
        <input type="text" name="name" value="<?= isset($profile['name']) ? esc($profile['name']) : '' ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= isset($profile['email']) ? esc($profile['email']) : '' ?>" required>

        <label>Nouveau mot de passe :</label>
        <input type="password" name="password" placeholder="laisser vide pour ne pas changer">

        <label>Confirmer le nouveau mot de passe :</label>
        <input type="password" name="password_confirm">

        <button type="submit">Modifier le profil</button>
    </form>
</section>