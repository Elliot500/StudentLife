<?php
class MealController extends Controller
{
    private MealPlanModel $meals;
    private FridgeModel   $fridge;
    private ShoppingModel $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->meals    = new MealPlanModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid = $this->getUserId();

        // Lundi de la semaine affichée
        $weekOffset = (int) $this->get('week', 0);
        $monday     = date('Y-m-d', strtotime('monday this week') + $weekOffset * 604800);

        if ($this->isPost()) {
            $action = $this->post('action', '');
            if ($action === 'save') {
                $date  = $this->post('date', '');
                $type  = $this->post('meal_type', '');
                $name  = $this->post('name', '');
                $notes = $this->post('notes', '');
                if ($date && $type && $name) {
                    $this->meals->upsert($uid, $date, $type, $name, $notes);
                    $this->setFlash('success', 'Repas planifié !');
                }
            } elseif ($action === 'delete') {
                $this->meals->deleteMeal($uid, $this->post('date', ''), $this->post('meal_type', ''));
                $this->setFlash('success', 'Repas supprimé.');
            }
            $this->redirect('meals?week=' . $weekOffset);
        }

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = date('Y-m-d', strtotime($monday . ' +' . $i . ' days'));
        }

        $this->render('meals/index', [
            'currentPage'  => 'meals',
            'pageTitle'    => 'Agenda repas — StudentLife Hub',
            'monday'       => $monday,
            'weekOffset'   => $weekOffset,
            'days'         => $days,
            'grid'         => $this->meals->weekFor($uid, $monday),
            'fridgeAlerts' => $this->fridge->alertCount($uid),
            'shoppingTodo' => $this->shopping->todoCount($uid),
            'flash'        => $this->getFlash(),
        ]);
    }
}
