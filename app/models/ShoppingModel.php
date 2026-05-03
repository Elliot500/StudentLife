<?php
class ShoppingModel extends Model
{
    protected string $table = 'shopping_list';

    public function forUser(int $userId): array
    {
        return $this->findWhere(['user_id' => $userId], 'status ASC, priority DESC, id DESC');
    }

    public function todoCount(int $userId): int
    {
        return $this->countWhere(['user_id' => $userId, 'status' => 'a_acheter']);
    }

    public function toggle(int $id, int $userId): bool
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE shopping_list
                 SET status = IF(status='a_acheter','achete','a_acheter')
                 WHERE id=:id AND user_id=:uid"
            );
            $stmt->execute([':id' => $id, ':uid' => $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
