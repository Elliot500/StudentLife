<?php
class ShoppingController extends Controller
{
    private ShoppingModel $shopping;
    private FridgeModel   $fridge;

    public function __construct()
    {
        parent::__construct();
        $this->shopping = new ShoppingModel();
        $this->fridge   = new FridgeModel();
    }

    public function index(): void
    {
        $uid = $this->getUserId();

        if ($this->isPost()) {
            $name = $this->post('name', '');
            $qty  = (float) $this->post('quantity', 1);
            $unit = $this->post('unit', '');

            if ($name !== '') {
                $this->shopping->create([
                    'user_id'  => $uid,
                    'name'     => $name,
                    'quantity' => $qty,
                    'unit'     => $unit,
                    'status'   => 'a_acheter',
                    'priority' => 0,
                ]);
                $this->setFlash('success', 'Article ajouté à la liste.');
            }
            $this->redirect('shopping');
        }

        $flash = $this->getFlash();

        $this->render('shopping/index', [
            'currentPage'  => 'shopping',
            'pageTitle'    => 'Liste de courses — StudentLife Hub',
            'items'        => $this->shopping->forUser($uid),
            'fridgeAlerts' => $this->fridge->alertCount($uid),
            'shoppingTodo' => $this->shopping->todoCount($uid),
            'flash'        => $flash,
        ]);
    }

    public function toggle(string $id): void
    {
        $this->shopping->toggle((int)$id, $this->getUserId());
        $this->redirect('shopping');
    }

    public function delete(string $id): void
    {
        $this->shopping->delete((int)$id);
        $this->redirect('shopping');
    }
}
