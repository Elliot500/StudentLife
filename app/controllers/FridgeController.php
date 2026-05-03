<?php
class FridgeController extends Controller
{
    private FridgeModel   $fridge;
    private ShoppingModel $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid = $this->getUserId();

        if ($this->isPost()) {
            $name    = $this->post('name', '');
            $qty     = (float) $this->post('quantity', 1);
            $unit    = $this->post('unit', '');
            $cat     = $this->post('category', 'autre');
            $expiry  = $this->post('expiry_date', '') ?: null;

            if ($name !== '') {
                $this->fridge->create([
                    'user_id'     => $uid,
                    'name'        => $name,
                    'quantity'    => $qty,
                    'unit'        => $unit,
                    'category'    => $cat,
                    'expiry_date' => $expiry,
                    'added_date'  => date('Y-m-d'),
                ]);
                $this->setFlash('success', 'Produit ajouté au frigo.');
            }
            $this->redirect('fridge');
        }

        $flash = $this->getFlash();
        $items = $this->fridge->forUser($uid);

        $this->render('fridge/index', [
            'currentPage'  => 'fridge',
            'pageTitle'    => 'Frigo — StudentLife Hub',
            'items'        => $items,
            'fridgeAlerts' => $this->fridge->alertCount($uid),
            'shoppingTodo' => $this->shopping->todoCount($uid),
            'flash'        => $flash,
        ]);
    }

    public function delete(string $id): void
    {
        $this->fridge->delete((int)$id);
        $this->setFlash('success', 'Produit supprimé.');
        $this->redirect('fridge');
    }
}
