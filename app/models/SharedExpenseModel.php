<?php
class SharedExpenseModel extends Model
{
    protected string $table = 'shared_expenses';

    public function forGroup(int $groupId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT se.*, u.name as payer_name
                 FROM shared_expenses se
                 JOIN users u ON u.id = se.paid_by
                 WHERE se.group_id = :gid
                 ORDER BY se.date DESC, se.id DESC"
            );
            $stmt->execute([':gid' => $groupId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function balances(int $groupId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT
                   ses.user_id,
                   u.name,
                   SUM(CASE WHEN se.paid_by = ses.user_id THEN se.amount ELSE 0 END) as paid,
                   SUM(ses.amount) as owes
                 FROM shared_expense_splits ses
                 JOIN shared_expenses se ON se.id = ses.expense_id
                 JOIN users u ON u.id = ses.user_id
                 WHERE se.group_id = :gid AND ses.settled = 0
                 GROUP BY ses.user_id, u.name"
            );
            $stmt->execute([':gid' => $groupId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function addWithSplits(int $groupId, int $paidBy, float $amount, string $desc, string $date, array $memberIds): int|false
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare(
                "INSERT INTO shared_expenses (group_id,paid_by,amount,description,date) VALUES (:g,:p,:a,:d,:dt)"
            );
            $stmt->execute([':g' => $groupId, ':p' => $paidBy, ':a' => $amount, ':d' => $desc, ':dt' => $date]);
            $expenseId = (int) $this->db->lastInsertId();

            $split = round($amount / count($memberIds), 2);
            $ins   = $this->db->prepare("INSERT INTO shared_expense_splits (expense_id,user_id,amount) VALUES (:e,:u,:a)");
            foreach ($memberIds as $uid) {
                $ins->execute([':e' => $expenseId, ':u' => $uid, ':a' => $split]);
            }
            $this->db->commit();
            return $expenseId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function settle(int $expenseId, int $userId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE shared_expense_splits SET settled=1 WHERE expense_id=:e AND user_id=:u"
            );
            $stmt->execute([':e' => $expenseId, ':u' => $userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function userGroup(int $userId): array|false
    {
        try {
            // Priorité au groupe dont l'utilisateur n'est PAS le créateur
            // (il a été invité), sinon son propre groupe.
            $stmt = $this->db->prepare(
                "SELECT sg.* FROM shared_groups sg
                 JOIN group_members gm ON gm.group_id = sg.id
                 WHERE gm.user_id = :uid
                 ORDER BY (sg.created_by = :uid2) ASC, gm.joined_at DESC
                 LIMIT 1"
            );
            $stmt->execute([':uid' => $userId, ':uid2' => $userId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function groupMembers(int $groupId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT u.id, u.name, u.email FROM users u
                 JOIN group_members gm ON gm.user_id = u.id
                 WHERE gm.group_id = :gid
                 ORDER BY gm.joined_at ASC"
            );
            $stmt->execute([':gid' => $groupId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function createGroup(string $name, int $createdBy): int|false
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO shared_groups (name, created_by) VALUES (:n, :u)"
            );
            $stmt->execute([':n' => $name, ':u' => $createdBy]);
            $groupId = (int) $this->db->lastInsertId();

            // Ajouter le créateur comme premier membre
            $stmt2 = $this->db->prepare(
                "INSERT INTO group_members (group_id, user_id) VALUES (:g, :u)"
            );
            $stmt2->execute([':g' => $groupId, ':u' => $createdBy]);
            return $groupId;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findUserByEmail(string $email): array|false
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT id, name, email FROM users WHERE email = :e LIMIT 1"
            );
            $stmt->execute([':e' => $email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function isMember(int $groupId, int $userId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT 1 FROM group_members WHERE group_id=:g AND user_id=:u"
            );
            $stmt->execute([':g' => $groupId, ':u' => $userId]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addMember(int $groupId, int $userId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT IGNORE INTO group_members (group_id, user_id) VALUES (:g, :u)"
            );
            $stmt->execute([':g' => $groupId, ':u' => $userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeMember(int $groupId, int $userId, int $createdBy): bool
    {
        if ($userId === $createdBy) return false;
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM group_members WHERE group_id=:g AND user_id=:u"
            );
            $stmt->execute([':g' => $groupId, ':u' => $userId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ── Invitations ───────────────────────────────────

    public function sendInvitation(int $groupId, int $invitedBy, int $invitedUser): string
    {
        // Déjà membre ?
        if ($this->isMember($groupId, $invitedUser)) return 'already_member';
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO coloc_invitations (group_id, invited_by, invited_user)
                 VALUES (:g, :by, :u)
                 ON DUPLICATE KEY UPDATE status='pending', created_at=NOW()"
            );
            $stmt->execute([':g' => $groupId, ':by' => $invitedBy, ':u' => $invitedUser]);
            return 'sent';
        } catch (PDOException $e) {
            return 'error';
        }
    }

    public function pendingInvitationsFor(int $userId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT ci.*, sg.name as group_name, u.name as inviter_name
                 FROM coloc_invitations ci
                 JOIN shared_groups sg ON sg.id = ci.group_id
                 JOIN users u ON u.id = ci.invited_by
                 WHERE ci.invited_user = :uid AND ci.status = 'pending'
                 ORDER BY ci.created_at DESC"
            );
            $stmt->execute([':uid' => $userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function pendingInvitationsCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM coloc_invitations
                 WHERE invited_user=:uid AND status='pending'"
            );
            $stmt->execute([':uid' => $userId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function sentInvitations(int $groupId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT ci.*, u.name as invitee_name, u.email as invitee_email
                 FROM coloc_invitations ci
                 JOIN users u ON u.id = ci.invited_user
                 WHERE ci.group_id = :g
                 ORDER BY ci.created_at DESC"
            );
            $stmt->execute([':g' => $groupId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function respondInvitation(int $invitationId, int $userId, string $response): bool
    {
        try {
            // Vérifie que l'invitation lui appartient
            $stmt = $this->db->prepare(
                "SELECT * FROM coloc_invitations WHERE id=:id AND invited_user=:u AND status='pending'"
            );
            $stmt->execute([':id' => $invitationId, ':u' => $userId]);
            $inv = $stmt->fetch();
            if (!$inv) return false;

            $this->db->prepare(
                "UPDATE coloc_invitations SET status=:s WHERE id=:id"
            )->execute([':s' => $response, ':id' => $invitationId]);

            if ($response === 'accepted') {
                $this->addMember($inv['group_id'], $userId);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function cancelInvitation(int $invitationId, int $groupId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM coloc_invitations WHERE id=:id AND group_id=:g"
            );
            $stmt->execute([':id' => $invitationId, ':g' => $groupId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
