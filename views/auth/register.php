<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><?php e($title); ?></h1>
            <p>Créez votre compte</p>
        </div>

        <?php if (has_flash_messages('error')): ?>
            <div class="alert alert-danger">
                <?php foreach (get_flash_messages('error') as $err): ?>
                    <div><?php e($err); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (has_flash_messages('success')): ?>
            <div class="alert alert-success">
                <?php foreach (get_flash_messages('success') as $msg): ?>
                    <div><?php e($msg); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="lastname" name="lastname" required
                    value="<?php echo escape(post('name', '')); ?>"
                    placeholder="Votre nom">
            </div>

            <div class="form-group">
                <label for="name">Prénom</label>
                <input type="text" id="name" name="name" required
                    value="<?php echo escape(post('name', '')); ?>"
                    placeholder="Votre prénom">
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required
                    value="<?php echo escape(post('email', '')); ?>"
                    placeholder="votre@email.com">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required
                    placeholder="Au moins 8 caractères">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    placeholder="Confirmez votre mot de passe">
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-user-plus"></i>
                S'inscrire
            </button>
        </form>

        <div class="auth-footer">
            <p>Déjà un compte ?
                <a href="<?php echo url('auth/login'); ?>">Se connecter</a>
            </p>
        </div>
    </div>
</div>