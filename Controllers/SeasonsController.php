<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\SeasonsModel;
use App\Models\CategoriesModel;

class SeasonsController extends Controller {

    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    // Liste des saisons
    public function index()
    {
        $seasons = SeasonsModel::all();
        $categories = SeasonsModel::allCategories();

        $this->render('dashboard/seasons/index', [
            'list' => $seasons,
            'categories' => $categories
        ]);
    }

    // Créer une saison
    public function create()
    {
        // Initialiser les variables pour éviter les warnings
        $message = '';
        $classMsg = '';

        $categories = SeasonsModel::allCategories();

        // SI POST : traiter le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_number', 'category_id', 'videogame','platform', 'status'])) {

                $season_number = $_POST['season_number'];
                $category_id = $_POST['category_id'];
                $videogame = $_POST['videogame'];
                $platform = $_POST['platform'];
                $status = $_POST['status'];

                $db = new SeasonsModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("INSERT INTO seasons (season_number, category_id, videogame, platform, status) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$season_number, $category_id, $videogame, $platform, $status]);
                    $message = "Saison créée avec succès";
                    $classMsg = "msg-success";
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $message = "Cette combinaison de numéro de saison et catégorie existe déjà !";
                    } else {
                        $message = "Erreur lors de la création de la saison.";
                    }
                    $classMsg = "msg-error";
                }
            } else {
                $message = "Création échouée : tous les champs sont obligatoires.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/seasons/index', [
                'list' => SeasonsModel::all(),
                'categories' => $categories,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $form = new Form();
        $categoriesOptions = [];
        foreach ($categories as $c) {
            $categoriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=seasons&action=create", "POST")
            ->addCSRF()
            ->addLabel("season_number", "Numéro de saison :")
            ->addInput("number", "season_number")
            ->addLabel("category_id", "Catégorie :")
            ->addSelect("category_id", $categoriesOptions)
            ->addLabel("videogame", "Jeu vidéo :")
            ->addInput("text", "videogame")
            ->addLabel("platform", "Plateforme :")
            ->addInput("text", "platform")
            ->addLabel("status", "Statut :")
            ->addSelect("status", ['active' => 'Active', 'desactive' => 'Désactivée'])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/seasons/create', [
            'categories' => $categories,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour une saison
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new SeasonsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM seasons WHERE id=?");
        $stmt->execute([$id]);
        $season = $stmt->fetch();

        if (!$season) {
            $message = "Saison introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/seasons/index', [
                'list' => SeasonsModel::all(),
                'categories' => SeasonsModel::allCategories(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $categories = SeasonsModel::allCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Form::validatePost($_POST, ['season_number', 'category_id', 'videogame', 'platform', 'status'])) {
                try {
                    $stmt = $pdo->prepare("UPDATE seasons SET season_number=?, category_id=?, videogame=?, platform=?, status=? WHERE id=?");
                    if ($stmt->execute([$_POST['season_number'], $_POST['category_id'], $_POST['videogame'], $_POST['platform'], $_POST['status'], $id])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $message = "Cette combinaison de numéro de saison et catégorie existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }
                    $classMsg = "msg-error";
                }
            } else {
                $message = "Mise à jour échouée : tous les champs sont obligatoires";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/seasons/index', [
                'list' => SeasonsModel::all(),
                'categories' => $categories,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $form = new Form();
        $categoriesOptions = [];
        foreach ($categories as $c) {
            $categoriesOptions[$c->id] = $c->name;
        }

        $form->startForm("index.php?controller=seasons&action=update&id=" . $season->id, "POST")
            ->addCSRF()
            ->addLabel("season_number", "Numéro de saison :")
            ->addInput("number", "season_number", ["value" => $season->season_number])
            ->addLabel("category_id", "Catégorie :")
            ->addSelect("category_id", $categoriesOptions, ["value" => $season->category_id])
            ->addLabel("videogame", "Jeu vidéo :")
            ->addInput("text", "videogame", ["value" => $season->videogame])
            ->addLabel("platform", "Plateforme :")
            ->addInput("text", "platform", ["value" => $season->platform])
            ->addLabel("status", "Statut :")
            ->addSelect("status", ['active' => 'Active', 'desactive' => 'Désactivée'], ["value" => $season->status])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/seasons/update', [
            'list' => SeasonsModel::all(),
            'categories' => $categories,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer une saison
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $db = new SeasonsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("
            SELECT seasons.*, categories.name AS category_name
            FROM seasons
            JOIN categories ON seasons.category_id = categories.id
            WHERE seasons.id = ?
        ");
        $stmt->execute([$id]);
        $season = $stmt->fetch();

        if (!$season) {
            $message = "Erreur : la saison demandée n'existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/seasons/index', [
                'list' => SeasonsModel::all(),
                'categories' => SeasonsModel::allCategories(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $pdo->prepare("DELETE FROM seasons WHERE id=?");
                if ($stmt->execute([$id])) {
                    $message = "Saison supprimée avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }
            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/seasons/index', [
                'list' => SeasonsModel::all(),
                'categories' => SeasonsModel::allCategories(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $this->render('dashboard/seasons/delete', [
            'id' => $id,
            'season_number' => $season->season_number,
            'category_name' => $season->category_name,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
