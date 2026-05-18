<?php
class SavingsController extends Controller
{
    private SavingsGoalModel $goals;
    private FridgeModel      $fridge;
    private ShoppingModel    $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->goals    = new SavingsGoalModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid = $this->getUserId();

        if ($this->isPost()) {
            $action = $this->post('action', '');

            if ($action === 'create') {
                $name   = $this->post('name', '');
                $emoji  = $this->post('emoji', '🎯');
                $target = (float) $this->post('target_amount', 0);
                $dead   = $this->post('deadline', '') ?: null;
                if ($name && $target > 0) {
                    $this->goals->create([
                        'user_id'       => $uid,
                        'name'          => $name,
                        'emoji'         => $emoji,
                        'target_amount' => $target,
                        'deadline'      => $dead,
                    ]);
                    $this->setFlash('success', 'Objectif créé !');
                }
            } elseif ($action === 'contribute') {
                $goalId = (int) $this->post('goal_id', 0);
                $amount = (float) $this->post('amount', 0);
                if ($goalId && $amount > 0) {
                    $this->goals->addContribution($goalId, $amount);
                    $this->setFlash('success', 'Contribution ajoutée !');
                }
            } elseif ($action === 'delete') {
                $this->goals->delete((int) $this->post('goal_id', 0));
                $this->setFlash('success', 'Objectif supprimé.');
            }
            $this->redirect('savings');
        }

        $this->render('savings/index', [
            'currentPage'  => 'savings',
            'pageTitle'    => 'Objectifs d\'épargne — StudentLife Hub',
            'goals'        => $this->goals->forUser($uid),
            'fridgeAlerts' => $this->fridge->alertCount($uid),
            'shoppingTodo' => $this->shopping->todoCount($uid),
            'flash'        => $this->getFlash(),
        ]);
    }
}
