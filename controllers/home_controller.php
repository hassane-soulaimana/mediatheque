<?php
// Contrôleur pour la page d'accueil

/**
 * Page d'accueil
 */
function home_index()
{
    require_once __DIR__ . '/../models/media_model.php';
    $books = search_catalogue('', 'book', '', '', 3, 0);
    $movies = search_catalogue('', 'movie', '', '', 3, 0);
    $games = search_catalogue('', 'game', '', '', 3, 0);
    $data = [
        'title' => 'Accueil',
        'books' => $books,
        'movies' => $movies,
        'games' => $games
    ];
    load_view_with_layout('home/index', $data);
}

/**
 * Page à propos
 */
function home_about()
{
    $data = [
        'title' => 'À propos',
        'content' => 'Cette application est un starter kit PHP MVC développé avec une approche procédurale.'
    ];

    load_view_with_layout('home/about', $data);
}

/**
 * Page contact
 */
function home_contact()
{
    $data = [
        'title' => 'Contact'
    ];

    if (is_post()) {
        $name = clean_input(post('name'));
        $email = clean_input(post('email'));
        $message = clean_input(post('message'));

        // Validation simple
        if (empty($name) || empty($email) || empty($message)) {
            set_flash('error', 'Tous les champs sont obligatoires.');
        } elseif (!validate_email($email)) {
            set_flash('error', 'Adresse email invalide.');
        } else {
            // Ici vous pourriez envoyer l'email ou sauvegarder en base
            set_flash('success', 'Votre message a été envoyé avec succès !');
            redirect('home/contact');
        }
    }

    load_view_with_layout('home/contact', $data);
}

/**
 * Page profile
 */
function home_profile()
{

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        redirect('auth/login'); // redirige vers page de connexion si pas connecté
        exit;
    }

    $user_id = $_SESSION['user_id'];


    if (is_post()) {
        $lastname = clean_input(post('lastname'));
        $name = clean_input(post('name'));
        $email = clean_input(post('email'));
        $password = clean_input(post('password'));
        $password_confirm = clean_input(post('password_confirm'));

        // Vérification des champs obligatoires (hors mot de passe)
        if (empty($lastname) || empty($name) || empty($email)) {
            set_flash('error', 'Nom, prénom et email sont obligatoires.');
            redirect('home/profile');
            exit;
        }

        // Validation de l'email
        if (!validate_email($email)) {
            set_flash('error', 'Adresse email invalide.');
            redirect('home/profile');
            exit;
        }

        // Si l'utilisateur souhaite changer de mot de passe
        if (!empty($password)) {
            if ($password !== $password_confirm) {
                set_flash('error', 'Les mots de passe ne correspondent pas.');
                redirect('home/profile');
                exit;
            }
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $hashed_password = null;
        }

        if (update_profile($user_id, $lastname, $name, $email, $hashed_password)) {
            set_flash('success', 'Profil mis à jour avec succès !');
        } else {
            set_flash('error', 'Erreur lors de la mise à jour du profil.');
        }

        redirect('home/profile');
        exit;
    }

    $profile = get_profile_by_id($user_id);

    $data = [
        'title' => 'Profile',
        'message' => 'Bienvenue sur votre profil',
        'content' => 'Modifiez vos informations ci-dessous.',
        'profile' => $profile
    ];

    load_view_with_layout('home/profile', $data);
}


/**
 * Page test
 */
function home_test()
{
    $data = [
        'title' => 'Page test',
        'message' => 'Bienvenue sur votre page test',
    ];

    load_view_with_layout('home/test', $data);
}
