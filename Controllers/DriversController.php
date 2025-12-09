<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\DriversModel;
use App\Models\CountriesModel;

class DriversController extends Controller {

    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    public function index()
    {
        $drivers = DriversModel::all();
        $countries = CountriesModel::all();

        $this->render('dashboard/drivers/index', [
            'list' => $drivers,
            'countries' => $countries
        ]);
    }

    // Créer un pilote
    public function create()
    {
        $message = '';
        $classMsg = '';

        $countries = CountriesModel::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['nickname', 'country_id', 'status'])) {

                // Supprime les espaces au début et à la fin de la chaine de texte pour comparer si doublon (laisse des espaces entre les mots intacts)
                $nickname = trim($_POST['nickname']);
                $country_id = $_POST['country_id'];
                $status = $_POST['status'];

                $db = new DriversModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO drivers (nickname, country_id, status)
                        VALUES (?, ?, ?)
                    ");

                    if ($stmt->execute([$nickname, $country_id, $status])) {
                        $message = "Pilote créée avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Création échouée : ce pseudo existe déjà !";
                    } else {
                        $message = "Erreur lors de la création.";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : informations manquantes.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/drivers/index', [
                'list' => DriversModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $form = new Form();
        $countriesOptions = [];

        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=drivers&action=create", "POST")
            ->addCSRF()
            ->addLabel("nickname", "Pseudo :")
            ->addInput("text", "nickname")
            ->addLabel("country_id", "Pays :")
            ->addSelect("country_id", $countriesOptions, ["value" => 1])
            ->addLabel("status", "Statut :")
            ->addSelect("status", [
                'active' => 'Actif',
                'desactive' => 'Désactivé'
            ])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/drivers/create', [
            'countries' => $countries,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour un pilote
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new DriversModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM drivers WHERE id=?");
        $stmt->execute([$id]);
        $driver = $stmt->fetch();

        if (!$driver) {
            $message = "Pilote introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/drivers/index', [
                'list' => DriversModel::all(),
                'countries' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $countries = CountriesModel::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['nickname', 'country_id', 'status'])) {

                try {

                    // Supprime les espaces au début et à la fin de la chaine de texte pour comparer si doublon (laisse des espaces entre les mots intacts)
                    $name = trim($_POST['nickname']);

                    $stmt = $pdo->prepare("
                        UPDATE drivers 
                        SET nickname=?, country_id=?, status=?
                        WHERE id=?
                    ");

                    if ($stmt->execute([$name, $_POST['country_id'], $_POST['status'], $id])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Erreur : ce pseudo existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : informations manquantes.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/drivers/index', [
                'list' => DriversModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $form = new Form();
        $countriesOptions = [];

        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=drivers&action=update&id=" . $driver->id, "POST")
            ->addCSRF()
            ->addLabel("nickname", "Pseudo :")
            ->addInput("text", "nickname", ["value" => $driver->nickname])
            ->addLabel("country_id", "Pays :")
            ->addSelect("country_id", $countriesOptions, ["value" => $driver->country_id])
            ->addLabel("status", "Statut :")
            ->addSelect("status", [
                'active' => 'Actif',
                'desactive' => 'Désactivé'
            ], ["value" => $driver->status])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/drivers/update', [
            'list' => DriversModel::all(),
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

        $db = new DriversModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM drivers WHERE id=?");
        $stmt->execute([$id]);
        $driver = $stmt->fetch();

        if (!$driver) {
            $message = "Erreur : le pilote demandé n’existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/drivers/index', [
                'list' => DriversModel::all(),
                'countries' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $stmt = $pdo->prepare("DELETE FROM drivers WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "Pilote supprimée avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                // Ici, $e->getMessage() contient exactement le MESSAGE_TEXT du trigger SQL (contraintes de suppression)
                // $e->errorInfo[2] contient uniquement le MESSAGE_TEXT du trigger
                $message = $e->errorInfo[2] ?? $e->getMessage();
                $classMsg = "msg-error";
            }

            $this->render('dashboard/drivers/index', [
                'list' => DriversModel::all(),
                'countries' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $this->render('dashboard/drivers/delete', [
            'id' => $id,
            'nickname' => $driver->nickname,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
