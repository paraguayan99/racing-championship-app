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

    // Afficher liste des résultats des GP
    public function index()
    {
        $gpPoints = GpPointsModel::allWithSeasonActive();
        $this->render('dashboard/gp_points/index', [
            'list' => $gpPoints
        ]);
    }

    // Créer un résultat de GP
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

        // Tableau circuit_id
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
            foreach ($allGps as $gpItem) {
                if ($gpItem->season_id == $s->id) {
                    $countryName = $circuitData[$gpItem->circuit_id]['country'] ?? 'Pays inconnu';
                    $circuitName = $circuitData[$gpItem->circuit_id]['name'] ?? 'Circuit inconnu';

                    $gps[$gpItem->id] =
                        $gpItem->category
                        . " - Saison " . $s->season_number
                        . " / GP " . $gpItem->gp_ordre
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
                $points_text = !empty(trim($_POST['points_text'])) ? strtoupper(substr(trim($_POST['points_text']), 0, 3)) : null;

                // Validation métier
                if ($position !== null && $position < 1) {
                    $message = "Erreur : La position doit être supérieure ou égale à 1";
                    $classMsg = "msg-error";
                    goto end_create;
                }

                if ($points_numeric < 0) {
                    $message = "Erreur : Les points ne peuvent pas être négatifs";
                    $classMsg = "msg-error";
                    goto end_create;
                }

                $db = new GpPointsModel();
                $pdo = $db->getConnection();

                if ($driver_id != 1) {
                    $checkStmt = $pdo->prepare("
                        SELECT 1 
                        FROM gp_points 
                        WHERE gp_id = ? AND driver_id = ?
                        LIMIT 1
                    ");
                    $checkStmt->execute([$gp_id, $driver_id]);

                    if ($checkStmt->fetch()) {
                        $message = "Erreur : Pilote déjà enregistré pour ce GP";
                        $classMsg = "msg-error";
                        goto end_create;
                    }
                }

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

                        // Ajout cache
                        $gp = GpModel::find($gp_id);
                        $cache = new \App\Core\PalmaresCache();
                        $cache->rebuild($gp->season_id);
                    } else {
                        $message = "Erreur lors de la création";
                        $classMsg = "msg-error";
                    }
                } catch (\PDOException $e) {
                    $message = "Erreur : Position déjà attribuée pour ce GP";
                    $classMsg = "msg-error";
                }

                end_create:
                ;
            } else {
                $message = "Erreur : informations manquantes";
                $classMsg = "msg-error";
            }
        }

        $selectedGpId        = $_POST['gp_id'] ?? null;
        $selectedDriverId    = $_POST['driver_id'] ?? null;
        $selectedTeamId      = $_POST['team_id'] ?? null;
        $selectedPosition    = $_POST['position'] ?? '';
        $selectedPointsNumeric = $_POST['points_numeric'] ?? '';
        $selectedPointsText  = $_POST['points_text'] ?? null;

        if ($isSuccess) {
            $selectedDriverId = null;
            $selectedTeamId = null;
            $selectedPointsText = null;
            $selectedPosition = '';
            $selectedPointsNumeric = '';
        }

        $form = new Form();

        $form->startForm("/gppoints/create", "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps, ["value" => $selectedGpId])
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", array_column($drivers, 'nickname', 'id'), ["value" => $selectedDriverId])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", array_column($teams, 'name', 'id'), ["value" => $selectedTeamId])
            ->addLabel("position", "Position :")
            ->addInput("number", "position", ["min" => 1, "value" => $selectedPosition])
            ->addLabel("points_numeric", "Points :")
            ->addInput("number", "points_numeric", ["min" => 0, "step" => "0.5", "value" => $selectedPointsNumeric])
            ->addLabel("points_text", "DNF-DNS-DSQ :")
            ->addSelect("points_text", [
                ''    => '',
                "DNF" => "DNF (Abandon)",
                "DNS" => "DNS (Non partant)",
                "DSQ" => "DSQ (Disqualifié)"
            ], ["value" => $selectedPointsText])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/gp_points/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour un résultat de GP
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

        $gp = GpModel::find($point->gp_id);
        $season = SeasonsModel::findById($gp->season_id ?? null);

        if (!$season || $season->status !== 'active') {
            $message = "Impossible de modifier ce résultat : la saison est désactivée";
            $classMsg = "msg-error";
            $this->render('dashboard/gp_points/index', [
                'list' => GpPointsModel::allWithSeasonActive(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Récupération des données pour les selects
        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $teams = TeamsModel::getActive();
        $allGps = GpModel::all();
        $circuits = CircuitsModel::all();

        $circuitData = [];
        foreach ($circuits as $c) {
            $circuitData[$c->id] = [
                'name' => $c->name ?? 'Circuit inconnu',
                'country' => $c->country ?? 'Pays inconnu'
            ];
        }

        $gps = [];
        foreach ($seasons as $s) {
            foreach ($allGps as $g) {
                if ($g->season_id == $s->id) {
                    $countryName = $circuitData[$g->circuit_id]['country'] ?? 'Pays inconnu';
                    $circuitName = $circuitData[$g->circuit_id]['name'] ?? 'Circuit inconnu';

                    $gps[$g->id] = $g->category
                        . " - Saison " . $s->season_number
                        . " / GP " . $g->gp_ordre
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
                $points_text = !empty(trim($_POST['points_text'])) ? strtoupper(substr(trim($_POST['points_text']), 0, 3)) : null;

                // Validation métier
                if ($position !== null && $position < 1) {
                    $this->renderUpdateForm(
                        $id, $gps, $drivers, $teams,
                        array_merge($_POST, ['position' => $position]),
                        "Erreur : La position doit être supérieure ou égale à 1",
                        "msg-error"
                    );
                    return;
                }

                if ($points_numeric < 0) {
                    $this->renderUpdateForm(
                        $id, $gps, $drivers, $teams,
                        array_merge($_POST, ['points_numeric' => $points_numeric]),
                        "Erreur : Les points ne peuvent pas être négatifs",
                        "msg-error"
                    );
                    return;
                }

                $db = new GpPointsModel();
                $pdo = $db->getConnection();

                // Vérification doublon pilote
                if ($driver_id != 1) {
                    $checkStmt = $pdo->prepare("
                        SELECT 1
                        FROM gp_points
                        WHERE gp_id = ? AND driver_id = ? AND id != ?
                        LIMIT 1
                    ");
                    $checkStmt->execute([$gp_id, $driver_id, $id]);

                    if ($checkStmt->fetch()) {
                        $this->renderUpdateForm(
                            $id, $gps, $drivers, $teams, $_POST,
                            "Erreur : Pilote déjà enregistré pour ce GP",
                            "msg-error"
                        );
                        return;
                    }
                }

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

                        // Ajout cache
                        $gp = GpModel::find($gp_id);
                        $cache = new \App\Core\PalmaresCache();
                        $cache->rebuild($gp->season_id);

                        // Retour à l'index seulement si succès
                        $this->render('dashboard/gp_points/index', [
                            'list' => GpPointsModel::allWithSeasonActive(),
                            'message' => $message,
                            'classMsg' => $classMsg
                        ]);
                        return;
                    } else {
                        // Erreur générale sur update -> retour formulaire
                        $this->renderUpdateForm(
                            $id, $gps, $drivers, $teams, $_POST,
                            "Erreur lors de la mise à jour",
                            "msg-error"
                        );
                        return;
                    }

                } catch (\PDOException $e) {
                    $this->renderUpdateForm(
                        $id, $gps, $drivers, $teams, $_POST,
                        "Erreur : Position déjà attribuée pour ce GP",
                        "msg-error"
                    );
                    return;
                }

            } else {
                $this->renderUpdateForm(
                    $id, $gps, $drivers, $teams, $_POST,
                    "Erreur : informations manquantes",
                    "msg-error"
                );
                return;
            }
        }

        // Affichage du formulaire pour GET
        $this->renderUpdateForm(
            $id,
            $gps,
            $drivers,
            $teams,
            [
                'gp_id' => $point->gp_id,
                'driver_id' => $point->driver_id,
                'team_id' => $point->team_id,
                'position' => $point->position,
                'points_numeric' => $point->points_numeric,
                'points_text' => $point->points_text
            ],
            $message,
            $classMsg
        );
    }

    // Centralisation du formulaire de mise à jour
    private function renderUpdateForm($id, $gps, $drivers, $teams, $data, $message, $classMsg)
    {
        $form = new Form();

        // Définir les options pour points_text
        $pointsTextOptions = [
            ''    => '',                // option vide par défaut
            'DNF' => 'DNF (Abandon)',
            'DNS' => 'DNS (Non partant)',
            'DSQ' => 'DSQ (Disqualifié)'
        ];

        $form->startForm("/gppoints/update/" . $id, "POST")
            ->addCSRF()
            ->addLabel("gp_id", "GP :")
            ->addSelect("gp_id", $gps, ["value" => $data['gp_id'] ?? null])
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", array_column($drivers, 'nickname', 'id'), ["value" => $data['driver_id'] ?? null])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", array_column($teams, 'name', 'id'), ["value" => $data['team_id'] ?? null])
            ->addLabel("position", "Position :")
            ->addInput("number", "position", ["min" => 1, "value" => $data['position'] ?? ''])
            ->addLabel("points_numeric", "Points :")
            ->addInput("number", "points_numeric", ["min" => 0, "step" => "0.5", "value" => $data['points_numeric'] ?? 0])
            ->addLabel("points_text", "DNF-DNS-DSQ :")
            ->addSelect("points_text", $pointsTextOptions, ["value" => $data['points_text'] ?? ''])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/gp_points/update', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer un résultat de GP
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        // Récupérer le point GP à supprimer
        $point = GpPointsModel::findById($id);
        if (!$point) {
            $message = "Résultat introuvable.";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
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

            // Retour liste avec message erreur
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

        // Construire les tableaux associatifs
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
            foreach (GpModel::all() as $gpItem) {
                if ($gpItem->season_id == $s->id) {
                    $countryName = $circuitCountries[$gpItem->circuit_id] ?? 'Pays inconnu';
                    $gpsMap[$gpItem->id] = $gpItem->category 
                                        . " - Saison " . $s->season_number 
                                        . " / GP " . $gpItem->gp_ordre 
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
                // Requete préparée
                $stmt = $pdo->prepare("DELETE FROM gp_points WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $message = "Résultat supprimé avec succès.";
                    $classMsg = "msg-success";

                    // Log des modifications
                    UpdatesLogModel::logUpdate('gp_points', null, $point->gp_id, $_SESSION['user_id'], 'delete');

                    // Ajout cache — $gp est déjà récupéré plus haut dans la méthode
                    $cache = new \App\Core\PalmaresCache();
                    $cache->rebuild($gp->season_id);
                } else {
                    $message = "Erreur lors de la suppression.";
                    $classMsg = "msg-error";
                }
            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression : " . $e->getMessage();
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
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