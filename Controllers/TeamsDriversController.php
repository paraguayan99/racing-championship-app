<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\TeamsDriversModel;
use App\Models\SeasonsModel;
use App\Models\DriversModel;
use App\Models\TeamsModel;

class TeamsDriversController extends Controller {

    public function __construct()
    {
        // Accès autorisé aux Administrateurs et Modérateurs
        $this->authMiddleware(["Administrateur", "Moderateur"]);
    }

    // Affiche la liste des associations des saisons actives
    public function index()
    {
        $list = TeamsDriversModel::all();

        $this->render('dashboard/teams_drivers/index', [
            'list' => $list
        ]);
    }

    // Créer une association pilote - team
    public function create()
    {
        $message = '';
        $classMsg = '';

        // Ne récupère que les éléments ACTIFS pour ne pas surcharger les select
        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $teams   = TeamsModel::getActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'driver_id', 'team_id'])) {

                $db = new TeamsDriversModel();
                $pdo = $db->getConnection();

                try {
                    // Requete préparée
                    $stmt = $pdo->prepare("
                        INSERT INTO teams_drivers (season_id, driver_id, team_id)
                        VALUES (?, ?, ?)
                    ");

                    if ($stmt->execute([
                        $_POST['season_id'],
                        $_POST['driver_id'],
                        $_POST['team_id']
                    ])) {
                        $message = "Association crée avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur : Ce pilote est déjà associé à un team dans cette saison.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur : Ce pilote est déjà associé à un team dans cette saison.";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : informations manquantes.";
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Formulaire
        $form = new Form();

        $seasonOptions = [];
        foreach ($seasons as $s) {
            $seasonOptions[$s->id] =
                        $s->category
                ." - Saison " . $s->season_number
                ." (" . $s->videogame
                ." - " . $s->platform
                .")";
        }

        $driverOptions = [];
        foreach ($drivers as $d) $driverOptions[$d->id] = $d->nickname;

        $teamOptions = [];
        foreach ($teams as $t) $teamOptions[$t->id] = $t->name;

        $form->startForm("/teamsdrivers/create", "POST")
            ->addCSRF()
            ->addLabel("season_id", "Saison :")
            ->addSelect("season_id", $seasonOptions)
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", $teamOptions)
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", $driverOptions)
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/teams_drivers/create', [
            'form' => $form,
            'seasons' => $seasons,
            'drivers' => $drivers,
            'teams' => $teams,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Modifier une association pilote - team
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new TeamsDriversModel();
        $pdo = $db->getConnection();

        $row = TeamsDriversModel::find($id);

        if (!$row) {
            $message = "Association introuvable";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier le statut active / desactive de la saison
        $season = SeasonsModel::findById($row->season_id);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de modifier : la saison est désactivée.";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }


        // Ne récupère que les éléments ACTIFS pour ne pas surcharger les select
        $seasons = SeasonsModel::getActive();
        $drivers = DriversModel::getActive();
        $teams   = TeamsModel::getActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'driver_id', 'team_id'])) {

                try {
                    // Requete préparée
                    $stmt = $pdo->prepare("
                        UPDATE teams_drivers 
                        SET season_id=?, driver_id=?, team_id=?
                        WHERE id=?
                    ");

                    if ($stmt->execute([
                        $_POST['season_id'],
                        $_POST['driver_id'],
                        $_POST['team_id'],
                        $id
                    ])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur lors de la mise à jour";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : informations manquantes.";
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Formulaire
        $form = new Form();

        $seasonOptions = [];
        foreach ($seasons as $s) {
            $seasonOptions[$s->id] =
                        $s->category
                ." - Saison " . $s->season_number
                ." (" . $s->videogame
                ." - " . $s->platform
                .")";
        }

        $driverOptions = [];
        foreach ($drivers as $d) $driverOptions[$d->id] = $d->nickname;

        $teamOptions = [];
        foreach ($teams as $t) $teamOptions[$t->id] = $t->name;

        $form->startForm("/teamsdrivers/update/" . $row->id, "POST")
            ->addCSRF()
            ->addLabel("season_id", "Saison :")
            ->addSelect("season_id", $seasonOptions, ["value" => $row->season_id])
            ->addLabel("team_id", "Team :")
            ->addSelect("team_id", $teamOptions, ["value" => $row->team_id])
            ->addLabel("driver_id", "Pilote :")
            ->addSelect("driver_id", $driverOptions, ["value" => $row->driver_id])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/teams_drivers/update', [
            'form' => $form,
            'seasons' => $seasons,
            'drivers' => $drivers,
            'teams' => $teams,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer une association pilote - team
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $seasons = SeasonsModel::all();
        $drivers = DriversModel::all();
        $teams   = TeamsModel::all();

        // Récupération des infos
        $seasonOptions = [];
        foreach ($seasons as $s) {
            $seasonOptions[$s->id] = $s->category
                . " - Saison " . $s->season_number;
        }

        $driverOptions = [];
        foreach ($drivers as $d) {
            $driverOptions[$d->id] = $d->nickname;
        }

        $teamOptions = [];
        foreach ($teams as $t) {
            $teamOptions[$t->id] = $t->name;
        }


        $db = new TeamsDriversModel();
        $pdo = $db->getConnection();

        $row = TeamsDriversModel::find($id);

        if (!$row) {
            $message = "Erreur : l’association demandée n’existe pas.";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier le statut active / desactive de la saison
        $season = SeasonsModel::findById($row->season_id);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de supprimer : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                // Requete préparée
                $stmt = $pdo->prepare("DELETE FROM teams_drivers WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "Association supprimée avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                // $e->errorInfo[2] contient le MESSAGE_TEXT du trigger
                $message = $e->errorInfo[2] ?? $e->getMessage();
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/teams_drivers/index', [
                'list' => TeamsDriversModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Récupération des infos
        $seasonName = $seasonOptions[$row->season_id] ?? '';
        $driverName = $driverOptions[$row->driver_id] ?? '';
        $teamName   = $teamOptions[$row->team_id] ?? '';

        $this->render('dashboard/teams_drivers/delete', [
            'id' => $id,
            'seasonName' => $seasonName,
            'driverName' => $driverName,
            'teamName' => $teamName,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
