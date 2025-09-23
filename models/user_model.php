<?php
// Modèle pour les utilisateurs

/**
 * Récupère un utilisateur par son email
 */
function get_user_by_email($email)
{
    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
    return db_select_one($query, [$email]);
}

/**
 * Récupère un utilisateur par son ID
 */
function get_user_by_id($id)
{
    $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
    return db_select_one($query, [$id]);
}

/**
 * Crée un nouvel utilisateur
 */
function create_user($lastname, $name, $email, $password)
{
    $hashed_password = hash_password($password);
    $query = "INSERT INTO users (lastname, name, email, password, created_at) VALUES (?, ?, ?,?, NOW())";

    if (db_execute($query, [$lastname, $name, $email, $hashed_password])) {
        return db_last_insert_id();
    }

    return false;
}

/**
 * Met à jour un utilisateur
 */
function update_user($id, $lastname, $name, $email)
{
    $query = "UPDATE users SET lastname = ?, name= ?, email = ?, updated_at = NOW() WHERE id = ?";
    return db_execute($query, [$lastname, $name, $email, $id]);
}
/**
 * Met à jour le mot de passe d'un utilisateur
 */
function update_user_password($id, $password)
{
    $hashed_password = hash_password($password);
    $query = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
    return db_execute($query, [$hashed_password, $id]);
}

/**
 * Supprime un utilisateur
 */
function delete_user($id)
{
    $query = "DELETE FROM users WHERE id = ?";
    return db_execute($query, [$id]);
}

/**
 * Récupère tous les utilisateurs
 */
function get_all_users($limit = null, $offset = 0)
{
    $query = "SELECT id, lastname, name, email, created_at FROM users ORDER BY created_at DESC";

    if ($limit !== null) {
        $query .= " LIMIT $offset, $limit";
    }

    return db_select($query);
}

/**
 * Compte le nombre total d'utilisateurs
 */
function count_users()
{
    $query = "SELECT COUNT(*) as total FROM users";
    $result = db_select_one($query);
    return $result['total'] ?? 0;
}

/**
 * Vérifie si un email existe déjà
 */
function email_exists($email, $exclude_id = null)
{
    $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $params = [$email];

    if ($exclude_id) {
        $query .= " AND id != ?";
        $params[] = $exclude_id;
    }

    $result = db_select_one($query, $params);
    return $result['count'] > 0;
}
