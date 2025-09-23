<?php

/**
 * Page de connexion
 */
function auth_login()
{

    // Rediriger si déjà connecté
    if (is_logged_in()) {
        redirect('home');
    }

    $data = [
        'title' => 'Connexion'
    ];

    if (is_post()) {
        $email = clean_input(post('email'));
        $password = post('password');

        if (empty($email) || empty($password)) {
            set_flash('error', 'Email et mot de passe obligatoires.');
        } else {
            // Rechercher l'utilisateur
            $user = get_user_by_email($email);


            if ($user && verify_password($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_lastname'] = $user['lastname'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'] ?? 'user';

                set_flash('success', 'Connexion réussie !');
                redirect('home');
            } else {
                set_flash('error', 'Email ou mot de passe incorrect.');
            }
        }
    }

    load_view_with_layout('auth/login', $data);
}

/**
 * Page d'inscription
 */
function auth_register()
{
    // Rediriger si déjà connecté
    if (is_logged_in()) {
        redirect('home');
    }

    $data = [
        'title' => 'Inscription'
    ];

    if (is_post()) {
        $lastname = clean_input(post('lastname'));
        $name = clean_input(post('name'));
        $email = clean_input(post('email'));
        $password = post('password');
        $confirm_password = post('confirm_password');

        $errors = [];
        // Champs obligatoires
        if (empty($lastname) || empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = 'Tous les champs sont obligatoires.';
        }
        // Nom/prénom alpha (lettres, tirets, espaces)
        if (!empty($lastname) && !preg_match('/^[\p{L} \-]+$/u', $lastname)) {
            $errors[] = 'Le nom ne doit contenir que des lettres, espaces ou tirets.';
        }
        if (!empty($name) && !preg_match('/^[\p{L} \-]+$/u', $name)) {
            $errors[] = 'Le prénom ne doit contenir que des lettres, espaces ou tirets.';
        }
        // Email valide
        if (!empty($email) && !validate_email($email)) {
            $errors[] = 'Adresse email invalide.';
        }
        // Email unique
        if (!empty($email) && get_user_by_email($email)) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        }
        // Mot de passe fort : 8+ caractères, majuscule, minuscule, chiffre
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins une majuscule.';
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins une minuscule.';
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
            }
        }
        // Confirmation mot de passe
        if (!empty($password) && $password !== $confirm_password) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (!empty($errors)) {
            foreach ($errors as $err) {
                set_flash('error', $err);
            }
        } else {
            // Créer l'utilisateur
            $user_id = create_user($lastname, $name, $email, $password);
            if ($user_id) {
                set_flash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                redirect('auth/login');
            } else {
                set_flash('error', 'Erreur lors de l\'inscription.');
            }
        }
    }

    load_view_with_layout('auth/register', $data);
}

/**
 * Déconnexion
 */
function auth_logout()
{
    logout();
}
