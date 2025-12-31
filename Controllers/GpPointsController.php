<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\GpPointsModel;
use App\Models\SeasonsModel;
use App\Models\CircuitsModel;
use App\Models\DriversModel;
use App\Models\TeamsModel;
use App\Models\GpModel;
use App\Models\UpdatesLogModel;

class GpPointsController extends Controller {

    public function __construct()
    {
        // Accès autorisé aux Admin et Modo
        $this->authMiddleware(["Administrateur", "Moderateur"]);
    }

    public function index()
    {
        $gpPoints = GpPointsModel::allWithSeasonActive();
        $this->render('dashboard/gp_points/index', [
            'list' => $gpPoints
        ]);
    }

    public function create()
    {
        $message = '';
        $classMsg = '';
        $isSuccess = false;

        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $teams = TeamsModel::getActive();
        $allGps = GpModel::all(); 
        $circuits = CircuitsModel::all();

        // Tableau circuit_id => ['name' => ..., 'country' => ...]
        $circuitData = [];
        foreach ($circuits as $c) {
            $circuitData[$c->id] = [
                'name'    => $c->name ?? 'Circuit inconnu',
                'country' => $c->country ?? 'Pays inconnu'
            ];
        }

        // Préparer la liste des GP pour le select
        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gp) {
                if ($gp->season_id == $s->id) {

                    $countryName = $circuitData[$gp->circuit_id]['country'] ?? 'Pays inconnu';
                    $circuitName = $circuitData[$gp->circuit_id]['name'] ?? 'Circuit inconnu';

                    $gps[$gp->id] =
                        $gp->category
                        . " - Saison " . $s->season_number
                        . " / GP " . $gp->gp_ordre
                        . " - " . $circuitName
                        . " (" . $countryName . ")";
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Form::validatePost($_POST, ['gp_id', 'driver_id', 'team_id'])) {

                $gp_id = $_POST['gp_id'];
                $driver_id = $_POST['driver_id'];
                $team_id = $_POST['team_id'];

                // Position peut être null si non renseigné
                $position = !empty($_POST['position']) ? intval($_POST['position']) : null;

                // Points, support demi-points et virgule
                $points_numeric = !empty($_POST['points_numeric']) ? floatval(str_replace(',', '.', $_POST['points_numeric'])) : 0;

                // Points textes, 3 lettres max et convertit en majuscule
                $points_text = !empty(trim($_POST['points_text'])) ? strtoupper(substr(trim($_POST['points_text']), 0, 3)) : null;


                $db = new GpPointsModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO gp_points (gp_id, driver_id, team_id, position, points_numeric, points_text)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");

                    if ($stmt->execute([$gp_id, $driver_id, $team_id, $position, $points_numeric, $points_text])) {
                        $message = "Résultat créé avec succès";
                        $classMsg = "msg-success";
                        $isSuccess = true;
                        UpdatesLogModel::logUpdate('gp_points', null, $gp_id, $_SESSION['user_id'], 'create');
                    } else {
                        $message = "Erreur lors de la création";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur : Pilote déjà ajouté, Position non unique, ou Position / Points doivent être un chiffre positif (0.5pt autorisé)";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : informations manquantes";
                $classMsg = "msg-error";
            }
        }

        // Récupération de l'id du GP si formulaire soumis pour resélectionner le même GP
        $selectedGpId = $_POST['gp_id'] ?? null;

        // Récupération du driver et du team si la création a échoué
        $selectedDriverId = $_POST['driver_id'] ?? null;
        $selectedTeamId   = $_POST['team_id'] ?? null;

        // Driver et Team réinitialisé si la création a été exécutée
        if ($isSuccess) {
            $selectedDriverId = null;
            $selectedTeamId = null;
        }

        // Formulaire
        $form = new Form();
        $form->startForm("index.php?controller=gppoints&action=create", "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps, ["value" => $selectedGpId])
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", array_column($drivers, 'nickname', 'id'), ["value" => $selectedDriverId])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", array_column($teams, 'name', 'id'), ["value" => $selectedTeamId])
            ->addLabel("position", "Position :")
            ->addInput("number", "position")
            ->addLabel("points_numeric", "Points :")
            ->addInput("number", "points_numeric", ["step" => "0.5"])
            ->addLabel("points_text", "DNF-DNS-DSQ :")
            ->addInput("text", "points_text", [
                "maxlength" => 3,
                "pattern"   => "[A-Za-z]{0,3}",
                "title"     => "3 lettres maximum (DNF, DNS, DSQ)",
                "style"     => "text-transform: uppercase"
            ])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/gp_points/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $point = GpPointsModel::findById($id);
        if (!$point) {
            $message = "Résultat introuvable";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier que la saison du GP est active
        $gp = GpModel::find($point->gp_id);
        $season = SeasonsModel::findById($gp->season_id ?? null);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de modifier ce résultat : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $teams = TeamsModel::getActive();
        $allGps = GpModel::all();
        $circuits = CircuitsModel::all();

        // Tableau circuit_id => ['name' => ..., 'country' => ...]
        $circuitData = [];
        foreach ($circuits as $c) {
            $circuitData[$c->id] = [
                'name'    => $c->name ?? 'Circuit inconnu',
                'country' => $c->country ?? 'Pays inconnu'
            ];
        }

        // Préparer la liste des GP pour le select
        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gp) {
                if ($gp->season_id == $s->id) {

                    $countryName = $circuitData[$gp->circuit_id]['country'] ?? 'Pays inconnu';
                    $circuitName = $circuitData[$gp->circuit_id]['name'] ?? 'Circuit inconnu';

                    $gps[$gp->id] =
                        $gp->category
                        . " - Saison " . $s->season_number
                        . " / GP " . $gp->gp_ordre
                        . " - " . $circuitName
                        . " (" . $countryName . ")";
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Form::validatePost($_POST, ['gp_id', 'driver_id', 'team_id'])) {

                $gp_id = $_POST['gp_id'];
                $driver_id = $_POST['driver_id'];
                $team_id = $_POST['team_id'];

                $position = !empty($_POST['position']) ? intval($_POST['position']) : null;
                $points_numeric = !empty($_POST['points_numeric']) ? floatval(str_replace(',', '.', $_POST['points_numeric'])) : 0;

                // Points textes, 3 lettres max et convertit en majuscule
                $points_text = !empty(trim($_POST['points_text'])) ? strtoupper(substr(trim($_POST['points_text']), 0, 3)) : null;

                $db = new GpPointsModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        UPDATE gp_points
                        SET gp_id=?, driver_id=?, team_id=?, position=?, points_numeric=?, points_text=?
                        WHERE id=?
                    ");

                    if ($stmt->execute([$gp_id, $driver_id, $team_id, $position, $points_numeric, $points_text, $id])) {
                        $message = "Résultat mis à jour";
                        $classMsg = "msg-success";
                        UpdatesLogModel::logUpdate('gp_points', null, $gp_id, $_SESSION['user_id'], 'update');
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }
                } catch (\PDOException $e) {
                    $message = "Erreur : Pilote déjà ajouté, Position non unique, ou Position / Points doivent être un chiffre positif (0.5pt autorisé)";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : informations manquantes";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Formulaire
        $form = new Form();
        $form->startForm("index.php?controller=gppoints&action=update&id=".$id, "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps, ["value" => $point->gp_id])
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", array_column($drivers, 'nickname', 'id'), ["value" => $point->driver_id])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", array_column($teams, 'name', 'id'), ["value" => $point->team_id])
            ->addLabel("position", "Position :")
            ->addInput("number", "position", ["value" => $point->position ?? ''])
            ->addLabel("points_numeric", "Points :")
            ->addInput("number", "points_numeric", ["step" => "0.5", "value" => $point->points_numeric])
            ->addLabel("points_text", "DNF-DNS-DSQ :")
            ->addInput("text", "points_text", ["value" => $point->points_text ?? ''])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/gp_points/update', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        // Récupérer le point GP à supprimer
        $point = GpPointsModel::findById($id);
        if (!$point) {
            $message = "Résultat introuvable.";
            $classMsg = "msg-error";

            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier si la saison est active
        $gp = GpModel::find($point->gp_id ?? null);
        $season = SeasonsModel::findById($gp->season_id ?? null);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de supprimer ce résultat : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Récupérer les pilotes, teams et saisons actives
        $drivers = DriversModel::getActive();
        $teams = TeamsModel::getActive();
        $seasons = SeasonsModel::getActive();

        // Construire les tableaux associatifs id => nom pour affichage
        $driversMap = [];
        foreach ($drivers as $d) {
            $driversMap[$d->id] = $d->nickname;
        }

        $teamsMap = [];
        foreach ($teams as $t) {
            $teamsMap[$t->id] = $t->name;
        }

        // Récupérer tous les circuits pour le pays
        $circuits = CircuitsModel::all();
        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        // Construire le GP avec concaténation catégorie / saison / GP / pays
        $gpsMap = [];
        foreach ($seasons as $s) {
            foreach (GpModel::all() as $gp) {
                if ($gp->season_id == $s->id) {
                    $countryName = $circuitCountries[$gp->circuit_id] ?? 'Pays inconnu';
                    $gpsMap[$gp->id] = $gp->category 
                                        . " - Saison " . $s->season_number 
                                        . " / GP " . $gp->gp_ordre 
                                        . " - " . $countryName;
                }
            }
        }


        // Récupérer les noms pour la vue
        $driverName = $driversMap[$point->driver_id] ?? '';
        $teamName = $teamsMap[$point->team_id] ?? '';
        $gpName = $gpsMap[$point->gp_id] ?? '';

        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new GpPointsModel();
            $pdo = $db->getConnection();

            try {
                $stmt = $pdo->prepare("DELETE FROM gp_points WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $message = "Résultat supprimé avec succès.";
                    $classMsg = "msg-success";

                    // Log des modifications
                    UpdatesLogModel::logUpdate('gp_points', null, $point->gp_id, $_SESSION['user_id'], 'delete');
                } else {
                    $message = "Erreur lors de la suppression.";
                    $classMsg = "msg-error";
                }
            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression : " . $e->getMessage();
                $classMsg = "msg-error";
            }

            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Afficher le formulaire de confirmation
        $this->render('dashboard/gp_points/delete', [
            'id' => $id,
            'gpName' => $gpName,
            'driverName' => $driverName,
            'teamName' => $teamName,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

}
?>
