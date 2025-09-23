<?php
require_once __DIR__ . '/../models/admin_model.php';
require_once __DIR__ . '/../models/admin_stats_model.php';

/* <Media*/

function admin_add_media()
{
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        set_flash('error', 'Accès réservé aux administrateurs.');
        redirect('home/index');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $type  = $_POST['type'] ?? '';
        $genre = $_POST['genre'] ?? '';
        $stock = $_POST['stock'] ?? 1;
        $cover = null;

        // Champs spécifiques
        $author = trim($_POST['author'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $pages = isset($_POST['pages']) ? intval($_POST['pages']) : null;
        $year_book = isset($_POST['year_book']) ? intval($_POST['year_book']) : null;
        $summary = trim($_POST['summary'] ?? '');

        $director = trim($_POST['director'] ?? '');
        $duration = isset($_POST['duration']) ? intval($_POST['duration']) : null;
        $year_movie = isset($_POST['year_movie']) ? intval($_POST['year_movie']) : null;
        $classification = $_POST['classification'] ?? '';
        $synopsis = trim($_POST['synopsis'] ?? '');

        $editor = trim($_POST['editor'] ?? '');
        $platform = $_POST['platform'] ?? '';
        $age_min = $_POST['age_min'] ?? '';
        $description = trim($_POST['description'] ?? '');

        // Gestion de l’upload d’image
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
            list($cover_name, $error) = handle_image_upload($_FILES['cover']);
            if ($error) {
                set_flash('error', $error);
                redirect('admin/index');
                exit;
            }
            // Stocker le chemin absolu relatif au projet pour la BDD
            $cover = '/mediatheque_paris_grp2/public/assets/uploads/covers/' . $cover_name;
        } else {
            // Image par défaut si aucun upload
            $cover = '/mediatheque_paris_grp2/public/assets/images/default_cover.jpg';
        }

        $errors = [];
        if (!$title) $errors[] = "Le titre est obligatoire.";
        if (!$type) $errors[] = "Le type est obligatoire.";
        if (!$genre) $errors[] = "Le genre est obligatoire.";
        if (!is_numeric($stock) || intval($stock) < 1) $errors[] = "Le stock doit être un entier positif (min 1).";

        if ($type === 'book') {
            if (strlen($author) < 2) $errors[] = "L'auteur est obligatoire (2 caractères minimum).";
            if (!preg_match('/^\d{10}(\d{3})?$/', $isbn)) $errors[] = "L'ISBN doit comporter 10 ou 13 chiffres.";
            if ($pages < 1) $errors[] = "Le nombre de pages est obligatoire (min 1).";
            $currentYear = intval(date('Y'));
            if ($year_book < 1900 || $year_book > $currentYear) $errors[] = "L'année de publication doit être comprise entre 1900 et $currentYear.";
        } elseif ($type === 'movie') {
            if (strlen($director) < 2) $errors[] = "Le réalisateur est obligatoire (2 caractères minimum).";
            if ($duration < 1) $errors[] = "La durée est obligatoire (min 1 minute).";
            $currentYear = intval(date('Y'));
            if ($year_movie < 1900 || $year_movie > $currentYear) $errors[] = "L'année du film doit être comprise entre 1900 et $currentYear.";
            if (!in_array($classification, ['Tous publics', '-12', '-16', '-18'])) $errors[] = "Classification obligatoire (Tous publics, -12, -16, -18).";
        } elseif ($type === 'game') {
            if (strlen($editor) < 2) $errors[] = "L'éditeur est obligatoire (2 caractères minimum).";
            if (!in_array($platform, ['PC', 'PlayStation', 'Xbox', 'Nintendo', 'Mobile'])) $errors[] = "Plateforme obligatoire (PC, PlayStation, Xbox, Nintendo, Mobile).";
            if (!in_array($age_min, ['3', '7', '12', '16', '18'])) $errors[] = "Âge minimum obligatoire (3, 7, 12, 16, 18).";
        }

        if ($errors) {
            set_flash('error', implode('<br>', $errors));
            redirect('admin');
            exit;
        }

        addMedia(
            $title,
            $type,
            $genre,
            $stock,
            $cover,
            $author,
            $isbn,
            $pages,
            $year_book,
            $director,
            $duration,
            $classification,
            $editor,
            $platform,
            $age_min,
            $summary,
            $synopsis,
            $description
        );
    }

    redirect('admin');
}

// Upload image
function handle_image_upload($file)
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return [null, "Erreur lors de l'upload du fichier."];
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];

    if ($file['size'] > 2097152) {
        return [null, "Fichier trop volumineux (max 2 Mo)."];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_extensions)) {
        return [null, "Format non supporté (jpg, jpeg, png, gif uniquement)."];
    }

    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $allowed_mime)) {
        return [null, "Type MIME non valide."];
    }

    $dimensions = getimagesize($file['tmp_name']);
    if (!$dimensions || $dimensions[0] < 100 || $dimensions[1] < 100) {
        return [null, "Dimensions trop petites (minimum 100x100px)."];
    }

    // Utiliser le nom d’origine (sécurisé)
    $original_name = pathinfo($file['name'], PATHINFO_FILENAME);
    $slug = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($original_name));
    $new_name = $slug . '.' . $ext;

    $upload_dir = __DIR__ . '/../public/assets/uploads/covers/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $destination = $upload_dir . $new_name;

    // Redimensionnement
    $tmp_path = $file['tmp_name'];
    list($width, $height, $type) = getimagesize($tmp_path);
    $max_width = 300;
    $max_height = 400;
    $ratio = min($max_width / $width, $max_height / $height, 1);
    $new_width = (int)($width * $ratio);
    $new_height = (int)($height * $ratio);
    $dst_img = imagecreatetruecolor($new_width, $new_height);

    switch ($type) {
        case IMAGETYPE_JPEG: $src_img = imagecreatefromjpeg($tmp_path); break;
        case IMAGETYPE_PNG:  $src_img = imagecreatefrompng($tmp_path); break;
        case IMAGETYPE_GIF:  $src_img = imagecreatefromgif($tmp_path); break;
        default: return [null, "Type d'image non supporté."];
    }

    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    $save_ok = false;
    if ($type === IMAGETYPE_JPEG) $save_ok = imagejpeg($dst_img, $destination, 90);
    elseif ($type === IMAGETYPE_PNG) $save_ok = imagepng($dst_img, $destination);
    elseif ($type === IMAGETYPE_GIF) $save_ok = imagegif($dst_img, $destination);

    imagedestroy($src_img);
    imagedestroy($dst_img);

    if (!$save_ok) {
        return [null, "Erreur lors de la sauvegarde de l'image."];
    }

    return [$new_name, null];
}


/* Update media */
function admin_update_media()
{
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        set_flash('error', 'Accès réservé aux administrateurs.');
        redirect('home/index');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];

        $old = getMediaById($id);
        $oldType = $old['type'];

        $oldBook = $oldType === 'book' && isset($old['book']) ? $old['book'] : [];
        $oldMovie = $oldType === 'movie' && isset($old['movie']) ? $old['movie'] : [];
        $oldGame = $oldType === 'game' && isset($old['game']) ? $old['game'] : [];

        $title = (isset($_POST['title']) && trim($_POST['title']) !== '') ? trim($_POST['title']) : $old['title'];
        $type  = (isset($_POST['type']) && $_POST['type'] !== '') ? $_POST['type'] : $old['type'];
        $genre = (isset($_POST['genre']) && $_POST['genre'] !== '') ? $_POST['genre'] : $old['genre'];
        $stock = (isset($_POST['stock']) && $_POST['stock'] !== '' && is_numeric($_POST['stock'])) ? $_POST['stock'] : $old['stock'];
        $cover = null;

        $author = array_key_exists('author', $_POST) ? (($_POST['author'] !== '') ? trim($_POST['author']) : ($oldBook['author'] ?? null)) : ($oldBook['author'] ?? null);
        $isbn = array_key_exists('isbn', $_POST) ? (($_POST['isbn'] !== '') ? trim($_POST['isbn']) : ($oldBook['isbn'] ?? null)) : ($oldBook['isbn'] ?? null);
        $pages = array_key_exists('pages', $_POST) ? (($_POST['pages'] !== '') ? intval($_POST['pages']) : ($oldBook['pages'] ?? null)) : ($oldBook['pages'] ?? null);
        $year_book = array_key_exists('year_book', $_POST) ? (($_POST['year_book'] !== '') ? intval($_POST['year_book']) : ($oldBook['year'] ?? null)) : ($oldBook['year'] ?? null);
        $summary = array_key_exists('summary', $_POST) ? (($_POST['summary'] !== '') ? trim($_POST['summary']) : ($oldBook['summary'] ?? null)) : ($oldBook['summary'] ?? null);

        $director = array_key_exists('director', $_POST) ? (($_POST['director'] !== '') ? trim($_POST['director']) : ($oldMovie['director'] ?? null)) : ($oldMovie['director'] ?? null);
        $duration = array_key_exists('duration', $_POST) ? (($_POST['duration'] !== '') ? intval($_POST['duration']) : ($oldMovie['duration'] ?? null)) : ($oldMovie['duration'] ?? null);
        $year_movie = array_key_exists('year_movie', $_POST) ? (($_POST['year_movie'] !== '') ? intval($_POST['year_movie']) : ($oldMovie['year'] ?? null)) : ($oldMovie['year'] ?? null);
        $classification = array_key_exists('classification', $_POST) ? (($_POST['classification'] !== '') ? $_POST['classification'] : ($oldMovie['classification'] ?? null)) : ($oldMovie['classification'] ?? null);
        $synopsis = array_key_exists('synopsis', $_POST) ? (($_POST['synopsis'] !== '') ? trim($_POST['synopsis']) : ($oldMovie['synopsis'] ?? null)) : ($oldMovie['synopsis'] ?? null);

        $editor = array_key_exists('editor', $_POST) ? (($_POST['editor'] !== '') ? trim($_POST['editor']) : ($oldGame['publisher'] ?? null)) : ($oldGame['publisher'] ?? null);
        $platform = array_key_exists('platform', $_POST) ? (($_POST['platform'] !== '') ? $_POST['platform'] : ($oldGame['platform'] ?? null)) : ($oldGame['platform'] ?? null);
        $age_min = array_key_exists('age_min', $_POST) ? (($_POST['age_min'] !== '') ? $_POST['age_min'] : ($oldGame['min_age'] ?? null)) : ($oldGame['min_age'] ?? null);
        $description = array_key_exists('description', $_POST) ? (($_POST['description'] !== '') ? trim($_POST['description']) : ($oldGame['description'] ?? null)) : ($oldGame['description'] ?? null);

        // Gestion de l’upload d’image
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
            list($cover_name, $error) = handle_image_upload($_FILES['cover']);
            if ($error) {
                set_flash('error', $error);
                redirect('admin/index');
                exit;
            }
            $cover = '/mediatheque_paris_grp2/public/assets/uploads/covers/' . $cover_name;
        } else {
            $cover = $_POST['cover_old'] ?? $old['cover'];
        }

        $errors = [];
        // Validation : on ne bloque que si le champ est vide ET qu'il n'y a pas de valeur existante
        if (($title === '' || $title === null) && ($old['title'] === '' || $old['title'] === null)) $errors[] = "Le titre est obligatoire.";
        if (($type === '' || $type === null) && ($old['type'] === '' || $old['type'] === null)) $errors[] = "Le type est obligatoire.";
        if (($genre === '' || $genre === null) && ($old['genre'] === '' || $old['genre'] === null)) $errors[] = "Le genre est obligatoire.";
        if ((!is_numeric($stock) || intval($stock) < 1) && (!is_numeric($old['stock']) || intval($old['stock']) < 1)) $errors[] = "Le stock doit être un entier positif (min 1).";

        if ($type === 'book') {
            if (array_key_exists('author', $_POST) && $_POST['author'] !== '' && strlen($author) < 2) $errors[] = "L'auteur est obligatoire (2 caractères minimum).";
            if (array_key_exists('isbn', $_POST) && $_POST['isbn'] !== '' && !preg_match('/^\d{10}(\d{3})?$/', $isbn)) $errors[] = "L'ISBN doit comporter 10 ou 13 chiffres.";
            if (array_key_exists('pages', $_POST) && $_POST['pages'] !== '' && $pages < 1) $errors[] = "Le nombre de pages est obligatoire (min 1).";
            $currentYear = intval(date('Y'));
            if (array_key_exists('year_book', $_POST) && $_POST['year_book'] !== '' && ($year_book < 1900 || $year_book > $currentYear)) $errors[] = "L'année de publication doit être comprise entre 1900 et $currentYear.";
        } elseif ($type === 'movie') {
            if (array_key_exists('director', $_POST) && $_POST['director'] !== '' && strlen($director) < 2) $errors[] = "Le réalisateur est obligatoire (2 caractères minimum).";
            if (array_key_exists('duration', $_POST) && $_POST['duration'] !== '' && $duration < 1) $errors[] = "La durée est obligatoire (min 1 minute).";
            $currentYear = intval(date('Y'));
            if (array_key_exists('year_movie', $_POST) && $_POST['year_movie'] !== '' && ($year_movie < 1900 || $year_movie > $currentYear)) $errors[] = "L'année du film doit être comprise entre 1900 et $currentYear.";
            if (array_key_exists('classification', $_POST) && $_POST['classification'] !== '' && !in_array($classification, ['Tous publics', '-12', '-16', '-18'])) $errors[] = "Classification obligatoire (Tous publics, -12, -16, -18).";
        } elseif ($type === 'game') {
            if (array_key_exists('editor', $_POST) && $_POST['editor'] !== '' && strlen($editor) < 2) $errors[] = "L'éditeur est obligatoire (2 caractères minimum).";
            if (array_key_exists('platform', $_POST) && $_POST['platform'] !== '' && !in_array($platform, ['PC', 'PlayStation', 'Xbox', 'Nintendo', 'Mobile'])) $errors[] = "Plateforme obligatoire (PC, PlayStation, Xbox, Nintendo, Mobile).";
            if (array_key_exists('age_min', $_POST) && $_POST['age_min'] !== '' && !in_array($age_min, ['3', '7', '12', '16', '18'])) $errors[] = "Âge minimum obligatoire (3, 7, 12, 16, 18).";
        }

        if ($errors) {
            set_flash('error', implode('<br>', $errors));
            redirect('admin');
            exit;
        }

        updateMedia(
            $id,
            $title,
            $type,
            $genre,
            $stock,
            $cover,
            $author,
            $isbn,
            $pages,
            $year_book,
            $director,
            $duration,
            $classification,
            $editor,
            $platform,
            $age_min,
            $summary,
            $synopsis,
            $description
        );
        set_flash('success', 'Média mis à jour.');
    }

    redirect('admin');
}

// ----------- Delete Media -----------
function admin_delete_media($id)
{
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        set_flash('error', 'Accès réservé aux administrateurs.');
        redirect('home/index');
        exit;
    }
    $result = deleteMedia($id);
    if ($result === 'has_borrows') {
        set_flash('error', "Impossible de supprimer ce média : il est encore emprunté.");
    } elseif ($result) {
        set_flash('success', 'Média supprimé avec succès.');
    } else {
        set_flash('error', 'Erreur lors de la suppression du média.');
    }
    redirect('admin');
}

// ----------- Delete User -----------
function admin_delete_user($id)
{
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        set_flash('error', 'Accès réservé aux administrateurs.');
        redirect('home/index');
        exit;
    }
    $result = deleteUser($id);
    if ($result === 'has_borrows') {
        set_flash('error', "Impossible de supprimer cet utilisateur : il a des emprunts dans l'historique.");
    } elseif ($result) {
        set_flash('success', 'Utilisateur supprimé avec succès.');
    } else {
        set_flash('error', 'Erreur lors de la suppression de l\'utilisateur.');
    }
    redirect('admin');
}

// -----------Borrows -----------
function admin_force_return($borrow_id)
{
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        set_flash('error', 'Accès réservé aux administrateurs.');
        redirect('admin/index');
        exit;
    }
    if (forceReturnBorrow($borrow_id)) {
        set_flash('success', 'Retour forcé effectué.');
    } else {
        set_flash('error', 'Erreur lors du retour.');
    }
    redirect('admin/index');
}

// ----------- Statistiques-----------
function admin_index()
{
    $is_admin = (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin');

    $medias  = $is_admin ? getAllMedias() : [];
    $users   = $is_admin ? getAllUsers() : [];
    $borrows = $is_admin ? getAllBorrowsWithUserAndMedia() : [];

    $stats = $is_admin ? [
        'total_medias'   => countMedias(),
        'total_users'    => countUsers(),
        'total_borrows'  => countBorrows(),
        'late_borrows'   => countLateBorrows(),
    ] : [];

    $user_borrow_stats = $is_admin ? getUserBorrowStats() : [];
    $late_borrows      = $is_admin ? getLateBorrows() : [];
    $medias_by_type    = $is_admin ? mediasByType() : [];

    $data = [
        'medias'            => $medias,
        'users'             => $users,
        'borrows'           => $borrows,
        'stats'             => $stats,
        'user_borrow_stats' => $user_borrow_stats,
        'countLateBorrows'  => $stats['late_borrows'] ?? 0,
        'late_borrows'      => $late_borrows,
        'medias_by_type'    => $medias_by_type,
        'title'             => 'Administration',
        'is_admin'          => $is_admin
    ];

    load_view_with_layout('admin/index', $data);
}

