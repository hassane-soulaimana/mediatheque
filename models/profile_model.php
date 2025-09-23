<?php


function get_profile_by_id($user_id)
{
    $query = "SELECT id, lastname, name, email FROM users WHERE id = ?";
    return db_select_one($query, [$user_id]);
}

function update_profile($user_id, $lastname, $name, $email, $hashed_password)
{
    if ($hashed_password) {
        $query = "UPDATE users SET lastname = ?, name = ?, email = ?, password = ? WHERE id = ?";
        return db_execute($query, [$lastname, $name, $email, $hashed_password, $user_id]);
    } else {
        $query = "UPDATE users SET lastname = ?, name = ?, email = ? WHERE id = ?";
        return db_execute($query, [$lastname, $name, $email, $user_id]);
    }
}
