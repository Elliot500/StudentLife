<?php
/**
 * Modèle de base
 * Tous les modèles héritent de cette classe
 * Fournit les opérations CRUD de base via PDO
 */
abstract class Model
{
    protected PDO $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Trouve un enregistrement par son ID
     *
     * @param int $id L'identifiant
     * @return array|false Les données ou false si non trouvé
     */
    public function find(int $id): array|false
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Model::find() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne tous les enregistrements de la table
     *
     * @param string $orderBy Colonne de tri (défaut: primaryKey DESC)
     * @return array Liste des enregistrements
     */
    public function findAll(string $orderBy = ''): array
    {
        try {
            $order = $orderBy ?: "`{$this->primaryKey}` DESC";
            $stmt = $this->db->query("SELECT * FROM `{$this->table}` ORDER BY {$order}");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Model::findAll() error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crée un nouvel enregistrement
     *
     * @param array $data Tableau associatif [colonne => valeur]
     * @return int|false L'ID inséré ou false en cas d'erreur
     */
    public function create(array $data): int|false
    {
        try {
            $columns = implode(', ', array_map(fn($col) => "`$col`", array_keys($data)));
            $placeholders = implode(', ', array_map(fn($col) => ":$col", array_keys($data)));

            $stmt = $this->db->prepare(
                "INSERT INTO `{$this->table}` ({$columns}) VALUES ({$placeholders})"
            );

            $params = [];
            foreach ($data as $key => $value) {
                $params[":$key"] = $value;
            }

            $stmt->execute($params);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Model::create() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour un enregistrement par ID
     *
     * @param int   $id   L'identifiant
     * @param array $data Tableau associatif [colonne => valeur]
     * @return bool Succès ou échec
     */
    public function update(int $id, array $data): bool
    {
        try {
            $setParts = array_map(fn($col) => "`$col` = :$col", array_keys($data));
            $setString = implode(', ', $setParts);

            $stmt = $this->db->prepare(
                "UPDATE `{$this->table}` SET {$setString} WHERE `{$this->primaryKey}` = :__id"
            );

            $params = [':__id' => $id];
            foreach ($data as $key => $value) {
                $params[":$key"] = $value;
            }

            $stmt->execute($params);
            return $stmt->rowCount() >= 0;
        } catch (PDOException $e) {
            error_log("Model::update() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un enregistrement par ID
     *
     * @param int $id L'identifiant
     * @return bool Succès ou échec
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->db->prepare(
                "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Model::delete() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Trouve des enregistrements selon des conditions simples
     *
     * @param array  $conditions Tableau [colonne => valeur]
     * @param string $orderBy    Clause ORDER BY optionnelle
     * @return array Liste des résultats
     */
    protected function findWhere(array $conditions, string $orderBy = ''): array
    {
        try {
            $whereParts = array_map(fn($col) => "`$col` = :$col", array_keys($conditions));
            $whereString = implode(' AND ', $whereParts);
            $order = $orderBy ? " ORDER BY $orderBy" : '';

            $stmt = $this->db->prepare(
                "SELECT * FROM `{$this->table}` WHERE {$whereString}{$order}"
            );

            $params = [];
            foreach ($conditions as $key => $value) {
                $params[":$key"] = $value;
            }

            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Model::findWhere() error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compte le nombre d'enregistrements selon des conditions
     *
     * @param array $conditions Tableau [colonne => valeur]
     * @return int Le nombre d'enregistrements
     */
    protected function countWhere(array $conditions = []): int
    {
        try {
            if (empty($conditions)) {
                $stmt = $this->db->query("SELECT COUNT(*) FROM `{$this->table}`");
            } else {
                $whereParts = array_map(fn($col) => "`$col` = :$col", array_keys($conditions));
                $whereString = implode(' AND ', $whereParts);
                $stmt = $this->db->prepare(
                    "SELECT COUNT(*) FROM `{$this->table}` WHERE {$whereString}"
                );
                $params = [];
                foreach ($conditions as $key => $value) {
                    $params[":$key"] = $value;
                }
                $stmt->execute($params);
            }
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Model::countWhere() error: " . $e->getMessage());
            return 0;
        }
    }
}
