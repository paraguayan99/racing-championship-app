<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\GpStatsModel;
use App\Models\SeasonsModel;
use App\Models\CircuitsModel;
use App\Models\DriversModel;
use App\Models\GpModel;
use App\Models\UpdatesLogModel;

class GpStatsController extends Controller {

    public function __construct()
    {
        // Accès autorisé aux Admin et Modo
        $this->authMiddleware(["Administrateur", "Moderateur"]);
    }

    public function index()
    {
        $gpStats = GpStatsModel::allWithSeasonActive();
        $this->render('dashboard/gp_stats/index', [
            'list' => $gpStats
        ]);
    }

    public function create()
    {
        $message = '';
        $classMsg = '';

        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $allGps = GpModel::all();
        $circuits = CircuitsModel::all();

        // Tableau circuit_id => country_name
        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        // Préparer la liste des GP pour le select
        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gp) {
                if ($gp->season_id == $s->id) {
                    $gps[$gp->id] = $gp->category 
                                        . " - Saison " . $s->season_number 
                                        . " / GP " . $gp->gp_ordre 
                                        . " - " . ($circuitCountries[$gp->circuit_id] ?? 'Pays inconnu') 
                                        . " - " . ($gp->circuit_name ?? $gp->name ?? 'Circuit inconnu');
                }
            }
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['gp_id'])) {

                $gp_id = $_POST['gp_id'];
                $pole_position_driver = !empty($_POST['pole_position_driver']) ? intval($_POST['pole_position_driver']) : null;
                $pole_position_time = !empty($_POST['pole_position_time']) ? trim($_POST['pole_position_time']) : null;
                $fastest_lap_driver = !empty($_POST['fastest_lap_driver']) ? intval($_POST['fastest_lap_driver']) : null;
                $fastest_lap_time = !empty($_POST['fastest_lap_time']) ? trim($_POST['fastest_lap_time']) : null;

                // Vérification format strict m:ss.mmm
                $timePattern = '/^\d+:[0-5]\d\.\d{3}$/';
                if (($pole_position_time && !preg_match($timePattern, $pole_position_time)) ||
                    ($fastest_lap_time && !preg_match($timePattern, $fastest_lap_time))) {
                    $message = "Format du temps invalide : m:ss.mmm (ex: 1:12.562)";
                    $classMsg = "msg-error";
                } else {
                    // Vérifier saison active
                    $gp = GpModel::find($gp_id);
                    $season = SeasonsModel::findById($gp->season_id ?? null);
                    if (!$season || $season->status !== 'active') {
                        $message = "Impossible de créer ces Pole Position & Fastest Lap : la saison est désactivée.";
                        $classMsg = "msg-error";
                    } else {
                        $db = new GpStatsModel();
                        $pdo = $db->getConnection();
                        try {
                            $stmt = $pdo->prepare("
                                INSERT INTO gp_stats (gp_id, pole_position_driver, pole_position_time, fastest_lap_driver, fastest_lap_time)
                                VALUES (?, ?, ?, ?, ?)
                            ");
                            if ($stmt->execute([
                                $gp_id,
                                $pole_position_driver,
                                $pole_position_time,
                                $fastest_lap_driver,
                                $fastest_lap_time
                            ])) {
                                $message = "Pole Position & Fastest Lap du GP créées avec succès";
                                $classMsg = "msg-success";
                                UpdatesLogModel::logUpdate('gp_stats', null, $gp_id, $_SESSION['user_id'], 'create');
                            } else {
                                $message = "Erreur lors de la création";
                                $classMsg = "msg-error";
                            }
                        } catch (\PDOException $e) {
                            $message = "Erreur : données invalides, pilotes non renseignés ou données déjà existantes pour ce GP";
                            $classMsg = "msg-error";
                        }
                    }
                }

                $this->render('dashboard/gp_stats/index', [
                    'list' => GpStatsModel::allWithSeasonActive(),
                    'message' => $message,
                    'classMsg' => $classMsg
                ]);
                return;
            }
        }

        // Permet l'option Aucun dans les select drivers
        $poleDrivers = ['' => 'Aucun'] + array_column($drivers, 'nickname', 'id');
        $fastestLapDrivers = ['' => 'Aucun'] + array_column($drivers, 'nickname', 'id');

        // Formulaire
        $form = new Form();
        $form->startForm("index.php?controller=gpstats&action=create", "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps)
            ->addLabel("pole_position_driver", "Pilote Pole :")
            ->addSelect("pole_position_driver", $poleDrivers, ["value" => null])
            ->addLabel("pole_position_time", "Temps Pole :")
            ->addInput("text", "pole_position_time", ["placeholder" => "1:12.562"])
            ->addLabel("fastest_lap_driver", "Pilote Fastest Lap :")
            ->addSelect("fastest_lap_driver", $fastestLapDrivers, ["value" => null])
            ->addLabel("fastest_lap_time", "Temps Fastest Lap :")
            ->addInput("text", "fastest_lap_time", ["placeholder" => "1:12.562"])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/gp_stats/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    public function update($gp_id)
    {
        $message = '';
        $classMsg = '';

        $stats = GpStatsModel::findByGpId($gp_id);
        if (!$stats) {
            $message = "Pole Position & Fastest Lap du GP introuvables";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_stats/index', [
                'list' => GpStatsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier saison active
        $gp = GpModel::find($gp_id);
        $season = SeasonsModel::findById($gp->season_id ?? null);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de modifier : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_stats/index', [
                'list' => GpStatsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
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
                    $gps[$gp->id] = $gp->category 
                                        . " - Saison " . $s->season_number 
                                        . " / GP " . $gp->gp_ordre 
                                        . " - " . ($circuitCountries[$gp->circuit_id] ?? 'Pays inconnu') 
                                        . " - " . ($gp->circuit_name ?? $gp->name ?? 'Circuit inconnu');
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['gp_id'])) {

                $new_gp_id = $_POST['gp_id'];
                $pole_position_driver = !empty($_POST['pole_position_driver']) ? intval($_POST['pole_position_driver']) : null;
                $pole_position_time = !empty($_POST['pole_position_time']) ? trim($_POST['pole_position_time']) : null;
                $fastest_lap_driver = !empty($_POST['fastest_lap_driver']) ? intval($_POST['fastest_lap_driver']) : null;
                $fastest_lap_time = !empty($_POST['fastest_lap_time']) ? trim($_POST['fastest_lap_time']) : null;

                // Validation strict m:ss.mmm
                $timePattern = '/^\d+:[0-5]\d\.\d{3}$/';
                if (($pole_position_time && !preg_match($timePattern, $pole_position_time)) ||
                    ($fastest_lap_time && !preg_match($timePattern, $fastest_lap_time))) {
                    $message = "Format du temps invalide : m:ss.mmm (ex: 1:12.562)";
                    $classMsg = "msg-error";
                } else {
                    $db = new GpStatsModel();
                    $pdo = $db->getConnection();
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE gp_stats
                            SET gp_id=?, pole_position_driver=?, pole_position_time=?, fastest_lap_driver=?, fastest_lap_time=?
                            WHERE gp_id=?
                        ");
                        if ($stmt->execute([
                            $new_gp_id,
                            $pole_position_driver,
                            $pole_position_time,
                            $fastest_lap_driver,
                            $fastest_lap_time,
                            $gp_id
                        ])) {
                            $message = "Pole Position & Fastest Lap du GP mises à jour";
                            $classMsg = "msg-success";
                            UpdatesLogModel::logUpdate('gp_stats', null, $new_gp_id, $_SESSION['user_id'], 'update');
                        } else {
                            $message = "Erreur lors de la mise à jour";
                            $classMsg = "msg-error";
                        }
                    } catch (\PDOException $e) {
                        $message = "Erreur : données invalides, pilotes non renseignés ou données déjà existantes pour ce GP";
                        $classMsg = "msg-error";
                    }
                }

                $this->render('dashboard/gp_stats/index', [
                    'list' => GpStatsModel::allWithSeasonActive(),
                    'message' => $message,
                    'classMsg' => $classMsg
                ]);
                return;
            }
        }

        // Gestion des selects pilotes
        $poleDrivers = array_column($drivers, 'nickname', 'id');
        $fastestLapDrivers = array_column($drivers, 'nickname', 'id');

        // Ajouter "Aucun" pour pouvoir retirer un pilote existant
        $poleDrivers = ['' => 'Aucun'] + $poleDrivers;
        $fastestLapDrivers = ['' => 'Aucun'] + $fastestLapDrivers;

        $poleSelected = $stats->pole_position_driver ?? '';
        $fastestSelected = $stats->fastest_lap_driver ?? '';

        // Formulaire
        $form = new Form();
        $form->startForm("index.php?controller=gpstats&action=update&gp_id=".$gp_id, "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps, ["value" => $stats->gp_id])
            ->addLabel("pole_position_driver", "Pilote Pole :")
            ->addSelect("pole_position_driver", $poleDrivers, ["value" => $poleSelected])
            ->addLabel("pole_position_time", "Temps Pole :")
            ->addInput("text", "pole_position_time", ["value" => $stats->pole_position_time, "placeholder" => "1:12.562"])
            ->addLabel("fastest_lap_driver", "Pilote Fastest Lap :")
            ->addSelect("fastest_lap_driver", $fastestLapDrivers, ["value" => $fastestSelected])
            ->addLabel("fastest_lap_time", "Temps Fastest Lap :")
            ->addInput("text", "fastest_lap_time", ["value" => $stats->fastest_lap_time, "placeholder" => "1:12.562"])
            ->addSubmit("Mettre à jour")
            ->endForm();


        $this->render('dashboard/gp_stats/update', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }


    public function delete($gp_id)
    {
        $message = '';
        $classMsg = '';

        // Récupérer stats
        $stats = GpStatsModel::findByGpId($gp_id);
        if (!$stats) {
            $message = "Pole Position & Fastest Lap du GP introuvables.";
            $classMsg = "msg-error";

            $this->render('dashboard/gp_stats/index', [
                'list' => GpStatsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier saison active
        $gp = GpModel::find($gp_id);
        $season = SeasonsModel::findById($gp->season_id ?? null);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de supprimer : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_stats/index', [
                'list' => GpStatsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Nom GP
        $seasons = SeasonsModel::getActive();
        $circuits = CircuitsModel::all();
        $allGps = GpModel::all();

        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $gp) {
                if ($gp->season_id == $s->id) {
                    $gps[$gp->id] = $gp->category 
                                        . " - Saison " . $s->season_number 
                                        . " / GP " . $gp->gp_ordre 
                                        . " - " . ($circuitCountries[$gp->circuit_id] ?? 'Pays inconnu') 
                                        . " - " . ($gp->circuit_name ?? $gp->name ?? 'Circuit inconnu');
                }
            }
        }

        $gpName = $gps[$gp_id] ?? '';

        // Formulaire soumis ?
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new GpStatsModel();
            $pdo = $db->getConnection();

            try {
                $stmt = $pdo->prepare("DELETE FROM gp_stats WHERE gp_id = ?");
                if ($stmt->execute([$gp_id])) {
                    $message = "Pole Position & Fastest Lap du GP supprimées avec succès.";
                    $classMsg = "msg-success";

                    UpdatesLogModel::logUpdate('gp_stats', null, $gp_id, $_SESSION['user_id'], 'delete');
                } else {
                    $message = "Erreur lors de la suppression.";
                    $classMsg = "msg-error";
                }
            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression : " . $e->getMessage();
                $classMsg = "msg-error";
            }

            $this->render('dashboard/gp_stats/index', [
                'list' => GpStatsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vue confirmation
        $this->render('dashboard/gp_stats/delete', [
            'id' => $gp_id,
            'gpName' => $gpName,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

}
?>
