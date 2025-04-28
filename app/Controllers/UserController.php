<?php
namespace App\Controllers;

require_once __DIR__.'/../../vendor/autoload.php';
use App\Core\Controller;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller {

    public function index() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/user/login');
        }
        $this->redirect('/dashboard');
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $data = [
            'error' => $_SESSION['login_error'] ?? '',
            'old' => $_SESSION['old_input'] ?? ['username' => '']
        ];

        // Nettoyage des données de session
        if(isset($_SESSION['login_error'])) unset($_SESSION['login_error']);
        if(isset($_SESSION['old_input'])) unset($_SESSION['old_input']);

        $this->view('user/login-view', $data);
    }

    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $data = [
            'errors' => $_SESSION['register_errors'] ?? [],
            'old' => $_SESSION['old_input'] ?? []
        ];

        // Nettoyage des données de session
        if(isset($_SESSION['register_errors'])) unset($_SESSION['register_errors']);
        if(isset($_SESSION['old_input'])) unset($_SESSION['old_input']);

        $this->view('user/register-view', $data);
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('/user/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/user/login');
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Conserver les anciennes valeurs pour remplir le formulaire
        $_SESSION['old_input'] = ['username' => $username];

        // Validation de base
        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Veuillez remplir tous les champs';
            $this->redirect('/user/login');
        }

        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user->getPasswordHash())) {
            $_SESSION['userId'] = $user->getId();
            $_SESSION['username'] = $user->getUserName();
            $this->redirect('/dashboard');
        } else {
            $_SESSION['login_error'] = 'Identifiants incorrects';
            $this->redirect('/user/login');
        }
    }

    public function processRegistration() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/user/register');
        }

        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $dateNaissance = $_POST['date_naissance'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $role_id = $_POST['role_id'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Conserver les anciennes valeurs
        $_SESSION['old_input'] = [
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naissance' => $dateNaissance,
            'username' => $username,
            'email' => $email,
            'role_id' => $role_id
        ];

        $errors = [];

        // Validation des champs obligatoires
        if (empty($nom)) $errors['nom'] = 'Le nom est obligatoire';
        if (empty($prenom)) $errors['prenom'] = 'Le prénom est obligatoire';
        if (empty($dateNaissance)) $errors['date_naissance'] = 'La date de naissance est obligatoire';
        if (empty($username)) $errors['username'] = 'Le nom d\'utilisateur est obligatoire';
        if (empty($email)) $errors['email'] = 'L\'email est obligatoire';
        if(empty($role_id)) $errors['role_id'] = 'Le role est obligatoire';
        if (empty($password)) $errors['password'] = 'Le mot de passe est obligatoire';
        if (empty($confirmPassword)) $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire';

        // Validation email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format d\'email invalide';
        }

        // Validation du mot de passe
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères';
            } elseif (!preg_match('/[A-Z]/', $password)) {
                $errors['password'] = 'Le mot de passe doit contenir au moins une majuscule';
            } elseif (!preg_match('/[a-z]/', $password)) {
                $errors['password'] = 'Le mot de passe doit contenir au moins une minuscule';
            } elseif (!preg_match('/\d/', $password)) {
                $errors['password'] = 'Le mot de passe doit contenir au moins un chiffre';
            } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
                $errors['password'] = 'Le mot de passe doit contenir au moins un caractère spécial';
            }

            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
            }
        }

        // Vérification d'unicité
        if (!empty($username) && User::findByUsername($username)) {
            $errors['username'] = 'Ce nom d\'utilisateur est déjà utilisé';
        }

        if (!empty($email) && User::findByEmail($email)) {
            $errors['email'] = 'Cette adresse email est déjà utilisée';
        }

        // S'il y a des erreurs, rediriger vers le formulaire
        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;
            $this->redirect('/user/register');
        }

        // Création du compte
        $user = new User(
            '',
            $nom,
            $prenom,
            new \DateTime($dateNaissance),
            $username,
            $email,
            $role_id,
            0,
            password_hash($password, PASSWORD_DEFAULT),
            0,
            0
        );

        if ($userId = $user->save()) {
            $_SESSION['userId'] = $userId;
            $_SESSION['username'] = $username;
            $this->redirect('/dashboard');
        } else {
            $_SESSION['register_errors'] = ['general' => 'Erreur lors de la création du compte'];
            $this->redirect('/user/register');
        }
    }
}