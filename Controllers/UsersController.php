<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\UsersModel;
use App\Models\RolesModel;

class UsersController extends Controller {
    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    public function index()
    {
        // On instancie la classe et on stocke dans une variable le return de la méthode all et allRoles
        $users = UsersModel::all();
        $roles = RolesModel::allRoles();

        // Préparer les options du select pour les rôles
        $rolesOptions = [];
        foreach ($roles as $r) {
            $rolesOptions[$r->id] = $r->name;
        }

        // Passer le formulaire à la vue
        $this->render('dashboard/users/index', [
            'list' => $users,
            'roles' => $roles
        ]);
    }

    // Créer un utilisateur
    public function create()
    {
        // Initialiser les variables pour éviter les warnings
        $message = '';
        $classMsg = '';

        $roles = RolesModel::allRoles();


        // SI POST : traiter le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['email', 'password', 'role_id'])) {

                $email = $_POST['email'];
                $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $role_id = $_POST['role_id'];

                $db = new UsersModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role_id) VALUES (?, ?, ?)");

                    if ($stmt->execute([$email, $password_hash, $role_id])) {
                        $message = "Utilisateur créé avec succès";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    if ($e->getCode() == 23000) {
                        $message = "Création échouée : cet email existe déjà !";
                    } else {
                        $message = "Erreur lors de la création de l'utilisateur.";
                    }

                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : email, mot de passe ou rôle manquant.";
                $classMsg = "msg-error";
            }

            // Après traitement du formulaire en POST : afficher la liste avec message
            $this->render('dashboard/users/index', [
                'list' => UsersModel::all(),
                'roles' => $roles,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Si pas de POST mais action=create en GET : afficher le formulaire de création

        $form = new Form();
        $rolesOptions = [];

        foreach ($roles as $r) {
            $rolesOptions[$r->id] = $r->name;
        }

        $form->startForm("index.php?controller=users&action=create", "POST")
            ->addCSRF()
            ->addLabel("email", "Email :")
            ->addInput("email", "email")
            ->addLabel("password", "Mot de passe :")
            ->addInput("password", "password")
            ->addLabel("role_id", "Rôle :")
            ->addSelect("role_id", $rolesOptions)
            // ->addInput("submit", "submit", ["value" => "Créer"])
            ->addSubmit("Créer")
            ->endForm();

        // Affichage du formulaire dans la vue DÉDIÉE
        $this->render('dashboard/users/create', [
            'roles' => $roles,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour un utilisateur
    public function update($id)
    {
        // Initialiser les variables pour éviter les warnings
        $message = '';
        $classMsg = '';

        $db = new UsersModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            $message = "Utilisateur introuvable";
            $classMsg = "msg-error";

            // Affichage de la page liste
            $this->render('dashboard/users/index', [
                'list' => UsersModel::all(),
                'roles' => RolesModel::allRoles(),
                // 'form' => null,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $roles = RolesModel::allRoles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Form::validatePost($_POST, ['email', 'role_id'])) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET email=?, role_id=? WHERE id=?");
                    if ($stmt->execute([$_POST['email'], $_POST['role_id'], $id])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }
                } catch (\PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $message = "Erreur : cet email existe déjà !";
                    } else {
                        $message = "Erreur lors de la mise à jour";
                    }
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : email ou rôle non renseigné";
                $classMsg = "msg-error";
            }

            // Afficher directement la liste comme pour create
            $this->render('dashboard/users/index', [
                'list' => UsersModel::all(),
                'roles' => $roles,
                // 'form' => null,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Si on arrive ici, c'est un GET => afficher formulaire update
        $form = new Form();
        $rolesOptions = [];
        foreach ($roles as $r) {
            $rolesOptions[$r->id] = $r->name;
        }

        $form->startForm("index.php?controller=users&action=update&id=" . $user->id, "POST")
            ->addCSRF()
            ->addLabel("email", "Email :")
            ->addInput("email", "email", ["value" => $user->email])
            ->addLabel("role_id", "Rôle :")
            ->addSelect("role_id", $rolesOptions, ["value" => $user->role_id])
            // ->addInput("submit", "submit", ["value" => "Mettre à jour"])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/users/update', [
            'list' => UsersModel::all(),
            'roles' => $roles,
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer un utilisateur
    public function delete($id)
    {
        // Initialiser les variables pour éviter les warnings
        $message = '';
        $classMsg = '';

        $db = new UsersModel();
        $pdo = $db->getConnection();

        // Vérifier si l'utilisateur existe AVANT de faire quoi que ce soit
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            // Utilisateur inexistant → message d’erreur + retour liste
            $message = "Erreur : l’utilisateur demandé n’existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/users/index', [
                'list' => UsersModel::all(),
                'roles' => RolesModel::allRoles(),
                // 'form' => null,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Si on est ici → utilisateur existe
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
                if ($stmt->execute([$id])) {
                    $message = "Utilisateur supprimé avec succès";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression";
                $classMsg = "msg-error";
            }

            // Retour à la liste avec message
            $this->render('dashboard/users/index', [
                'list' => UsersModel::all(),
                'roles' => RolesModel::allRoles(),
                // 'form' => null,
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // GET ⇒ afficher page de confirmation
        $this->render('dashboard/users/delete', [
            'id' => $id,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>