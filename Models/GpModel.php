<?php
namespace App\Models;

use App\Core\DbConnect;
use App\Models\CircuitsModel;

class GpModel extends DbConnect {

    public $id;
    public $season_id;
    public $circuit_id;
    public $gp_ordre;

    /**
     * Retourne tous les GP triés par catégorie puis gp_ordre
     */
    public static function all()
    {
        $db = new DbConnect();

        return $db->getConnection()->query("
            SELECT 
                gp.id,
                gp.season_id,
                gp.circuit_id,
                gp.gp_ordre,

                -- Season
                s.season_number,
                s.status AS season_status,

                -- Category
                cat.name AS category,

                -- Circuit
                ci.name AS circuit_name

            FROM gp
            JOIN seasons s ON gp.season_id = s.id
            JOIN categories cat ON s.category_id = cat.id
            JOIN circuits ci ON gp.circuit_id = ci.id

            ORDER BY cat.name ASC, gp.gp_ordre ASC
        ")->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Trouver un GP par ID
     */
    public static function find($id)
    {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("
            SELECT * FROM gp WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Retourne tous les GP enrichis avec le nom du pays du circuit
     */
    public static function allWithCountry()
    {
        $list = self::all(); // récupère les GP avec season, category, circuit_name
        $circuits = CircuitsModel::all();

        // Préparer un tableau circuit_id => countryName
        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        // Ajouter countryName à chaque GP
        foreach ($list as &$gp) {
            $gp->countryName = $circuitCountries[$gp->circuit_id] ?? 'Pays inconnu';
        }

        return $list;
    }
}
?>
