<?php
class DashboardController extends Controller
{
    private ExpenseModel    $expenses;
    private FridgeModel     $fridge;
    private ShoppingModel   $shopping;
    private SavingsGoalModel $savings;

    public function __construct()
    {
        parent::__construct();
        $this->expenses = new ExpenseModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
        $this->savings  = new SavingsGoalModel();
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

        // ── Graphe 6 mois ─────────────────────────────────
        $history = $this->expenses->last6Months($uid);

        // ── Prévisionnel ──────────────────────────────────
        $daysInMonth  = (int) date('t');
        $daysElapsed  = max(1, (int) date('j'));
        $dailyRate    = $totalExpenses / $daysElapsed;
        $projected    = round($dailyRate * $daysInMonth, 2);

        // ── Score mensuel (0-100) ─────────────────────────
        $score = 100;
        if ($income > 0) {
            $ratio = $totalExpenses / $income;
            $score = (int) max(0, min(100, round((1 - $ratio) * 100)));
        }

        // ── Alertes budget ────────────────────────────────
        $budgets = $this->expenses->budgetsForUser($uid);
        $budgetAlerts = [];
        foreach ($byCategory as $cat) {
            if (isset($budgets[$cat['category']])) {
                $limit = $budgets[$cat['category']];
                $pct   = $limit > 0 ? $cat['total'] / $limit * 100 : 0;
                if ($pct >= 80) {
                    $budgetAlerts[] = [
                        'category' => $cat['category'],
                        'spent'    => $cat['total'],
                        'limit'    => $limit,
                        'pct'      => round($pct),
                    ];
                }
            }
        }

        // ── Objectifs d'épargne ───────────────────────────
        $goals = array_slice($this->savings->forUser($uid), 0, 3);

        $this->render('dashboard/index', [
            'currentPage'   => 'dashboard',
            'pageTitle'     => 'Dashboard — StudentLife Hub',
            'income'        => $income,
            'totalExpenses' => $totalExpenses,
            'balance'       => $balance,
            'byCategory'    => $byCategory,
            'fridgeItems'   => array_slice($fridgeItems, 0, 8),
            'fridgeAlerts'  => $fridgeAlerts,
            'shoppingTodo'  => $shoppingTodo,
            'shopItems'     => $shopItems,
            'history'       => $history,
            'projected'     => $projected,
            'score'         => $score,
            'budgetAlerts'  => $budgetAlerts,
            'goals'         => $goals,
        ]);
    }
}
