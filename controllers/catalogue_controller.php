<?php


require_once __DIR__ . '/../models/media_model.php';

function catalogue_index()
{
    $motCle = $_GET['q'] ?? '';
    $type   = $_GET['type'] ?? '';
    $genre  = $_GET['genre'] ?? '';
    $dispo  = $_GET['dispo'] ?? '';
    $page   = max(1, intval($_GET['page'] ?? 1));
    $perPage = 20;
    $offset = ($page - 1) * $perPage;

    // Appel au modèle avec pagination
    $medias = search_catalogue($motCle, $type, $genre, $dispo, $perPage, $offset);
    $total  = count_catalogue($motCle, $type, $genre, $dispo);

    // Préparation des données pour la vue
    $data = [
        'medias' => $medias,
        'title'  => 'Catalogue',
        'motCle' => $motCle,
        'type'   => $type,
        'genre'  => $genre,
        'dispo'  => $dispo,
        'page'   => $page,
        'perPage'=> $perPage,
        'total'  => $total,
    ];
    // Chargement de la vue avec le layout principal
    load_view_with_layout('catalogue/index', $data);
}