<div class="page-header">
    <link rel="stylesheet" href="<?= url('assets/css/contact.css') ?>">
    <div class="container">
        <h1>Contactez la Médiathèque</h1>
    </div>
</div>

<section class="contact-section">
    <div class="container">
        <div class="contact-flex">
            <div class="contact-main">
                <h2>Envoyez-nous un message</h2>
                <p>Une question, une suggestion ou un souci ? Remplissez le formulaire ci-dessous, notre équipe vous répondra rapidement.</p>
                <form method="POST" class="contact-form">
                    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" id="name" name="name" required value="<?php echo escape(post('name', '')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required value="<?php echo escape(post('prenom', '')); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" required value="<?php echo escape(post('email', '')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="message">Votre message</label>
                        <textarea id="message" name="message" rows="6" required><?php echo escape(post('message', '')); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-contact">
                        <span>Envoyer <i class="fas fa-paper-plane"></i></span>
                    </button>
                </form>
            </div>
            <aside class="contact-sidebar">
                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Infos pratiques</h4>
                    <ul class="contact-infos">
                        <li><i class="fas fa-envelope"></i> contact@mediatheque-paris.fr</li>
                        <li><i class="fas fa-phone"></i> +33 1 23 45 67 89</li>
                        <li><i class="fas fa-map-marker-alt"></i> 123 Rue de la Culture, 75000 Paris</li>
                    </ul>
                </div>
                <div class="info-box">
                    <h4><i class="fas fa-clock"></i> Horaires</h4>
                    <ul class="contact-hours">
                        <li><strong>Lundi - Vendredi :</strong> 9h00 - 18h00</li>
                        <li><strong>Samedi :</strong> 9h00 - 12h00</li>
                        <li><strong>Dimanche :</strong> Fermé</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>