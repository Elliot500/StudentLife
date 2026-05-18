<?php
class ProfileController extends Controller
{
    private UserModel    $users;
    private ExpenseModel $expenses;
    private FridgeModel  $fridge;
    private ShoppingModel $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->users    = new UserModel();
        $this->expenses = new ExpenseModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid  = $this->getUserId();
        $user = $this->users->find($uid);

        if ($this->isPost()) {
            $action = $this->post('action', '');

            if ($action === 'update_info') {
                $name  = trim($this->post('name', ''));
                $email = trim($this->post('email', ''));

                if (!$name || !$email) {
                    $this->setFlash('error', 'Nom et email requis.');
                } elseif ($email !== $user['email'] && $this->users->findByEmail($email)) {
                    $this->setFlash('error', 'Cet email est déjà utilisé.');
                } else {
                    $this->users->update($uid, ['name' => $name, 'email' => $email]);
                    $_SESSION['user']['name']  = $name;
                    $_SESSION['user']['email'] = $email;
                    $this->setFlash('success', 'Profil mis à jour !');
                }

            } elseif ($action === 'change_password') {
                $current = $this->post('current_password', '');
                $new     = $this->post('new_password', '');
                $confirm = $this->post('confirm_password', '');

                if (!password_verify($current, $user['password'])) {
                    $this->setFlash('error', 'Mot de passe actuel incorrect.');
                } elseif (strlen($new) < 6) {
                    $this->setFlash('error', 'Le nouveau mot de passe doit faire au moins 6 caractères.');
                } elseif ($new !== $confirm) {
                    $this->setFlash('error', 'Les mots de passe ne correspondent pas.');
                } else {
                    $this->users->update($uid, ['password' => password_hash($new, PASSWORD_DEFAULT)]);
                    $this->setFlash('success', 'Mot de passe modifié !');
                }

            } elseif ($action === 'delete_account') {
                $this->users->delete($uid);
                session_destroy();
                $this->redirect('auth/login');
            }

            $this->redirect('profile');
        }

        // Stats du compte
        $totalExpenses = $this->expenses->monthlyTotal($uid);
        $totalIncome   = $this->expenses->monthlyIncome($uid);
        try {
            $db   = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM expenses WHERE user_id=:u");
            $stmt->execute([':u' => $uid]);
            $expenseCount = (int) $stmt->fetchColumn();

            $stmt2 = $db->prepare("SELECT COUNT(*) FROM savings_goals WHERE user_id=:u");
            $stmt2->execute([':u' => $uid]);
            $goalsCount = (int) $stmt2->fetchColumn();
        } catch (PDOException $e) {
            $expenseCount = 0;
            $goalsCount   = 0;
        }

        $this->render('profile/index', [
            'currentPage'   => 'profile',
            'pageTitle'     => 'Mon profil — StudentLife Hub',
            'user'          => $this->users->find($uid),
            'totalExpenses' => $totalExpenses,
            'totalIncome'   => $totalIncome,
            'expenseCount'  => $expenseCount,
            'goalsCount'    => $goalsCount,
            'fridgeAlerts'  => $this->fridge->alertCount($uid),
            'shoppingTodo'  => $this->shopping->todoCount($uid),
            'flash'         => $this->getFlash(),
        ]);
    }
}
