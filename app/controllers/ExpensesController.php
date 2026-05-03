<?php
class ExpensesController extends Controller
{
    private ExpenseModel  $expenses;
    private IncomeModel   $income;
    private FridgeModel   $fridge;
    private ShoppingModel $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->expenses = new ExpenseModel();
        $this->income   = new IncomeModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid = $this->getUserId();

        if ($this->isPost()) {
            $type = $this->post('type', 'expense');

            if ($type === 'income') {
                $amount = (float) $this->post('amount', 0);
                $source = $this->post('source', '');
                $date   = $this->post('date', date('Y-m-d'));

                if ($amount > 0 && $source !== '') {
                    $this->income->create([
                        'user_id' => $uid,
                        'amount'  => $amount,
                        'source'  => $source,
                        'date'    => $date,
                    ]);
                    $this->setFlash('success', 'Revenu ajouté avec succès.');
                } else {
                    $this->setFlash('error', 'Montant ou source invalide.');
                }
            } else {
                $amount      = (float) $this->post('amount', 0);
                $category    = $this->post('category', 'autre');
                $description = $this->post('description', '');
                $date        = $this->post('date', date('Y-m-d'));

                if ($amount > 0) {
                    $this->expenses->create([
                        'user_id'     => $uid,
                        'amount'      => $amount,
                        'category'    => $category,
                        'description' => $description,
                        'date'        => $date,
                    ]);
                    $this->setFlash('success', 'Dépense ajoutée avec succès.');
                } else {
                    $this->setFlash('error', 'Montant invalide.');
                }
            }
            $this->redirect('expenses');
        }

        $flash = $this->getFlash();
        $uid   = $this->getUserId();

        $this->render('expenses/index', [
            'currentPage'   => 'expenses',
            'pageTitle'     => 'Dépenses & Revenus — StudentLife Hub',
            'expenses'      => $this->expenses->forUser($uid),
            'incomes'       => $this->income->forUser($uid),
            'totalExpenses' => $this->expenses->monthlyTotal($uid),
            'totalIncome'   => $this->income->monthlyTotal($uid),
            'byCategory'    => $this->expenses->byCategory($uid),
            'fridgeAlerts'  => $this->fridge->alertCount($uid),
            'shoppingTodo'  => $this->shopping->todoCount($uid),
            'flash'         => $flash,
        ]);
    }

    public function delete(string $id): void
    {
        $this->expenses->delete((int)$id);
        $this->setFlash('success', 'Dépense supprimée.');
        $this->redirect('expenses');
    }

    public function deleteIncome(string $id): void
    {
        $this->income->delete((int)$id);
        $this->setFlash('success', 'Revenu supprimé.');
        $this->redirect('expenses');
    }
}
