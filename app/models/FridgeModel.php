<?php
class FridgeModel extends Model
{
    protected string $table = 'fridge_items';

    public function forUser(int $userId): array
    {
        return $this->findWhere(['user_id' => $userId], 'expiry_date ASC');
    }

    public function alertCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM fridge_items
                 WHERE user_id=:uid
                   AND (expiry_date < CURDATE()
                        OR (expiry_date IS NOT NULL AND expiry_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)))"
            );
            $stmt->execute([':uid' => $userId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function expiryStatus(string|null $date): string
    {
        if (!$date) return 'ok';
        $diff = (new DateTime($date))->diff(new DateTime('today'))->days;
        $past = (new DateTime($date)) < (new DateTime('today'));
        if ($past)      return 'bad';
        if ($diff <= 3) return 'warn';
        return 'ok';
    }

    public function daysUntilExpiry(string|null $date): int|null
    {
        if (!$date) return null;
        $today   = new DateTime('today');
        $expiry  = new DateTime($date);
        $diff    = $today->diff($expiry);
        return $expiry >= $today ? (int)$diff->days : -(int)$diff->days;
    }
}
