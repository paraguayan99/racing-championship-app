<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\CategoriesModel;

class CategoriesController extends Controller {
    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");
    }

    // LISTE DES CATÉGORIES
    public function index()
    {
        // Récupère toutes les catégories
        $categories = CategoriesModel::all();

        $this->render('dashboard/categories/index', [
            'list' => $categories
        ]);
    }

    // CRÉER UNE CATÉGORIE
    public function create()
    {
        // Initialiser les variables pour éviter warnings
        $message = '';
        $classMsg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name', 'color', 'status'])) {

                // Supprime les espaces au début et à la fin de la chaine de texte pour comparer si doublon (laisse des espaces entre les mots intacts)
                $name = trim($_POST['name']);
                $color = $_POST['color'];
                $status = $_POST['status'];

                // Connexion DB
                $db = new CategoriesModel();
                $pdo = $db->getConnection();

                try {
                    // Requete préparée
                    $stmt = $pdo->prepare("SELECT * FROM categories WHERE name=?");
                    $stmt->execute([$name]);
                    if ($stmt->fetch()) {
                        $message = "Erreur : ce nom existe déjà !";
                        $classMsg = "msg-error";

                        $this->render('dashboard/categories/index', [
                            'list' => CategoriesModel::all(),
                            'message' => $message,
                            'classMsg' => $classMsg
                        ]);
                        return;
                    }

                    // INSERT
                    $stmt = $pdo->prepare("INSERT INTO categories (name, color, status) VALUES (?,?,?)");
                    if ($stmt->execute([$name, $color, $status])) {
                        $message = "Catégorie créée avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Erreur : ce nom est déjà utilisé !";
                    } else {
                        $message = "Erreur lors de la création.";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : champ manquant.";
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/categories/index', [
                'list' => CategoriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET => formulaire création
        $form = new Form();

        $form->startForm("index.php?controller=categories&action=create", "POST")
            ->addCSRF()
            ->addLabel("name", "Nom de la catégorie :")
            ->addInput("text", "name")
            ->addLabel("color", "Couleur :")
            ->addInput("color", "color")
            ->addLabel("status", "Statut :")
            ->addSelect("status", [
                "active" => "Active",
                "desactive" => "Désactivée"
            ])
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/categories/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // METTRE À JOUR UNE CATÉGORIE
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new CategoriesModel();
        $pdo = $db->getConnection();

        // Vérifier si catégorie existe
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->execute([$id]);
        $category = $stmt->fetch();

        if (!$category) {
            $message = "Catégorie introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/categories/index', [
                'list' => CategoriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Si données reçues en POST, traitement de la requete préparée
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['name', 'color', 'status'])) {

                try {
                    // Supprime les espaces au début et à la fin de la chaine de texte 
                    // pour comparer si doublon (laisse des espaces entre les mots intacts)
                    $name = trim($_POST['name']);

                    $stmt = $pdo->prepare("SELECT * FROM categories WHERE name=? AND id!=?");
                    $stmt->execute([$name, $id]);

                    if ($stmt->fetch()) {
                        $message = "Erreur : ce nom existe déjà !";
                        $classMsg = "msg-error";

                        $this->render('dashboard/categories/index', [
                            'list' => CategoriesModel::all(),
                            'message' => $message,
                            'classMsg' => $classMsg
                        ]);
                        return;
                    }

                    // UPDATE
                    // Supprime les espaces au début et à la fin de la chaine de texte 
                    // pour comparer si doublon (laisse des espaces entre les mots intacts)
                    $name = trim($_POST['name']);

                    $stmt = $pdo->prepare("UPDATE categories SET name=?, color=?, status=? WHERE id=?");

                    if ($stmt->execute([$name, $_POST['color'], $_POST['status'], $id])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur lors de la mise à jour.";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Champ manquant.";
                $classMsg = "msg-error";
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/categories/index', [
                'list' => CategoriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET => form update
        $form = new Form();

        $form->startForm("index.php?controller=categories&action=update&id=" . $category->id, "POST")
            ->addCSRF()
            ->addLabel("name", "Nom de la catégorie :")
            ->addInput("text", "name", ["value" => $category->name])
            ->addLabel("color", "Couleur :")
            ->addInput("color", "color", ["value" => $category->color])
            ->addLabel("status", "Statut :")
            ->addSelect("status", [
                "active" => "Active",
                "desactive" => "Désactivée"
            ], ["value" => $category->status])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/categories/update', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // SUPPRIMER UNE CATÉGORIE
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        $db = new CategoriesModel();
        $pdo = $db->getConnection();

        // Requete préparée
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->execute([$id]);
        $category = $stmt->fetch();

        if (!$category) {
            $message = "Erreur : cette catégorie n'existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/categories/index', [
                'list' => CategoriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // POST => supprimer
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "Catégorie supprimée avec succès";
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
                        'fk_seasons_category' => 'Impossible de supprimer : La catégorie contient des saisons',
                    ];

                    // On choisit le message correspondant sinon un message générique
                    $message = $messages[$constraint] ?? "Impossible de supprimer : des éléments liés existent";
                } else {
                    // Autre type d'erreur SQL
                    $message = $e->errorInfo[2] ?? $e->getMessage();
                }
            }

            $this->render('dashboard/categories/index', [
                'list' => CategoriesModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET => page de confirmation
        $this->render('dashboard/categories/delete', [
            'id' => $id,
            'name' => $category->name,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}

