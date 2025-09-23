  <link href="../assets/css/home.css" rel="stylesheet" />
  <link href="../assets/css/media-cards.css" rel="stylesheet" />
<!-- Hero -->
<section class="hero-section">
  <div class="hero-box">
    <h1 class="hero-title">Bienvenue à la Médiathèque</h1>
    <p class="hero-text">Cherchez des livres, revues, films, jeux et plus encore...</p>
    <form class="search-box" action="<?php echo url('catalogue'); ?>" method="GET">
      <span class="icon"><i class="fas fa-search"></i></span>
      <input type="text" name="q" placeholder="Rechercher un titre, un auteur, un genre...">
      <button type="submit">Recherche avancée</button>
    </form>
  </div>
</section>

<!-- Nouveautés -->
<section class="nouveautes">
  <h2>🌟 Nouveautés</h2>
  <div class="grid-nouveautes">
    <?php if (!empty($new_books)): ?>
      <?php foreach ($new_books as $book): ?>
        <a href="<?php echo url('detail?id=' . $book['id']); ?>">
          <div class="card-home">
            <img src="<?php echo !empty($book['cover']) ? url('assets/uploads/covers/' . basename(str_replace('\\', '/', $book['cover']))) : url('assets/images/default_cover.jpg'); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <a href="<?php echo url('catalogue/index?type=book'); ?>">
        <div class="card-home">Livres</div>
      </a>
      <a href="<?php echo url('catalogue/index?type=movie'); ?>">
        <div class="card-home">Films</div>
      </a>
      <a href="<?php echo url('catalogue/index?type=game'); ?>">
        <div class="card-home">Jeux</div>
      </a>
    <?php endif; ?>
  </div>
  <div class="voir-plus">
    <a href="<?php echo url('catalogue/index'); ?>">Voir tout le catalogue</a>
  </div>
</section>

<!-- A la une -->
<section class="home-categories">
  <h2>📚 Livres</h2>
  <div class="grid">
    <?php foreach (($books ?? []) as $media): ?>
      <div class="card-media">
        <img src="<?= !empty($media['cover']) ? url('assets/uploads/covers/' . basename(str_replace('\\', '/', $media['cover']))) : url('assets/images/default_cover.jpg') ?>" alt="<?= htmlspecialchars($media['title']) ?>">
        <h3><?= htmlspecialchars($media['title']) ?></h3>
        <p><?= htmlspecialchars($media['genre']) ?></p>
        <a href="<?= url('detail?id=' . urlencode($media['id'])) ?>" class="btn-media">Voir détails</a>
        <form method="POST" action="<?= url('borrow/borrow/' . $media['id']) ?>" style="display:inline;">
          <button type="submit" class="btn-media" <?= ($media['stock'] ?? 0) == 0 ? 'disabled' : '' ?>>Emprunter</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <h2>🎬 Films</h2>
<div class="grid">
  <?php foreach (($movies ?? []) as $media): ?>
    <div class="card-media">
      <img src="<?= !empty($media['cover']) ? url('assets/uploads/covers/' . basename(str_replace('\\', '/', $media['cover']))) : url('assets/images/default_cover.jpg') ?>" alt="<?= htmlspecialchars($media['title']) ?>">
      <h3><?= htmlspecialchars($media['title']) ?></h3>
      <p><?= htmlspecialchars($media['genre']) ?></p>
      <a href="<?= url('detail?id=' . urlencode($media['id'])) ?>" class="btn-media">Voir détails</a>
      <form method="POST" action="<?= url('borrow/borrow/' . $media['id']) ?>" style="display:inline;">
        <button type="submit" class="btn-media" <?= ($media['stock'] ?? 0) == 0 ? 'disabled' : '' ?>>Emprunter</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<h2>🎮 Jeux</h2>
<div class="grid">
  <?php foreach (($games ?? []) as $media): ?>
    <div class="card-media">
      <img src="<?= !empty($media['cover']) ? url('assets/uploads/covers/' . basename(str_replace('\\', '/', $media['cover']))) : url('assets/images/default_cover.jpg') ?>" alt="<?= htmlspecialchars($media['title']) ?>">
      <h3><?= htmlspecialchars($media['title']) ?></h3>
      <p><?= htmlspecialchars($media['genre']) ?></p>
      <a href="<?= url('detail?id=' . urlencode($media['id'])) ?>" class="btn-media">Voir détails</a>
      <form method="POST" action="<?= url('borrow/borrow/' . $media['id']) ?>" style="display:inline;">
        <button type="submit" class="btn-media" <?= ($media['stock'] ?? 0) == 0 ? 'disabled' : '' ?>>Emprunter</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>


<!-- Ressources Documentaires -->
<section class="ressources-documentaires">
  <h2>📚 Ressources Documentaires</h2>
  <p class="section-description">Accédez à nos ressources en ligne et partenaires numériques</p>

  <div class="ressources-grid">
    <a href="https://www.gallica.bnf.fr" target="_blank" class="ressource-card">
      <div class="ressource-icon">
        <i class="fas fa-book-open"></i>
      </div>
      <h3>Gallica - BnF</h3>
      <p>Bibliothèque numérique de la Bibliothèque nationale de France</p>
      <span class="ressource-link">Accéder <i class="fas fa-external-link-alt"></i></span>
    </a>

    <a href="https://www.europresse.com" target="_blank" class="ressource-card">
      <div class="ressource-icon">
        <i class="fas fa-newspaper"></i>
      </div>
      <h3>Europresse</h3>
      <p>Plateforme de presse française et internationale</p>
      <span class="ressource-link">Accéder <i class="fas fa-external-link-alt"></i></span>
    </a>

    <a href="https://www.cairn.info" target="_blank" class="ressource-card">
      <div class="ressource-icon">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h3>Cairn.info</h3>
      <p>Revues de sciences humaines et sociales</p>
      <span class="ressource-link">Accéder <i class="fas fa-external-link-alt"></i></span>
    </a>

    <a href="https://www.arte.tv" target="_blank" class="ressource-card">
      <div class="ressource-icon">
        <i class="fas fa-tv"></i>
      </div>
      <h3>ARTE Médiathèque</h3>
      <p>Documentaires et magazines en streaming</p>
      <span class="ressource-link">Accéder <i class="fas fa-external-link-alt"></i></span>
    </a>

    <a href="https://www.universcine.com" target="_blank" class="ressource-card">
      <div class="ressource-icon">
        <i class="fas fa-film"></i>
      </div>
      <h3>UniversCiné</h3>
      <p>VOD de films du patrimoine et indépendants</p>
      <span class="ressource-link">Accéder <i class="fas fa-external-link-alt"></i></span>
    </a>

    <a href="https://www.ina.fr" target="_blank" class="ressource-card">
      <div class="ressource-icon">
        <i class="fas fa-history"></i>
      </div>
      <h3>INA.fr</h3>
      <p>Archives audiovisuelles françaises</p>
      <span class="ressource-link">Accéder <i class="fas fa-external-link-alt"></i></span>
    </a>
  </div>


  <!-- Événements -->
  <section class="evenements">
    <h2>📅 Événements à venir</h2>
    <div class="evenements-grid">
      <div class="evenement-card">
        <div class="evenement-date">
          <span class="jour">15</span>
          <span class="mois">Oct</span>
        </div>
        <div class="evenement-content">
          <h3>Club de lecture</h3>
          <p>Discussion autour du roman "L'Étranger" d'Albert Camus</p>
          <span class="evenement-info"><i class="fas fa-clock"></i> 18h30 - Médiathèque</span>
        </div>
      </div>

      <div class="evenement-card">
        <div class="evenement-date">
          <span class="jour">22</span>
          <span class="mois">Oct</span>
        </div>
        <div class="evenement-content">
          <h3>Projection cinéma</h3>
          <p>Film "Le Fabuleux Destin d'Amélie Poulain" de Jean-Pierre Jeunet</p>
          <span class="evenement-info"><i class="fas fa-clock"></i> 20h00 - Salle de projection</span>
        </div>
      </div>

      <div class="evenement-card">
        <div class="evenement-date">
          <span class="jour">05</span>
          <span class="mois">Nov</span>
        </div>
        <div class="evenement-content">
          <h3>Atelier numérique</h3>
          <p>Découverte des ressources en ligne de la médiathèque</p>
          <span class="evenement-info"><i class="fas fa-clock"></i> 14h00 - Espace multimédia</span>
        </div>
      </div>
    </div>
  </section>