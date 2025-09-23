<?php
require_once __DIR__ . '/../core/database.php';

// ----------- Statistiques d'utilisation par utilisateur -----------
function getUserBorrowStats()
{
    $db = db_connect();
    $sql = "SELECT u.id, u.email,
        COUNT(b.id) as total_borrows,
        SUM(CASE WHEN b.id IS NOT NULL AND b.returned_at IS NULL THEN 1 ELSE 0 END) as active_borrows,
        SUM(CASE WHEN b.id IS NOT NULL AND b.returned_at IS NOT NULL THEN 1 ELSE 0 END) as returned_borrows,
        SUM(CASE WHEN b.id IS NOT NULL AND b.returned_at IS NULL AND b.due_date < NOW() THEN 1 ELSE 0 END) as late_borrows
        FROM users u
        LEFT JOIN borrows b ON b.user_id = u.id
        GROUP BY u.id, u.email
        ORDER BY total_borrows DESC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ----------- Emprunts en cours -----------
function getAllBorrowsWithUserAndMedia()
{
    $db = db_connect();
    $sql = "SELECT b.id, b.user_id, b.media_id, b.borrowed_at, b.due_date,
                   u.email as user_email, m.title as media_title
            FROM borrows b
            JOIN users u ON b.user_id = u.id
            JOIN medias m ON b.media_id = m.id
            WHERE b.returned_at IS NULL
            ORDER BY b.due_date ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ----------- Compteurs généraux -----------
function countMedias()
{
    $db = db_connect();
    $stmt = $db->query("SELECT COUNT(*) as total FROM medias");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function countUsers()
{
    $db = db_connect();
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function countBorrows()
{
    $db = db_connect();
    $stmt = $db->query("SELECT COUNT(*) as total FROM borrows WHERE returned_at IS NULL");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

// ----------- Médias par type -----------
function mediasByType()
{
    $db = db_connect();
    $sql = "SELECT type, COUNT(*) as total FROM medias GROUP BY type";
    $stmt = $db->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $out = [];
    foreach ($result as $row) {
        $out[$row['type']] = $row['total'];
    }
    return $out;
}

// ----------- Emprunts en retard -----------

// Retourne uniquement le nombre
function countLateBorrows()
{
    $db = db_connect();
    $sql = "SELECT COUNT(*) as total
            FROM borrows
            WHERE returned_at IS NULL AND due_date < NOW()";
    $stmt = $db->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

// Retourne la liste détaillée
function getLateBorrows()
{
    $db = db_connect();
    $sql = "SELECT b.id, b.user_id, b.media_id, b.borrowed_at, b.due_date,
                   u.email as user_email, m.title as media_title
            FROM borrows b
            JOIN users u ON b.user_id = u.id
            JOIN medias m ON b.media_id = m.id
            WHERE b.returned_at IS NULL AND b.due_date < NOW()
            ORDER BY b.due_date ASC";
    $stmt = $db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ----------- Retour forcé d'un emprunt -----------
function forceReturnBorrow($borrow_id)
{
    $db = db_connect();

    $stmt = $db->prepare("SELECT * FROM borrows WHERE id = ?");
    $stmt->execute([$borrow_id]);
    $borrow = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$borrow) return false;

    $media_id = $borrow['media_id'];

    $stmt = $db->prepare("UPDATE borrows SET returned_at = NOW(), status = 'Rendu' WHERE id = ?");
    $ok1 = $stmt->execute([$borrow_id]);

    $stmt = $db->prepare("UPDATE medias SET stock = stock + 1 WHERE id = ?");
    $ok2 = $stmt->execute([$media_id]);

    return $ok1 && $ok2;
}
