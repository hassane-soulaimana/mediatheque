
<?php
require_once MODEL_PATH . '/borrow_model.php';
require_once MODEL_PATH . '/media_model.php';

function borrow_borrow($media_id)
{
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté pour emprunter.');
        redirect('auth/login');
        exit;
    }
    $user_id = $_SESSION['user_id'];
    if (count_user_active_borrows($user_id) >= 3) {
        set_flash('error', 'Vous avez déjà 3 emprunts en cours.');
        redirect('catalogue');
        exit;
    }
    $media = get_media_by_id($media_id);
    if (!$media || $media['stock'] <= 0) {
        set_flash('error', 'Ce média n\'est plus disponible.');
        redirect('catalogue');
        exit;
    }
    // On ne vérifie plus is_media_borrowed : seul le stock limite les emprunts simultanés
    $due_date = date('Y-m-d', strtotime('+14 days'));
    if (borrow_media($user_id, $media_id, $due_date)) {
        decrement_media_stock($media_id);
        set_flash('success', 'Emprunt validé ! Retour prévu le ' . $due_date);
        // TODO: envoyer un email si besoin
    } else {
        set_flash('error', 'Erreur lors de l\'emprunt.');
    }
    redirect('catalogue');
}

function borrow_return($borrow_id)
{
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté.');
        redirect('auth/login');
        exit;
    }
    // On récupère l'id du média pour ré-incrémenter le stock
    $borrow = db_select_one("SELECT * FROM borrows WHERE id = ?", [$borrow_id]);
    if (return_media($borrow_id)) {
        if ($borrow && isset($borrow['media_id'])) {
            increment_media_stock($borrow['media_id']);
        }
        set_flash('success', 'Média rendu avec succès.');
    } else {
        set_flash('error', 'Erreur lors du retour.');
    }
    redirect('catalogue');
}

function borrow_history()
{
    if (!is_logged_in()) {
        set_flash('error', 'Vous devez être connecté.');
        redirect('auth/login');
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $history = get_borrow_history($user_id);
    load_view_with_layout('borrow/history', ['history' => $history]);
}
