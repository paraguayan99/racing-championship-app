<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\TeamsModel;
use App\Models\CountriesModel;

class TeamsController extends Controller {

    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    public function index()
    {
        $teams = TeamsModel::all();
        $countries = CountriesModel::all();

        // Préparer select pays
        $countriesOptions = [];
        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $this->render('dashboard/teams/index', [
            'list' => $teams,
            'countries' => $countries
        ]);
    }

    // Créer une équipe
    public function create()
    {
        $message = '';
        $classMsg = '';

        $countries = CountriesModel::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name', 'country_id'])) {

                $name = $_POST['name'];
                $logo = $_POST['logo'] ?? null;
                $country_id = $_POST['country_id'];
                $status = $_POST['status'] ?? 'active';

                $db = new TeamsModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO teams (name, logo, country_id, status)
                        VALUES (?, ?, ?, ?)
                    ");

                    if ($stmt->execute([$name, $logo, $country_id, $status])) {
                        $message = "Équipe créée avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Création échouée : ce nom d’équipe existe déjà !";
                    } else {
                        $message = "Erreur lors de la création de l’équipe.";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : nom ou pays manquant.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Form GET
        $form = new Form();
        $countriesOptions = [];
        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=teams&action=create", "POST")
            ->addCSRF()
            ->addLabel("name", "Nom de l’équipe :")
            ->addInput("text", "name")
            ->addLabel("logo", "URL ou chemin du logo :")
            ->addInput("text", "logo")
            ->addLabel("country_id", "Pays :")
            ->addSelect("country_id", $countriesOptions)
            ->addLabel("status", "Statut :")
            ->addSelect("status", ['active' => 'Actif', 'desactive' => 'Désactivé'])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/teams/create', [
            'countries' => $countries,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour une équipe
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new TeamsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM teams WHERE id=?");
        $stmt->execute([$id]);
        $team = $stmt->fetch();

        if (!$team) {
            $message = "Équipe introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $countries = CountriesModel::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name', 'country_id'])) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE teams SET name=?, logo=?, country_id=?, status=? WHERE id=?
                    ");

                    if ($stmt->execute([
                        $_POST['name'],
                        $_POST['logo'],
                        $_POST['country_id'],
                        $_POST['status'],
                        $id
                    ])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $message = "Erreur : ce nom d’équipe existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : nom ou pays manquant";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Form GET
        $form = new Form();
        $countriesOptions = [];
        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=teams&action=update&id=" . $team->id, "POST")
            ->addCSRF()
            ->addLabel("name", "Nom de l’équipe :")
            ->addInput("text", "name", ["value" => $team->name])
            ->addLabel("logo", "URL ou chemin du logo :")
            ->addInput("text", "logo", ["value" => $team->logo])
            ->addLabel("country_id", "Pays :")
            ->addSelect("country_id", $countriesOptions, ["value" => $team->country_id])
            ->addLabel("status", "Statut :")
            ->addSelect("status", ['active' => 'Actif', 'desactive' => 'Désactivé'], ["value" => $team->status])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/teams/update', [
            'list' => TeamsModel::all(),
            'countries' => $countries,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer une équipe
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $db = new TeamsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM teams WHERE id=?");
        $stmt->execute([$id]);
        $team = $stmt->fetch();

        if (!$team) {
            $message = "Erreur : l’équipe demandée n’existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $stmt = $pdo->prepare("DELETE FROM teams WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "Équipe supprimée avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $this->render('dashboard/teams/delete', [
            'id' => $id,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
