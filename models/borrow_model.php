
<?php

function get_user_borrows($user_id)
{
    $sql = "SELECT * FROM borrows WHERE user_id = ? AND returned_at IS NULL";
    return db_select($sql, [$user_id]);
}

function count_user_active_borrows($user_id)
{
    $sql = "SELECT COUNT(*) as count FROM borrows WHERE user_id = ? AND returned_at IS NULL";
    $row = db_select_one($sql, [$user_id]);
    return $row ? $row['count'] : 0;
}

function is_media_borrowed($media_id)
{
    $sql = "SELECT COUNT(*) as count FROM borrows WHERE media_id = ? AND returned_at IS NULL";
    $row = db_select_one($sql, [$media_id]);
    return $row && $row['count'] > 0;
}

function borrow_media($user_id, $media_id, $due_date)
{
    $sql = "INSERT INTO borrows (user_id, media_id, borrowed_at, due_date) VALUES (?, ?, NOW(), ?)";
    return db_execute($sql, [$user_id, $media_id, $due_date]);
}

function return_media($borrow_id)
{
    $sql = "UPDATE borrows SET returned_at = NOW() WHERE id = ?";
    return db_execute($sql, [$borrow_id]);
}

function get_borrow_history($user_id)
{
    $sql = "SELECT b.*, m.title FROM borrows b JOIN medias m ON b.media_id = m.id WHERE b.user_id = ? ORDER BY b.borrowed_at DESC";
    return db_select($sql, [$user_id]);
}

function get_borrow_by_media_and_user($media_id, $user_id)
{
    $sql = "SELECT * FROM borrows WHERE media_id = ? AND user_id = ? AND returned_at IS NULL";
    return db_select_one($sql, [$media_id, $user_id]);
}
