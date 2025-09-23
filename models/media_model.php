
<?php

require_once __DIR__ . '/../core/database.php';

// Décrémente le stock d'un média (si stock > 0)
function decrement_media_stock($media_id)
{
    $sql = "UPDATE medias SET stock = stock - 1 WHERE id = ? AND stock > 0";
    return db_execute($sql, [$media_id]);
}

// Incrémente le stock d'un média
function increment_media_stock($media_id)
{
    $sql = "UPDATE medias SET stock = stock + 1 WHERE id = ?";
    return db_execute($sql, [$media_id]);
}

function search_catalogue($motCle, $type, $genre, $dispo, $limit = 20, $offset = 0)
{
    $sql = "SELECT * FROM medias WHERE 1=1";
    $params = [];

    if ($motCle) {
        $sql .= " AND title LIKE ?";
        $params[] = '%' . $motCle . '%';
    }
    if ($type) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }
    if ($genre) {
        $sql .= " AND genre = ?";
        $params[] = $genre;
    }
    if ($dispo !== '') {
        if ($dispo == '1') {
            $sql .= " AND stock > 0";
        } else {
            $sql .= " AND stock = 0";
        }
    }

    $sql .= " ORDER BY created_at ASC LIMIT ? OFFSET ?";
    $params[] = (int)$limit;
    $params[] = (int)$offset;

    return db_select($sql, $params);
}

function count_catalogue($motCle, $type, $genre, $dispo)
{
    $sql = "SELECT COUNT(*) as total FROM medias WHERE 1=1";
    $params = [];

    if ($motCle) {
        $sql .= " AND title LIKE ?";
        $params[] = '%' . $motCle . '%';
    }
    if ($type) {
        $sql .= " AND type = ?";
        $params[] = $type;
    }
    if ($genre) {
        $sql .= " AND genre = ?";
        $params[] = $genre;
    }
    if ($dispo !== '') {
        if ($dispo == '1') {
            $sql .= " AND stock > 0";
        } else {
            $sql .= " AND stock = 0";
        }
    }

    $row = db_select_one($sql, $params);
    return $row ? (int)$row['total'] : 0;
}
function get_all_medias()
{
    $sql = "SELECT * FROM medias";
    return db_select($sql);
}

function get_media_by_id($id)
{
    $sql = "SELECT * FROM medias WHERE id = ?";
    return db_select_one($sql, [$id]);
}

function create_media($title, $type, $genre, $stock)
{
    $sql = "INSERT INTO medias (title, type, genre, stock) VALUES (?, ?, ?, ?)";
    if (db_execute($sql, [$title, $type, $genre, $stock])) {
        return db_last_insert_id();
    }
    return false;
}

// Fonction pour récupérer tous les détails d'un média (général + spécifiques)
function get_media_full_details($media_id)
{
    // Récupérer les infos générales du média
    $media = get_media_by_id($media_id);

    if (!$media) {
        return null;
    }

    // Récupérer les détails spécifiques selon le type
    $details = [];
    $type = $media['type'];
    $missing_details = false;
    switch ($type) {
        case 'book':
            $sql = "SELECT * FROM books WHERE media_id = ?";
            $details = db_select_one($sql, [$media_id]);
            if (!$details) $missing_details = true;
            break;
        case 'movie':
            $sql = "SELECT * FROM movies WHERE media_id = ?";
            $details = db_select_one($sql, [$media_id]);
            if (!$details) $missing_details = true;
            break;
        case 'game':
            $sql = "SELECT * FROM games WHERE media_id = ?";
            $details = db_select_one($sql, [$media_id]);
            if (!$details) $missing_details = true;
            break;
    }

    $result = array_merge($media, $details ? $details : []);
    if ($missing_details) {
        $result['__missing_details'] = true;
    }
    return $result;
}
