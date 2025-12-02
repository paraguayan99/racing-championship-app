<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\CircuitsModel;

class CircuitsController extends Controller {

    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    // INDEX : liste des circuits
    public function index()
    {
        $circuits = CircuitsModel::all();
        $countries = CircuitsModel::allCountries();

        $this->render('dashboard/circuits/index', [
            'list' => $circuits,
            'countries' => $countries
        ]);
    }

    // Créer un circuit
    public function create()
    {
        $message = '';
        $classMsg = '';

        $countries = CircuitsModel::allCountries();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name', 'country_id'])) {

                $name = $_POST['name'];
                $country_id = $_POST['country_id'];

                $db = new CircuitsModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO circuits (name, country_id) 
                        VALUES (?, ?)
                    ");

                    if ($stmt->execute([$name, $country_id])) {
                        $message = "Circuit créé avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Création échouée : ce nom de circuit existe déjà !";
                    } else {
                        $message = "Erreur lors de la création du circuit.";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : nom ou pays manquant.";
                $classMsg = "msg-error";
            }

            // Retour à la liste après traitement
            $this->render('dashboard/circuits/index', [
                'list' => CircuitsModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET => afficher formulaire de création
        $form = new Form();

        $countriesOptions = [];
        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=circuits&action=create", "POST")
            ->addCSRF()
            ->addLabel("name", "Nom du circuit :")
            ->addInput("text", "name")
            ->addLabel("country_id", "Pays :")
            ->addSelect("country_id", $countriesOptions)
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/circuits/create', [
            'countries' => $countries,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour un circuit
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new CircuitsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM circuits WHERE id=?");
        $stmt->execute([$id]);
        $circuit = $stmt->fetch();

        if (!$circuit) {
            $message = "Circuit introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/circuits/index', [
                'list' => CircuitsModel::all(),
                'countries' => CircuitsModel::allCountries(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $countries = CircuitsModel::allCountries();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name', 'country_id', 'status'])) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE circuits 
                        SET name=?, country_id=?, status=? 
                        WHERE id=?
                    ");

                    if ($stmt->execute([$_POST['name'], $_POST['country_id'], $_POST['status'], $id])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Erreur : ce nom existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : informations manquantes";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/circuits/index', [
                'list' => CircuitsModel::all(),
                'countries' => $countries,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET => afficher formulaire update
        $form = new Form();
        $countriesOptions = [];
        foreach ($countries as $c) {
            $countriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=circuits&action=update&id=" . $circuit->id, "POST")
            ->addCSRF()
            ->addLabel("name", "Nom :")
            ->addInput("text", "name", ["value" => $circuit->name])
            ->addLabel("country_id", "Pays :")
            ->addSelect("country_id", $countriesOptions, ["value" => $circuit->country_id])
            ->addLabel("status", "Statut :")
            ->addSelect("status", [
                "active" => "Actif",
                "desactive" => "Désactivé"
            ], ["value" => $circuit->status])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/circuits/update', [
            'list' => CircuitsModel::all(),
            'countries' => $countries,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer un circuit
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $db = new CircuitsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM circuits WHERE id=?");
        $stmt->execute([$id]);
        $circuit = $stmt->fetch();

        if (!$circuit) {
            $message = "Erreur : le circuit demandé n’existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/circuits/index', [
                'list' => CircuitsModel::all(),
                'countries' => CircuitsModel::allCountries(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $stmt = $pdo->prepare("DELETE FROM circuits WHERE id=?");
                if ($stmt->execute([$id])) {
                    $message = "Circuit supprimé avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/circuits/index', [
                'list' => CircuitsModel::all(),
                'countries' => CircuitsModel::allCountries(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET ⇒ page confirmation
        $this->render('dashboard/circuits/delete', [
            'id' => $id,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
