<?php
class AuthController extends Controller
{
    private UserModel $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new UserModel();
    }

    public function login(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $error = null;

        if ($this->isPost()) {
            $email    = $this->post('email', '');
            $password = $this->post('password', '');

            if (empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                $user = $this->users->verify($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user']    = [
                        'id'    => $user['id'],
                        'name'  => $user['name'],
                        'email' => $user['email'],
                    ];
                    $this->redirect('dashboard');
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            }
        }

        $this->render('auth/login', ['error' => $error], false);
    }

    public function register(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $error = null;

        if ($this->isPost()) {
            $name     = $this->post('name', '');
            $email    = $this->post('email', '');
            $password = $this->post('password', '');
            $confirm  = $this->post('confirm', '');

            if (empty($name) || empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } elseif ($password !== $confirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif (strlen($password) < 6) {
                $error = 'Le mot de passe doit faire au moins 6 caractères.';
            } elseif ($this->users->findByEmail($email)) {
                $error = 'Cet email est déjà utilisé.';
            } else {
                $id = $this->users->register($name, $email, $password);
                if ($id) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user']    = ['id' => $id, 'name' => $name, 'email' => $email];
                    $this->redirect('dashboard');
                } else {
                    $error = 'Erreur lors de la création du compte.';
                }
            }
        }

        $this->render('auth/register', ['error' => $error], false);
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('auth/login');
    }
}
