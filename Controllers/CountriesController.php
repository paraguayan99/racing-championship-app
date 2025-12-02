<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\CountriesModel;

class CountriesController extends Controller {

    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    // Liste des pays
    public function index()
    {
        $countries = CountriesModel::all();

        $this->render('dashboard/countries/index', [
            'list' => $countries
        ]);
    }

    // Créer un pays
    public function create()
    {
        // Initialiser les variables pour éviter les warnings
        $message = '';
        $classMsg = '';

        // SI POST : traiter le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name'])) {

                $name = $_POST['name'];
                // Forcer le code si absent ou trop court (<3 lettres)
                $code = (empty($_POST['code']) || strlen($_POST['code']) < 3) 
                        ? strtoupper(substr($name, 0, 3)) 
                        : strtoupper($_POST['code']); // on met aussi en majuscule pour uniformité
                $flag = $_POST['flag'] ?? null;

                $db = new CountriesModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("INSERT INTO countries (name, code, flag) VALUES (?, ?, ?)");

                    if ($stmt->execute([$name, $code, $flag])) {
                        $message = "Pays créé avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Erreur : ce pays (ou code à 3 lettres) existe déjà !";
                    } else {
                        $message = "Erreur lors de la création du pays.";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : nom du pays manquant.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/countries/index', [
                'list' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET : afficher formulaire création
        $form = new Form();
        $form->startForm("index.php?controller=countries&action=create", "POST")
            ->addCSRF()
            ->addLabel("name", "Nom du pays :")
            ->addInput("text", "name")
            ->addLabel("code", "Code 3 lettres :")
            ->addInput("text", "code")
            ->addLabel("flag", "URL ou chemin du drapeau :")
            ->addInput("text", "flag")
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/countries/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour un pays
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new CountriesModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM countries WHERE id=?");
        $stmt->execute([$id]);
        $country = $stmt->fetch();

        if (!$country) {
            $message = "Pays introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/countries/index', [
                'list' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Form::validatePost($_POST, ['name'])) {

                // Forcer le code si absent ou trop court (<3 lettres)
                $code = (empty($_POST['code']) || strlen($_POST['code']) < 3) 
                        ? strtoupper(substr($_POST['name'], 0, 3)) 
                        : strtoupper($_POST['code']); // on met aussi en majuscule pour uniformité

                try {
                    $stmt = $pdo->prepare("UPDATE countries SET name=?, code=?, flag=? WHERE id=?");
                    if ($stmt->execute([$_POST['name'], $code ?? null, $_POST['flag'] ?? null, $id])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $message = "Erreur : ce pays (ou code à 3 lettres) existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : nom du pays manquant";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/countries/index', [
                'list' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET : afficher formulaire update
        $form = new Form();
        $form->startForm("index.php?controller=countries&action=update&id=" . $country->id, "POST")
            ->addCSRF()
            ->addLabel("name", "Nom du pays :")
            ->addInput("text", "name", ["value" => $country->name])
            ->addLabel("code", "Code 3 lettres :")
            ->addInput("text", "code", ["value" => $country->code])
            ->addLabel("flag", "URL ou chemin du drapeau :")
            ->addInput("text", "flag", ["value" => $country->flag])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/countries/update', [
            'list' => CountriesModel::all(),
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer un pays
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $db = new CountriesModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM countries WHERE id=?");
        $stmt->execute([$id]);
        $country = $stmt->fetch();

        if (!$country) {
            $message = "Erreur : le pays demandé n’existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/countries/index', [
                'list' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $pdo->prepare("DELETE FROM countries WHERE id=?");
                if ($stmt->execute([$id])) {
                    $message = "Pays supprimé avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }
            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/countries/index', [
                'list' => CountriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET : page confirmation suppression
        $this->render('dashboard/countries/delete', [
            'id' => $id,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
