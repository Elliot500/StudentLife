<?php
class SavingsGoalModel extends Model
{
    protected string $table = 'savings_goals';

    public function forUser(int $userId): array
    {
        return $this->findWhere(['user_id' => $userId], 'created_at DESC');
    }

    public function addContribution(int $goalId, float $amount): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE savings_goals SET current_amount = current_amount + :a WHERE id = :id"
            );
            $stmt->execute([':a' => $amount, ':id' => $goalId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function progressPct(array $goal): int
    {
        if ($goal['target_amount'] <= 0) return 0;
        return (int) min(100, round($goal['current_amount'] / $goal['target_amount'] * 100));
    }
}
