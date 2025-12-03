<?php
namespace App\Models;

use App\Core\DbConnect;

class UpdatesLogModel extends DbConnect {

    /**
     * Enregistre une mise à jour dans la table updates_log
     * 
     * @param string $table Nom de la table modifiée
     * @param int|null $season_id Saison concernée
     * @param int|null $gp_id GP concerné
     * @param int $user_id Utilisateur ayant effectué la modification
     */

    public static function logUpdate(string $table, ?int $season_id, ?int $gp_id, ?int $user_id = null, string $action = '')
    {
        // Vérifie qu'au moins une des deux est renseignée
        if (is_null($season_id) && is_null($gp_id)) {
            throw new \Exception("logUpdate : season_id ou gp_id doit être renseigné.");
        }

        // Récupère l'utilisateur depuis la session si non fourni
        if (is_null($user_id)) {
            if (empty($_SESSION['user_id'])) {
                throw new \Exception("logUpdate : utilisateur non connecté.");
            }
            $user_id = $_SESSION['user_id'];
        }

        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("
            INSERT INTO updates_log (season_id, gp_id, table_name, updated_at, updated_by, action)
            VALUES (:season_id, :gp_id, :table_name, NOW(), :updated_by, :action)
        ");

        $stmt->execute([
            ':season_id'  => $season_id,
            ':gp_id'      => $gp_id,
            ':table_name' => $table,
            ':updated_by' => $user_id,
            ':action'     => $action
        ]);
    }


    /**
     * Optionnel : récupérer tout l'historique
     */
    public static function getAll()
    {
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT ul.*, u.username 
            FROM updates_log ul
            JOIN users u ON ul.updated_by = u.id
            ORDER BY ul.updated_at DESC
        ")->fetchAll(\PDO::FETCH_OBJ);
    }
    

    /**
     * Optionnel : récupérer la dernière mise à jour d'une table/saison/GP
     */
    public static function getLast(string $table, ?int $season_id = null, ?int $gp_id = null)
    {
        $db = new DbConnect();

        $conditions = ["table_name = :table"];
        $params = [':table' => $table];

        if ($season_id !== null) {
            $conditions[] = "season_id = :season_id";
            $params[':season_id'] = $season_id;
        }
        if ($gp_id !== null) {
            $conditions[] = "gp_id = :gp_id";
            $params[':gp_id'] = $gp_id;
        }

        $sql = "SELECT ul.*, u.username 
                FROM updates_log ul
                JOIN users u ON ul.updated_by = u.id
                WHERE " . implode(' AND ', $conditions) . "
                ORDER BY ul.updated_at DESC
                LIMIT 1";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
