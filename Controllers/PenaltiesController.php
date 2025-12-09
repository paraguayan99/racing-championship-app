<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\PenaltiesModel;
use App\Models\SeasonsModel;
use App\Models\DriversModel;
use App\Models\TeamsModel;
use App\Models\GpModel;
use App\Models\UpdatesLogModel;
use App\Models\CircuitsModel;

class PenaltiesController extends Controller {

    public function __construct()
    {
        $this->authMiddleware(["Administrateur", "Moderateur"]);
    }

    public function index()
    {
        $penalties = PenaltiesModel::allWithSeasonActive();
        $this->render('dashboard/penalties/index', [
            'list' => $penalties
        ]);
    }

    public function create()
    {
        $message = '';
        $classMsg = '';

        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $teams = TeamsModel::getActive();
        $allGps = GpModel::all();
        $circuits = CircuitsModel::all();

        // Préparer le mapping GP => texte
        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gp) {
                if ($gp->season_id == $s->id) {
                    $countryName = $circuitCountries[$gp->circuit_id] ?? 'Pays inconnu';
                    $gps[$gp->id] = $gp->category 
                                    . " - Saison " . $s->season_number 
                                    . " / GP " . $gp->gp_ordre 
                                    . " - " . $countryName;
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Vérifier que gp_id et points_removed existent
            if (!isset($_POST['gp_id'], $_POST['points_removed'])) {
                $message = "Création échouée : informations manquantes";
                $classMsg = "msg-error";
            } else {

                $gp_id = $_POST['gp_id'];
                $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;
                $team_id = !empty($_POST['team_id']) ? $_POST['team_id'] : null;

                // Au moins un driver ou team doit être renseigné
                if ($driver_id === null && $team_id === null) {
                    $message = "Veuillez sélectionner un pilote ou une team.";
                    $classMsg = "msg-error";
                } else {
                    // points_removed
                    $points_removed = $_POST['points_removed'] !== '' ? intval($_POST['points_removed']) : 0;
                    $comment = !empty($_POST['comment']) ? trim($_POST['comment']) : null;

                    // Vérifier saison active
                    $gp = GpModel::find($gp_id);
                    $season = SeasonsModel::findById($gp->season_id ?? null);
                    if (!$season || $season->status !== 'active') {
                        $message = "Impossible de créer la pénalité : la saison est désactivée.";
                        $classMsg = "msg-error";
                    } else {
                        $db = new PenaltiesModel();
                        $pdo = $db->getConnection();
                        try {
                            $stmt = $pdo->prepare("
                                INSERT INTO penalties (gp_id, driver_id, team_id, points_removed, comment)
                                VALUES (?, ?, ?, ?, ?)
                            ");
                            if ($stmt->execute([$gp_id, $driver_id, $team_id, $points_removed, $comment])) {
                                $message = "Pénalité créée avec succès";
                                $classMsg = "msg-success";
                                UpdatesLogModel::logUpdate('penalties', null, $gp_id, $_SESSION['user_id'], 'create');
                            } else {
                                $message = "Erreur lors de la création";
                                $classMsg = "msg-error";
                            }
                        } catch (\PDOException $e) {
                            $message = "Erreur : données invalides";
                            $classMsg = "msg-error";
                        }
                    }
                }
            }

            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Préparer selects pilotes et teams
        $driversSelect = ['' => 'Aucun'] + array_column($drivers, 'nickname', 'id');
        $teamsSelect = ['' => 'Aucun'] + array_column($teams, 'name', 'id');

        // Formulaire
        $form = new Form();
        $form->startForm("index.php?controller=penalties&action=create", "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps)
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", $driversSelect, ["value" => null])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", $teamsSelect, ["value" => null])
            ->addLabel("points_removed", "Point(s) retiré(s) :")
            ->addInput("number", "points_removed", ["min" => 0])
            ->addLabel("comment", "Commentaire :")
            ->addInput("text", "comment")
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/penalties/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }


    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $penalty = PenaltiesModel::findById($id);
        if (!$penalty) {
            $message = "Pénalité introuvable";
            $classMsg = "msg-error";
            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $gp = GpModel::find($penalty->gp_id);
        $season = SeasonsModel::findById($gp->season_id ?? null);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de modifier cette pénalité : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
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

        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gp) {
                if ($gp->season_id == $s->id) {
                    $countryName = $circuitCountries[$gp->circuit_id] ?? 'Pays inconnu';
                    $gps[$gp->id] = $gp->category 
                                    . " - Saison " . $s->season_number 
                                    . " / GP " . $gp->gp_ordre 
                                    . " - " . $countryName;
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_POST['gp_id'], $_POST['points_removed'])) {
                $message = "Mise à jour échouée : informations manquantes";
                $classMsg = "msg-error";
            } else {

                $gp_id = $_POST['gp_id'];
                $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;
                $team_id = !empty($_POST['team_id']) ? $_POST['team_id'] : null;

                if ($driver_id === null && $team_id === null) {
                    $message = "Veuillez sélectionner un pilote ou une team.";
                    $classMsg = "msg-error";
                } else {
                    $points_removed = $_POST['points_removed'] !== '' ? intval($_POST['points_removed']) : 0;
                    $comment = !empty($_POST['comment']) ? trim($_POST['comment']) : null;

                    $db = new PenaltiesModel();
                    $pdo = $db->getConnection();
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE penalties
                            SET gp_id=?, driver_id=?, team_id=?, points_removed=?, comment=?
                            WHERE id=?
                        ");
                        if ($stmt->execute([$gp_id, $driver_id, $team_id, $points_removed, $comment, $id])) {
                            $message = "Pénalité mise à jour";
                            $classMsg = "msg-success";
                            UpdatesLogModel::logUpdate('penalties', null, $gp_id, $_SESSION['user_id'], 'update');
                        } else {
                            $message = "Erreur lors de la mise à jour";
                            $classMsg = "msg-error";
                        }
                    } catch (\PDOException $e) {
                        $message = "Erreur : données invalides";
                        $classMsg = "msg-error";
                    }
                }
            }

            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $driversSelect = ['' => 'Aucun'] + array_column($drivers, 'nickname', 'id');
        $teamsSelect = ['' => 'Aucun'] + array_column($teams, 'name', 'id');

        $form = new Form();
        $form->startForm("index.php?controller=penalties&action=update&id=".$id, "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps, ["value" => $penalty->gp_id])
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", $driversSelect, ["value" => $penalty->driver_id ?? ''])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", $teamsSelect, ["value" => $penalty->team_id ?? ''])
            ->addLabel("points_removed", "Point(s) retiré(s) :")
            ->addInput("number", "points_removed", ["min" => 0, "value" => $penalty->points_removed])
            ->addLabel("comment", "Commentaire :")
            ->addInput("text", "comment", ["value" => $penalty->comment ?? ''])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/penalties/update', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $penalty = PenaltiesModel::findById($id);
        if (!$penalty) {
            $message = "Pénalité introuvable.";
            $classMsg = "msg-error";
            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $gp = GpModel::find($penalty->gp_id ?? null);
        $season = SeasonsModel::findById($gp->season_id ?? null);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de supprimer cette pénalité : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Récupérer pilote et team
        $driverName = $penalty->driver_id ? DriversModel::find($penalty->driver_id)->nickname ?? 'Inconnu' : 'Aucun';
        $teamName = $penalty->team_id ? TeamsModel::findById($penalty->team_id)->name ?? 'Inconnu' : 'Aucun';

        // Préparer les infos GP
        $seasons = SeasonsModel::getActive();
        $allGps = GpModel::all();
        $circuits = CircuitsModel::all();

        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gpItem) {
                if ($gpItem->season_id == $s->id) {
                    $countryName = $circuitCountries[$gpItem->circuit_id] ?? 'Pays inconnu';
                    $gps[$gpItem->id] = $gpItem->category 
                                        . " - Saison " . $s->season_number 
                                        . " / GP " . $gpItem->gp_ordre 
                                        . " - " . $countryName;
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new PenaltiesModel();
            $pdo = $db->getConnection();

            try {
                $stmt = $pdo->prepare("DELETE FROM penalties WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $message = "Pénalité supprimée avec succès.";
                    $classMsg = "msg-success";
                    UpdatesLogModel::logUpdate('penalties', null, $penalty->gp_id, $_SESSION['user_id'], 'delete');
                } else {
                    $message = "Erreur lors de la suppression.";
                    $classMsg = "msg-error";
                }
            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression : " . $e->getMessage();
                $classMsg = "msg-error";
            }

            $this->render('dashboard/penalties/index', [
                'list' => PenaltiesModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $this->render('dashboard/penalties/delete', [
            'id' => $id,
            'penalty' => $penalty,
            'driverName' => $driverName,
            'teamName' => $teamName,
            'gps' => $gps,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

}
?>
