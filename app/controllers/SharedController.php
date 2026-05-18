<?php
class SharedController extends Controller
{
    private SharedExpenseModel $shared;
    private FridgeModel        $fridge;
    private ShoppingModel      $shopping;

    public function __construct()
    {
        parent::__construct();
        $this->shared   = new SharedExpenseModel();
        $this->fridge   = new FridgeModel();
        $this->shopping = new ShoppingModel();
    }

    public function index(): void
    {
        $uid   = $this->getUserId();
        $group = $this->shared->userGroup($uid);

        if ($this->isPost()) {
            $action = $this->post('action', '');

            if ($action === 'create_group') {
                $name = trim($this->post('group_name', ''));
                if ($name) {
                    $this->shared->createGroup($name, $uid);
                    $this->setFlash('success', 'Groupe créé !');
                } else {
                    $this->setFlash('error', 'Donne un nom au groupe.');
                }

            } elseif ($action === 'invite' && $group) {
                $email = trim($this->post('email', ''));
                $user  = $this->shared->findUserByEmail($email);
                if (!$user) {
                    $this->setFlash('error', "Aucun compte trouvé pour « $email ».");
                } elseif ($user['id'] === $uid) {
                    $this->setFlash('error', 'Tu ne peux pas t\'inviter toi-même.');
                } else {
                    $result = $this->shared->sendInvitation($group['id'], $uid, $user['id']);
                    if ($result === 'already_member') {
                        $this->setFlash('info', $user['name'] . ' est déjà dans le groupe.');
                    } elseif ($result === 'sent') {
                        $this->setFlash('success', 'Invitation envoyée à ' . $user['name'] . ' !');
                    } else {
                        $this->setFlash('error', 'Erreur lors de l\'envoi.');
                    }
                }

            } elseif ($action === 'cancel_invite' && $group) {
                $this->shared->cancelInvitation((int)$this->post('invitation_id', 0), $group['id']);
                $this->setFlash('success', 'Invitation annulée.');

            } elseif ($action === 'accept' || $action === 'decline') {
                $invId = (int) $this->post('invitation_id', 0);
                $this->shared->respondInvitation($invId, $uid, $action === 'accept' ? 'accepted' : 'declined');
                $this->setFlash('success', $action === 'accept' ? 'Tu as rejoint le groupe !' : 'Invitation refusée.');

            } elseif ($action === 'remove_member' && $group) {
                $this->shared->removeMember($group['id'], (int)$this->post('member_id', 0), $group['created_by']);
                $this->setFlash('success', 'Membre retiré.');

            } elseif ($action === 'add' && $group) {
                $amount  = (float) $this->post('amount', 0);
                $desc    = $this->post('description', '');
                $date    = $this->post('date', date('Y-m-d'));
                $members = $this->shared->groupMembers($group['id']);
                $ids     = array_column($members, 'id');
                if ($amount > 0 && $desc && count($ids)) {
                    $this->shared->addWithSplits($group['id'], $uid, $amount, $desc, $date, $ids);
                    $this->setFlash('success', 'Dépense partagée ajoutée.');
                }

            } elseif ($action === 'delete' && $group) {
                $this->shared->delete((int)$this->post('expense_id', 0));
                $this->setFlash('success', 'Dépense supprimée.');
            }

            $this->redirect('shared');
        }

        $flash = $this->getFlash();
        $group = $this->shared->userGroup($uid);

        $this->render('shared/index', [
            'currentPage'       => 'shared',
            'pageTitle'         => 'Colocation — StudentLife Hub',
            'group'             => $group,
            'expenses'          => $group ? $this->shared->forGroup($group['id']) : [],
            'members'           => $group ? $this->shared->groupMembers($group['id']) : [],
            'balances'          => $group ? $this->shared->balances($group['id']) : [],
            'sentInvitations'   => $group ? $this->shared->sentInvitations($group['id']) : [],
            'pendingInvitations'=> $this->shared->pendingInvitationsFor($uid),
            'currentUid'        => $uid,
            'fridgeAlerts'      => $this->fridge->alertCount($uid),
            'shoppingTodo'      => $this->shopping->todoCount($uid),
            'flash'             => $flash,
        ]);
    }
}
