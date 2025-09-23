<?php
require_once __DIR__ . '/../models/media_model.php';

function detail_index($id = null)
{
    if ($id === null) {
        $id = $_GET['id'] ?? 0;
    }
    if (!$id) {
        header('Location: /catalogue');
        exit;
    }
    $media = get_media_full_details($id);
    if (!$media) {
        header('Location: /catalogue');
        exit;
    }
    $data = [
        'media' => $media,
        'title' => 'Détails - ' . $media['title']
    ];
    // Chargement de la vue avec le layout principal
    load_view_with_layout('detail/index', $data);
}
