<?php
class DashboardController extends Controller
{
    private ExpenseModel  $expenses;
    private FridgeModel   $fridge;
    private ShoppingModel $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->expenses = new ExpenseModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid = $this->getUserId();

        $income        = $this->expenses->monthlyIncome($uid);
        $totalExpenses = $this->expenses->monthlyTotal($uid);
        $balance       = $income - $totalExpenses;
        $byCategory    = $this->expenses->byCategory($uid);

        $fridgeItems  = $this->fridge->forUser($uid);
        $fridgeAlerts = $this->fridge->alertCount($uid);
        $shoppingTodo = $this->shopping->todoCount($uid);
        $shopItems    = array_slice($this->shopping->forUser($uid), 0, 6);

        $this->render('dashboard/index', [
            'currentPage'    => 'dashboard',
            'pageTitle'      => 'Dashboard — StudentLife Hub',
            'income'         => $income,
            'totalExpenses'  => $totalExpenses,
            'balance'        => $balance,
            'byCategory'     => $byCategory,
            'fridgeItems'    => array_slice($fridgeItems, 0, 8),
            'fridgeAlerts'   => $fridgeAlerts,
            'shoppingTodo'   => $shoppingTodo,
            'shopItems'      => $shopItems,
        ]);
    }
}
