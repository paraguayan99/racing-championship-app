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
    }

    // Affiche la liste des équipes
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

                // Supprime les espaces au début et à la fin de la chaine de texte 
                // pour comparer si doublon (laisse des espaces entre les mots intacts)
                $name = trim($_POST['name']);
                $logo = $_POST['logo'] ?? null;
                $color = trim($_POST['color']) ?? null;
                $country_id = $_POST['country_id'];
                $status = $_POST['status'] ?? 'active';

                $db = new TeamsModel();
                $pdo = $db->getConnection();

                try {
                    // Requete préparée
                    $stmt = $pdo->prepare("
                        INSERT INTO teams (name, logo, color, country_id, status)
                        VALUES (?, ?, ?, ?, ?)
                    ");

                    if ($stmt->execute([$name, $logo, $color, $country_id, $status])) {
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

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Formulaire
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
            ->addLabel("color", "Couleur :")
            ->addInput("color", "color")
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

        // Requete préparée
        $stmt = $pdo->prepare("SELECT * FROM teams WHERE id=?");
        $stmt->execute([$id]);
        $team = $stmt->fetch();

        if (!$team) {
            $message = "Équipe introuvable";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
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

                    // Supprime les espaces au début et à la fin de la chaine de texte 
                    // pour comparer si doublon (laisse des espaces entre les mots intacts)
                    $name = trim($_POST['name']);
                    $color = null;

                    // Si la case supprimer n'est pas cochée, on garde la couleur choisie
                    if (empty($_POST['clear_color'])) {
                        $color = $_POST['color'] ?? null;
                    }

                    // Requete préparée
                    $stmt = $pdo->prepare("
                        UPDATE teams SET name=?, logo=?, color=?, country_id=?, status=? WHERE id=?
                    ");

                    if ($stmt->execute([
                        $name,
                        $_POST['logo'] ?? null,
                        $color,
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
                        $message = "Erreur : ce nom d’équipe (ou cette couleur) existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : nom ou pays manquant";
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/teams/index', [
                'list' => TeamsModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Formulaire
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
            ->addLabel("color", "Couleur :")
            ->addInput("color", "color", ["value" => $team->color])
            ->addLabel("clear_color", "Supprimer la couleur :")
            ->addInput("checkbox", "clear_color", ["value" => 1])
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

        // Requete préparée
        $stmt = $pdo->prepare("SELECT * FROM teams WHERE id=?");
        $stmt->execute([$id]);
        $team = $stmt->fetch();

        if (!$team) {
            $message = "Erreur : l’équipe demandée n’existe pas.";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
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
                // Requete préparée
                $stmt = $pdo->prepare("DELETE FROM teams WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "Équipe supprimée avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                $classMsg = "msg-error";

                // Gestion des erreurs de contrainte foreign key
                if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                    // On récupère le nom de la contrainte depuis le message MySQL
                    preg_match('/CONSTRAINT `(.*?)`/', $e->errorInfo[2], $matches);
                    $constraint = $matches[1] ?? '';

                    // Messages personnalisés pour chaque contrainte
                    $messages = [
                        'fk_teams_drivers_team' => 'Impossible de supprimer : Le team est associé à un pilote dans une saison',
                        'fk_gp_points_team' => 'Impossible de supprimer : Le team est associé à un résultat de GP',
                        'fk_manual_adjustments_team' => 'Impossible de supprimer : Le team a des ajustements manuels',
                        'fk_penalties_team' => 'Impossible de supprimer : Le team est associé à une pénalité',
                    ];

                    // On choisit le message correspondant sinon un message générique
                    $message = $messages[$constraint] ?? "Impossible de supprimer : des éléments liés existent";
                } else {
                    // Autre type d'erreur SQL
                    $message = $e->errorInfo[2] ?? $e->getMessage();
                }
            }

            // Retour liste avec message succès ou erreur
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
            'name' => $team->name,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
