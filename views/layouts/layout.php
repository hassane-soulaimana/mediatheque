<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? esc($title) . ' - ' . APP_NAME : APP_NAME; ?></title>
    <link rel="stylesheet" href="/mediatheque_paris_grp2/public/assets/css/style.css">
    <?php if (isset($title) && stripos($title, 'admin') !== false): ?>
        <link rel="stylesheet" href="/mediatheque_paris_grp2/public/assets/css/admin.css">
    <?php endif; ?>
    <?php if (isset($title) && stripos($title, 'catalogue') !== false): ?>
        <link rel="stylesheet" href="/mediatheque_paris_grp2/public/assets/css/catalogue.css">
    <?php endif; ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <a href="<?php echo url(); ?>">
                    <img src="<?= url('assets/uploads/covers/logo.webp') ?>" alt="<?= APP_NAME ?>" class="logo">
                    <span class="brand-text">Médiathèque</span>
                </a>
            </div>


            <ul class="nav-menu">
                <li><a href="<?php echo url(); ?>">Accueil</a></li>
                <li><a href="<?php echo url('home/about'); ?>">À propos</a></li>
                <li><a href="<?php echo url('home/contact'); ?>">Contact</a></li>
                <li><a href="<?php echo url('catalogue/index'); ?>">Catalogue</a></li>
                <li><a href="<?php echo url('home/profile'); ?>">Profil</a></li>
                <?php if (is_logged_in()): ?>
                    <?php if ((isset($_SESSION['role']) && $_SESSION['role'] === 'admin')): ?>
                        <li><a href="<?php echo url('admin/index'); ?>">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo url('auth/logout'); ?>">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?php echo url('auth/login'); ?>">Connexion</a></li>
                    <li><a href="<?php echo url('auth/register'); ?>">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="main-content">
        <?php flash_messages(); ?>
        <?php echo $content ?? ''; ?>
    </main>

    <!-- FOOTER CORRIGÉ -->
    <footer class="footer">
        <div class="footer-content">
            <!-- Bloc gauche : Logo et description -->
            <div class="footer-left">
                <img src="<?= url('assets/uploads/covers/logo.webp') ?>" alt="Logo <?php echo APP_NAME; ?>" class="footer-logo">
                <p class="footer-description">Votre médiathèque moderne au cœur de Paris. Livres, films, jeux et ressources numériques.</p>
            </div>

            <!-- Bloc centre : Liens rapides -->
            <div class="footer-center">
                <div class="footer-section">
                    <h3>Navigation</h3>
                    <ul class="footer-nav">
                        <li><a href="<?php echo url(); ?>">Accueil</a></li>
                        <li><a href="<?php echo url('catalogue/index'); ?>">Catalogue</a></li>
                        <li><a href="<?php echo url('home/about'); ?>">À propos</a></li>
                        <li><a href="<?php echo url('home/contact'); ?>">Contact</a></li>
                        <?php if (is_logged_in()): ?>
                            <li><a href="<?php echo url('home/profile'); ?>">Mon profil</a></li>
                            <li><a href="<?php echo url('borrow/history'); ?>">Mes emprunts</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Ressources</h3>
                    <ul class="footer-nav">
                        <li><a href="<?php echo url('catalogue/index?type=livre'); ?>">Livres</a></li>
                        <li><a href="<?php echo url('catalogue/index?type=film'); ?>">Films</a></li>
                        <li><a href="<?php echo url('catalogue/index?type=jeu'); ?>">Jeux</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Informations</h3>
                    <div class="footer-info">
                        <p><i class="fas fa-clock"></i> Lun-Ven: 9h-18h | Sam: 10h-16h</p>
                        <p><i class="fas fa-map-marker-alt"></i> 123 Rue de la Culture, Paris</p>
                        <p><i class="fas fa-phone"></i> 01 23 45 67 89</p>
                        <p><i class="fas fa-envelope"></i> contact@mediatheque.fr</p>
                    </div>
                </div>
            </div>

            <!-- Bloc droite : Newsletter et réseaux sociaux -->
            <div class="footer-right">
                <div class="social-icons">
                    <h3>Suivez-nous</h3>
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://youtube.com" target="_blank" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div> <!-- Fermeture de footer-content -->

        <!-- Section bas de footer pour le copyright -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> - Tous droits réservés</p>
        </div>
    </footer>

    <script src="<?php echo url('assets/js/app.js'); ?>"></script>
</body>

</html>