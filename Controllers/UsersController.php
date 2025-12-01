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

        // Créer le formulaire
        $form = new Form();
        $form->startForm("index.php?controller=users&action=create", "POST")
            ->addCSRF()
            ->addLabel("email", "Email :")
            ->addInput("email", "email")
            ->addLabel("password_hash", "Mot de passe :")
            ->addInput("password_hash", "password")
            ->addLabel("role_id", "Rôle :")
            ->addSelect("role_id", $rolesOptions)
            ->addInput("submit", "submit", ["value" => "Créer"])
            ->endForm();

        // Passer le formulaire à la vue
        $this->render('dashboard/users/index', [
            'list' => $users,
            'roles' => $roles,
            'form' => $form
        ]);
    }

        // Créer un utilisateur
    public function create()
    {
        $roles = \App\Models\RolesModel::allRoles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['email', 'password', 'role_id'])) {

                $email = $_POST['email'];
                $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $role_id = $_POST['role_id'];

                $db = new UsersModel();
                $pdo = $db->getConnection();

                $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, role_id) VALUES (?, ?, ?)");
                $stmt->execute([$email, $password_hash, $role_id]);

                header("Location: index.php?controller=users");
                exit;
            }
        }

        // Affichage du formulaire
        $form = new Form();
        $rolesOptions = [];
        foreach ($roles as $r) {
            $rolesOptions[$r->id] = $r->name;
        }

        $form->startForm("index.php?controller=users&action=create", "POST")
             ->addCSRF()
             ->addLabel("email", "Email :")
             ->addInput("email", "email")
             ->addLabel("password_hash", "Mot de passe :")
             ->addInput("password_hash", "password")
             ->addLabel("role_id", "Rôle :")
             ->addSelect("role_id", $rolesOptions)
             ->addInput("submit", "submit", ["value" => "Créer"])
             ->endForm();

        $this->render('dashboard/users/create', ['form' => $form]);
    }

    // Mettre à jour un utilisateur
    public function update($id)
    {
        $db = new UsersModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user) {
            die("Utilisateur introuvable");
        }

        $roles = \App\Models\RolesModel::allRoles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Form::validatePost($_POST, ['email', 'role_id'])) {
                $stmt = $pdo->prepare("UPDATE users SET email=?, role_id=? WHERE id=?");
                $stmt->execute([$_POST['email'], $_POST['role_id'], $id]);

                header("Location: index.php?controller=users");
                exit;
            }
        }

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
             ->addInput("submit", "submit", ["value" => "Mettre à jour"])
             ->endForm();

        $this->render('dashboard/users/update', [
            'form' => $form,
            'user' => $user
        ]);
    }

    // Supprimer un utilisateur
    public function delete($id)
    {
        $db = new UsersModel();
        $pdo = $db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
            $stmt->execute([$id]);

            header("Location: index.php?controller=users");
            exit;
        }

        $this->render('dashboard/users/delete', ['id' => $id]);
    }
}
?>