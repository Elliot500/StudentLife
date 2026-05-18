<?php
class MealPlanModel extends Model
{
    protected string $table = 'meal_plans';

    public function weekFor(int $userId, string $monday): array
    {
        try {
            $sunday = date('Y-m-d', strtotime($monday . ' +6 days'));
            $stmt   = $this->db->prepare(
                "SELECT * FROM meal_plans
                 WHERE user_id=:uid AND date BETWEEN :mon AND :sun
                 ORDER BY date ASC, FIELD(meal_type,'breakfast','lunch','dinner')"
            );
            $stmt->execute([':uid' => $userId, ':mon' => $monday, ':sun' => $sunday]);
            $rows = $stmt->fetchAll();

            $grid = [];
            foreach ($rows as $r) {
                $grid[$r['date']][$r['meal_type']] = $r;
            }
            return $grid;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function upsert(int $userId, string $date, string $type, string $name, string $notes = ''): bool
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO meal_plans (user_id,date,meal_type,name,notes)
                 VALUES (:u,:d,:t,:n,:no)
                 ON DUPLICATE KEY UPDATE name=:n2, notes=:no2"
            );
            $stmt->execute([
                ':u' => $userId, ':d' => $date, ':t' => $type,
                ':n' => $name, ':no' => $notes,
                ':n2' => $name, ':no2' => $notes,
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteMeal(int $userId, string $date, string $type): bool
    {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM meal_plans WHERE user_id=:u AND date=:d AND meal_type=:t"
            );
            $stmt->execute([':u' => $userId, ':d' => $date, ':t' => $type]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
