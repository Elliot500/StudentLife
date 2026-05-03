<?php
class ExpenseModel extends Model
{
    protected string $table = 'expenses';

    public function forUser(int $userId): array
    {
        return $this->findWhere(['user_id' => $userId], 'date DESC');
    }

    public function monthlyTotal(int $userId, string $month = ''): float
    {
        if (!$month) $month = date('Y-m');
        [$year, $mon] = explode('-', $month);
        try {
            $stmt = $this->db->prepare(
                "SELECT COALESCE(SUM(amount),0) FROM expenses
                 WHERE user_id=:uid AND YEAR(date)=:y AND MONTH(date)=:m"
            );
            $stmt->execute([':uid' => $userId, ':y' => $year, ':m' => $mon]);
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0.0;
        }
    }

    public function byCategory(int $userId, string $month = ''): array
    {
        if (!$month) $month = date('Y-m');
        [$year, $mon] = explode('-', $month);
        try {
            $stmt = $this->db->prepare(
                "SELECT category, SUM(amount) as total FROM expenses
                 WHERE user_id=:uid AND YEAR(date)=:y AND MONTH(date)=:m
                 GROUP BY category ORDER BY total DESC"
            );
            $stmt->execute([':uid' => $userId, ':y' => $year, ':m' => $mon]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function monthlyIncome(int $userId, string $month = ''): float
    {
        if (!$month) $month = date('Y-m');
        [$year, $mon] = explode('-', $month);
        try {
            $stmt = $this->db->prepare(
                "SELECT COALESCE(SUM(amount),0) FROM income
                 WHERE user_id=:uid AND YEAR(date)=:y AND MONTH(date)=:m"
            );
            $stmt->execute([':uid' => $userId, ':y' => $year, ':m' => $mon]);
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0.0;
        }
    }
}
