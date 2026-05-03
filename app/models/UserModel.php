<?php
class UserModel extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `users` WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("UserModel::findByEmail() error: " . $e->getMessage());
            return false;
        }
    }

    public function register(string $name, string $email, string $password): int|false
    {
        return $this->create([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function verify(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
