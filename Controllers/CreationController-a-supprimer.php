<?php
namespace App\Controllers;

use App\Core\Form;
use App\Entities\Creation;
use App\Entities\Lecteur;
use App\Models\CreationModel;

class CreationController extends Controller
{
    // Méthode qui permet d'afficher la liste des livres
    public function index()
    {
        // On instancie la classe CreationModel
        $creations = new CreationModel();

        // On stocke dans une variable le return de la méthode findAll()
        $list = $creations->findAll();

        $this->render('creation/index', ['list' => $list]);
    }


    // Méthode pour ajouter un livre
    public function add()
    {
        // On controle si les champs du formulaire sont remplis
        if (Form::validatePost($_POST, ['titre', 'auteur'])) 
        {
            // On déclare la session pour récupérer le token actif
            session_start();

            // TEST FAILLE CSRF : on le compare au token reçu avec la méthode POST
            // ET VERIFICATION des accès d'administration à la gestion des livres
            if ($_POST['token'] == $_SESSION['token'] && $_SESSION['admin'] == "admin1234") 
            {
                // On instancie l'entite creation
                $creation = new Creation();

                // On l'hydrate
                $creation->setTitre($_POST['titre']);
                $creation->setAuteur($_POST['auteur']);
                
                // On instancie le model creation
                $model = new CreationModel();
                $model->create($creation);

                // On redirige l'utilisateur vers la liste des livres
                header("Location:index.php?controller=creation&action=index");
            } else {
                    // ERREUR : on redirige l'utilisateur vers la page d'erreur
                    header("Location:error.php?msgError=addToken");
                    die();
            }
        }
        // On instancie la classe Form pour construire le formulaire d'ajout
        $form = new Form();

        // On construit le formulaire d'ajout
        // On ajoute le début du formulaire manuellement dans Views/creation/add.php pour y insérer un input hidden du token
        // $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("titre", "Titre", ["class" => "form-label"]);
        $form->addInput("text", "titre", ["id" => "titre", "class" => "form-control", "placeholder" => "Ajouter un titre"]);

        $form->addLabel("auteur", "Auteur", ["class" => "form-label"]);
        $form->addInput("text", "auteur", ["id" => "auteur", "class" => "form-control", "placeholder" => "Ajouter un auteur"]);

        $form->addInput("submit", "add", ["value" => "Ajouter le livre dans la bibliothèque", "class" => "mt-2 btn btn-primary"]);
        $form->endForm();

        // On envoie le formulaire dans la vue add.php
        $this->render('creation/add', ["addForm" => $form->getFormElements()]);
    }


    // Méthode pour la mise à jour d'un livre
    public function updateCreation($id)
    {
        // On contrôle si les champs du formulaire sont remplis
        if (Form::validatePost($_POST, ['titre', 'auteur'])) 
        {
            // On déclare la session pour récupérer le token actif
            session_start();

            // TEST FAILLE CSRF : on le compare au token reçu avec la méthode POST
            // ET VERIFICATION des accès d'administration à la gestion des livres
            if ($_POST['token'] == $_SESSION['token'] && $_SESSION['admin'] == "admin1234") 
            {
                // On instancie l'entité "Creation"
                $creation = new Creation();

                // On l'hydrate
                $creation->setTitre($_POST['titre']);
                $creation->setAuteur($_POST['auteur']);
                $creation->setDate_emprunt($_POST['date_emprunt']);
                $creation->setDate_retour($_POST['date_retour']);
                $creation->setId_lecteur($_POST['id_lecteur']);

                // On instancie le modèle "creation" pour l'update
                $creations = new CreationModel();
                $creations->update($id, $creation);

                // On redirige l'utilisateur vers la liste des créations
                header("Location:index.php?controller=creation&action=index");
            } else {
            // ERREUR : on redirige l'utilisateur vers la page d'erreur
                header("Location:error.php?msgError=addToken");
                die();
            }
        }
        // On instancie le model pour récupérer les informations de la création
        $creations = new CreationModel();
        $creation = $creations->find($id);

        // On construit le formulaire de mise à jour
        // On ajoute le début du formulaire manuellement dans Views/creation/updateCreation.php pour y insérer un input hidden du token
        $form = new Form();

        // $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("titre", "Titre", ["class" => "form-label"]);
        $form->addInput("text", "titre", ["id" => "titre", "class" => "form-control", "placeholder" => "Ajouter un titre", "value" => $creation->titre]);

        $form->addLabel("auteur", "Auteur", ["class" => "form-label"]);
        $form->addInput("text", "auteur", ["id" => "auteur", "class" => "form-control", "placeholder" => "Ajouter un auteur", "value" => $creation->auteur]);

        $form->addLabel("date_emprunt", "Date d'emprunt", ["class" => "form-label"]);
        $form->addInput("text", "date_emprunt", ["id" => "date_emprunt", "class" => "form-control", "placeholder" => "Ajouter une date / ou laisser vide pour rendre disponible le livre", "value" => $creation->date_emprunt]);

        $form->addLabel("date_retour", "Date de retour", ["class" => "form-label"]);
        $form->addInput("text", "date_retour", ["id" => "date_retour", "class" => "form-control", "placeholder" => "Ajouter une date / ou laisser vide pour rendre disponible le livre", "value" => $creation->date_retour]);

        $form->addLabel("id_lecteur", "ID du lecteur", ["class" => "form-label"]);
        $form->addInput("text", "id_lecteur", ["id" => "id_lecteur", "class" => "form-control", "placeholder" => "Ajouter un lecteur / ou laisser vide pour rendre disponible le livre", "value" => $creation->id_lecteur]);
        
        $form->addInput("submit", "update", ["value" => "Modifier les informations concernant ce livre", "class" => "mt-2 btn btn-primary"]);
        $form->endForm();

        // On renvoie vers la vue le formulaire de mise à jour et le message d'erreur potentiel
        error_reporting(0);
        $this->render("creation/updateCreation", ["updateForm" => $form->getFormElements(), "erreur" => $erreur]);
    }


    // Méthode pour la suppression d'un livre
    public function deleteCreation($id)
    {
        if (isset($_POST['true'])) {
            // On déclare la session pour récupérer le token actif
            session_start();

            // TEST FAILLE CSRF : on le compare au token reçu avec la méthode POST
            // ET VERIFICATION des accès d'administration à la gestion des livres
            if ($_POST['token'] == $_SESSION['token'] && $_SESSION['admin'] == "admin1234")
            {
                // On instancie la classe CreationModel pour exécuter la suppression avec la méthode delete()
                // en récupérant l'id de la création du lien "OUI"
                $creations = new CreationModel();
                $creations->delete($id);
                // On redirige l'utilisateur vers la liste des créations
                header("Location:index.php?controller=creation&action=index");
            } else {
                // On redirige l'utilisateur vers la page d'erreur
                header("Location:error.php?msgError=addToken");
                die();
            }
        } elseif (isset($_POST['false'])) {
            // On redirige l'utilisateur vers la liste des créations
            header("Location:index.php?controller=creation&action=index");
        } else {
            // On récupère la création avec la méthode find()
            $creations = new CreationModel();
            $creation = $creations->find($id);
        }
        // On renvoie vers la vue la création sélectionnée
        $this->render('creation/deleteCreation', ["creation" => $creation]);
    }


    // Méthode pour ajouter un lecteur ou bibliothequaire (autorisations d'administration à accorder dans la BDD SQL)
    public function addLecteur()
    {   // On controle si les champs du formulaire sont remplis
        if (Form::validatePost($_POST, ['email', 'pseudo', 'mdp', 'nom', 'prenom'])) 
        {
            // On instancie l'entite creation
            $creation = new Lecteur();

            // On l'hydrate
            $creation->setEmail($_POST['email']);
            $creation->setPseudo($_POST['pseudo']);
            $creation->setMdp($_POST['mdp']);
            $creation->setNom($_POST['nom']);
            $creation->setPrenom($_POST['prenom']);
            
            // On instancie le model creation
            $model = new CreationModel();
            $model->createLecteur($creation);

            // On redirige l'utilisateur vers la page indiquant le succès de la création du compte
            header("Location:error.php?msgError=addLecteurSuccess");
            die();
        }

        // On instancie la classe Form pour construire le formulaire d'ajout
        $form = new Form();

        // On construit le formulaire d'ajout
        // On ajoute le début du formulaire manuellement dans Views/creation/add.php pour y insérer un input hidden du token
        // $form->startForm("#", "POST", ["enctype" => "multipart/form-data"]);
        $form->addLabel("email", "E-mail", ["class" => "form-label"]);
        $form->addInput("text", "email", ["id" => "email", "class" => "form-control", "placeholder" => "Ajouter un email"]);

        $form->addLabel("pseudo", "Pseudo", ["class" => "form-label"]);
        $form->addInput("text", "pseudo", ["id" => "pseudo", "class" => "form-control", "placeholder" => "Ajouter un pseudo"]);

        $form->addLabel("mdp", "Mot de passe", ["class" => "form-label"]);
        $form->addInput("text", "mdp", ["id" => "mdp", "class" => "form-control", "placeholder" => "Ajouter un mot de passe"]);

        $form->addLabel("nom", "Nom", ["class" => "form-label"]);
        $form->addInput("text", "nom", ["id" => "nom", "class" => "form-control", "placeholder" => "Ajouter un nom"]);

        $form->addLabel("prenom", "Prénom", ["class" => "form-label"]);
        $form->addInput("text", "prenom", ["id" => "prenom", "class" => "form-control", "placeholder" => "Ajouter un prénom"]);


        $form->addInput("submit", "add", ["value" => "Valider la création du compte", "class" => "mt-2 btn btn-primary"]);
        $form->endForm();

        // On envoie le formulaire dans la vue addLecteur.php
        $this->render('creation/addLecteur', ["addForm" => $form->getFormElements()]);
    }


    // Méthode pour la demande d'emprunt d'un livre
    public function updateEmprunt($id)
    {
    session_start();

    // On verifie s'il existe déjà une date d'emprunt, pour bloquer une tentative de forçage d'emprunt via l'URL (id_livre en get)
    $creations = new CreationModel();
    $dateEmprunt = $creations->verifierDateEmpruntExisteDeja($id);

    if (isset($_SESSION['token']) && empty($dateEmprunt)) 
    {
        $idLecteur = $_SESSION['id_lecteur'];

        // On instancie CreationModel
        $creations = new CreationModel();

        // Vérifie la limite d'emprunts
        if ($creations->verifierLimiteEmprunts($idLecteur)) {
            // Le lecteur a déjà 3 emprunts ou plus => bloquer l'emprunt et afficher message d'erreur
            $_SESSION['ThreeBooks'] = TRUE;
            header("Location:index.php?controller=creation&action=index");
            exit;
        }

        // On instancie Creation
        $creation = new Creation();

        // On récupère la date du jour
        date_default_timezone_set('Europe/Paris');
        $date_today = date("Y/m/d");

        // On ajoute 3 semaines à la date d'emprunt
        $aujourdhui = new \DateTime();
        $aujourdhui->add(new \DateInterval('P3W'));
        $date_3weeks = $aujourdhui->format('Y/m/d');

        // On l'hydrate
        $creation->setDate_emprunt($date_today);
        $creation->setDate_retour($date_3weeks);
        $creation->setId_lecteur($idLecteur);

        // On instancie CreationModel pour mettre à jour dates et id du lecteur
        $creations = new CreationModel();
        $creations->updateEmprunt($id, $creation);

        // On redirige l'utilisateur vers la liste des créations
        header("Location:index.php?controller=creation&action=index");
        exit;
    } 
    else 
    {
        // ERREUR : on redirige l'utilisateur vers la page d'erreur
        header("Location:error.php?msgError=addToken");
        die();
    }
    }

}
?>