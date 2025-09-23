<?php

require_once __DIR__ . '/../core/database.php';

// ----------- MÉDIAS ----------- //
function getAllMedias()
{
    $db = db_connect();
    $stmt = $db->query("SELECT * FROM medias ORDER BY created_at ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMediaById($id)
{
    $db = db_connect();
    $stmt = $db->prepare("SELECT * FROM medias WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Nouvelle version de addMedia avec champs spécifiques
function addMedia(
    $title,
    $type,
    $genre,
    $stock,
    $cover,
    $author = null,
    $isbn = null,
    $pages = null,
    $year = null,
    $director = null,
    $duration = null,
    $classification = null,
    $editor = null,
    $platform = null,
    $age_min = null,
    $summary = null,
    $synopsis = null,
    $description = null
) {
    $db = db_connect();
    // 1. Insertion dans medias
    $stmt = $db->prepare("INSERT INTO medias (title, type, genre, stock, cover, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$title, $type, $genre, $stock, $cover]);
    $media_id = $db->lastInsertId();

    // 2. Insertion dans la table spécifique
    if ($type === 'book') {
        $stmt2 = $db->prepare("INSERT INTO books (media_id, author, isbn, pages, year, summary) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$media_id, $author, $isbn, $pages, $year, $summary]);
    } elseif ($type === 'movie') {
        $stmt2 = $db->prepare("INSERT INTO movies (media_id, director, year, duration, classification, synopsis) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$media_id, $director, $year, $duration, $classification, $synopsis]);
    } elseif ($type === 'game') {
        $stmt2 = $db->prepare("INSERT INTO games (media_id, publisher, platform, min_age, description) VALUES (?, ?, ?, ?, ?)");
        $stmt2->execute([$media_id, $editor, $platform, $age_min, $description]);
    }
    return $media_id;
}

// Vérifie l’unicité de l’ISBN
function isbnExists($isbn)
{
    if (!$isbn) return false;
    $db = db_connect();
    $stmt = $db->prepare("SELECT COUNT(*) FROM books WHERE isbn = ?");
    $stmt->execute([$isbn]);
    return $stmt->fetchColumn() > 0;
}

// Update media 
function updateMedia(
    $id,
    $title,
    $type,
    $genre,
    $stock,
    $cover,
    $author = null,
    $isbn = null,
    $pages = null,
    $year_book = null,
    $director = null,
    $duration = null,
    $classification = null,
    $editor = null,
    $platform = null,
    $age_min = null,
    $summary = null,
    $synopsis = null,
    $description = null
) {
    $db = db_connect();

    // Récupérer l'ancien cover pour suppression si remplacé
    $oldMedia = getMediaById($id);
    $oldCover = $oldMedia['cover'] ?? null;

    $stmt = $db->prepare("UPDATE medias 
                          SET title=?, type=?, genre=?, stock=?, cover=?, updated_at=NOW() 
                          WHERE id=?");
    $success = $stmt->execute([$title, $type, $genre, $stock, $cover, $id]);

    // Supprimer l'ancien fichier image si remplacé
    if ($success && $cover && $oldCover && $cover !== $oldCover) {
        $file = __DIR__ . '/../uploads/covers/' . $oldCover;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // Mettre à jour la table spécifique selon le type
    if ($type === 'book') {
        // Charger les anciennes valeurs si besoin
        $oldBook = $db->prepare("SELECT * FROM books WHERE media_id = ?");
        $oldBook->execute([$id]);
        $oldBookData = $oldBook->fetch(PDO::FETCH_ASSOC);
        $stmt2 = $db->prepare("UPDATE books SET author=?, isbn=?, pages=?, year=?, summary=? WHERE media_id=?");
        $stmt2->execute([
            $author !== null ? $author : ($oldBookData['author'] ?? ''),
            $isbn !== null ? $isbn : ($oldBookData['isbn'] ?? ''),
            $pages !== null ? $pages : ($oldBookData['pages'] ?? 0),
            $year_book !== null ? $year_book : ($oldBookData['year'] ?? 0),
            $summary !== null ? $summary : ($oldBookData['summary'] ?? ''),
            $id
        ]);
    } elseif ($type === 'movie') {
        $oldMovie = $db->prepare("SELECT * FROM movies WHERE media_id = ?");
        $oldMovie->execute([$id]);
        $oldMovieData = $oldMovie->fetch(PDO::FETCH_ASSOC);
        $stmt2 = $db->prepare("UPDATE movies SET director=?, year=?, duration=?, classification=?, synopsis=? WHERE media_id=?");
        $stmt2->execute([
            $director !== null ? $director : ($oldMovieData['director'] ?? ''),
            $year_book !== null ? $year_book : ($oldMovieData['year'] ?? 0),
            $duration !== null ? $duration : ($oldMovieData['duration'] ?? 0),
            $classification !== null ? $classification : ($oldMovieData['classification'] ?? ''),
            $synopsis !== null ? $synopsis : ($oldMovieData['synopsis'] ?? ''),
            $id
        ]);
    } elseif ($type === 'game') {
        $oldGame = $db->prepare("SELECT * FROM games WHERE media_id = ?");
        $oldGame->execute([$id]);
        $oldGameData = $oldGame->fetch(PDO::FETCH_ASSOC);
        $stmt2 = $db->prepare("UPDATE games SET publisher=?, platform=?, min_age=?, description=? WHERE media_id=?");
        $stmt2->execute([
            $editor !== null ? $editor : ($oldGameData['publisher'] ?? ''),
            $platform !== null ? $platform : ($oldGameData['platform'] ?? ''),
            $age_min !== null ? $age_min : ($oldGameData['min_age'] ?? ''),
            $description !== null ? $description : ($oldGameData['description'] ?? ''),
            $id
        ]);
    }

    return $success;
}
// Delete media
function deleteMedia($id)
{
    $db = db_connect();
  
    $stmt = $db->prepare("SELECT COUNT(*) as nb FROM borrows WHERE media_id = ?");
    $stmt->execute([$id]);
    $nb = $stmt->fetch(PDO::FETCH_ASSOC)['nb'] ?? 0;
    if ($nb > 0) {
   
        return 'has_borrows';
    }
    $stmt = $db->prepare("DELETE FROM medias WHERE id = ?");
    return $stmt->execute([$id]) ? true : false;
}

// USER //
function getAllUsers()
{
    $db = db_connect();
    $stmt = $db->query("SELECT * FROM users ORDER BY created_at ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserById($id)
{
    $db = db_connect();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Delete User
function deleteUser($id)
{
    $db = db_connect();
    // Vérifier s'il existe des emprunts pour cet utilisateur
    $stmt = $db->prepare("SELECT COUNT(*) as nb FROM borrows WHERE user_id = ?");
    $stmt->execute([$id]);
    $nb = $stmt->fetch(PDO::FETCH_ASSOC)['nb'] ?? 0;
    if ($nb > 0) {
        // On ne supprime pas, on signale l'erreur
        return 'has_borrows';
    }
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]) ? true : false;
}
