<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\ManualAdjustmentsModel;
use App\Models\SeasonsModel;
use App\Models\DriversModel;
use App\Models\TeamsModel;
use App\Models\UpdatesLogModel;
use App\Models\UsersModel;

class ManualAdjustmentsController extends Controller {

    public function __construct()
    {
        // Seuls les administrateurs peuvent accéder à ce controller
        $this->authMiddleware("Administrateur");

        // Pour élargir les accès aux autres rôles en array
        // $this->authMiddleware(["Administrateur", "Moderateur", "User"]);
    }

    public function index()
    {
        $manuals = ManualAdjustmentsModel::all();

        $this->render('dashboard/manual_adjustments/index', [
            'list' => $manuals
        ]);
    }

    // Créer un ajustement manuel
    public function create()
    {
        $message = '';
        $classMsg = '';

        $seasons = SeasonsModel::all();
        $drivers = DriversModel::all();
        $teams   = TeamsModel::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'points'])) {

                $season_id = $_POST['season_id'];
                $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;
                $team_id   = !empty($_POST['team_id']) ? $_POST['team_id'] : null;
                $points    = $_POST['points'];
                // Supprime les espaces au début et à la fin de la chaine de texte (laisse des espaces entre les mots intacts)
                $comment = !empty(trim($_POST['comment'])) ? trim($_POST['comment']) : null;

                $db = new ManualAdjustmentsModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO manual_adjustments (season_id, driver_id, team_id, points, comment)
                        VALUES (?, ?, ?, ?, ?)
                    ");

                    if ($stmt->execute([$season_id, $driver_id, $team_id, $points, $comment])) {
                        $message = "Ajustement créé avec succès";
                        $classMsg = "msg-success";

                        // AJOUT DANS LA TABLE UPDATES_LOG POUR AVOIR UN HISTORIQUE DES MAJ 
                        $gp_id = null; // manual_adjustments affecte les saisons, pas les gp
                        UpdatesLogModel::logUpdate('manual_adjustments', $season_id, $gp_id, $_SESSION['user_id'], 'create');
                    } else {
                        $message = "Erreur lors de la création.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {

                    $message = "Erreur : Un pilote ou un team doit être sélectionné.";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Création échouée : informations manquantes.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/manual_adjustments/index', [
                'list' => ManualAdjustmentsModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Création du formulaire
        $form = new Form();

        $seasonOptions = [];
        foreach ($seasons as $s) {
            // Concaténation de toutes les infos
            $seasonOptions[$s->id] = 
                        $s->category 
                ." - Saison " . $s->season_number
                ." (" . $s->videogame 
                ." - " . $s->platform
                .")";
        }

        $driverOptions = ["" => "Aucun"];
        foreach ($drivers as $d) {
            $driverOptions[$d->id] = $d->nickname;
        }

        $teamOptions = ["" => "Aucune"];
        foreach ($teams as $t) {
            $teamOptions[$t->id] = $t->name;
        }

        $form->startForm("index.php?controller=manualadjustments&action=create", "POST")
            ->addCSRF()
            ->addLabel("season_id", "Saison :")
            ->addSelect("season_id", $seasonOptions)
            ->addLabel("driver_id", "Pilote | Ajustements Classement Pilotes")
            ->addSelect("driver_id", $driverOptions)
            ->addLabel("team_id", "Team | Ajustements Classement Constructeurs")
            ->addSelect("team_id", $teamOptions)
            ->addLabel("points", "Points :")
            ->addInput("number", "points")
            ->addLabel("comment", "Commentaire :")
            ->addInput("text", "comment")
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/manual_adjustments/create', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Mettre à jour un ajustement
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new ManualAdjustmentsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM manual_adjustments WHERE id=?");
        $stmt->execute([$id]);
        $manual = $stmt->fetch();

        if (!$manual) {
            $message = "Ajustement introuvable";
            $classMsg = "msg-error";

            $this->render('dashboard/manual_adjustments/index', [
                'list' => ManualAdjustmentsModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        $seasons = SeasonsModel::all();
        $drivers = DriversModel::all();
        $teams   = TeamsModel::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'points'])) {

                try {
                    $stmt = $pdo->prepare("
                        UPDATE manual_adjustments
                        SET season_id=?, driver_id=?, team_id=?, points=?, comment=?
                        WHERE id=?
                    ");

                    $driver_id = !empty($_POST['driver_id']) ? $_POST['driver_id'] : null;
                    $team_id   = !empty($_POST['team_id']) ? $_POST['team_id'] : null;

                    if ($stmt->execute([
                        $_POST['season_id'],
                        $driver_id,
                        $team_id,
                        $_POST['points'],
                        // Supprime les espaces au début et à la fin de la chaine de texte (laisse des espaces entre les mots intacts)
                        trim($_POST['comment']),
                        $id
                    ])) {
                        $message = "Mise à jour réussie";
                        $classMsg = "msg-success";

                        // AJOUT DANS LA TABLE UPDATES_LOG POUR AVOIR UN HISTORIQUE DES MAJ 
                        $gp_id = null; // manual_adjustments affecte les saisons, pas les gp
                        UpdatesLogModel::logUpdate('manual_adjustments', $_POST['season_id'], $gp_id, $_SESSION['user_id'], 'update');
                    } else {
                        $message = "Erreur lors de la mise à jour";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur : Un pilote ou un team doit être sélectionné.";
                    $classMsg = "msg-error";
                }

            } else {
                $message = "Mise à jour échouée : informations manquantes.";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/manual_adjustments/index', [
                'list' => ManualAdjustmentsModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // FORMULAIRE
        $form = new Form();

        $seasonOptions = [];
        foreach ($seasons as $s) {
            // Concaténation de toutes les infos
            $seasonOptions[$s->id] = 
                        $s->category 
                ." - Saison " . $s->season_number
                ." (" . $s->videogame 
                ." - " . $s->platform
                .")";
        }

        $driverOptions = ["" => "Aucun"];
        foreach ($drivers as $d) {
            $driverOptions[$d->id] = $d->nickname;
        }

        $teamOptions = ["" => "Aucune"];
        foreach ($teams as $t) {
            $teamOptions[$t->id] = $t->name;
        }

        $form->startForm("index.php?controller=manualadjustments&action=update&id=" . $manual->id, "POST")
            ->addCSRF()
            ->addLabel("season_id", "Saison :")
            ->addSelect("season_id", $seasonOptions, ["value" => $manual->season_id])
            ->addLabel("driver_id", "Pilote | Ajustements Classement Pilotes")
            ->addSelect("driver_id", $driverOptions, ["value" => $manual->driver_id])
            ->addLabel("team_id", "Team | Ajustements Classement Constructeurs")
            ->addSelect("team_id", $teamOptions, ["value" => $manual->team_id])
            ->addLabel("points", "Points :")
            ->addInput("number", "points", ["value" => $manual->points])
            ->addLabel("comment", "Commentaire :")
            ->addInput("text", "comment", ["value" => $manual->comment])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/manual_adjustments/update', [
            'form' => $form,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // Supprimer un ajustement
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        // On instancie ces Models pour préparer les variables à afficher sur la page delete
        $seasons = SeasonsModel::all();
        $drivers = DriversModel::all();
        $teams   = TeamsModel::all();

        // Construire les options pour pouvoir récupérer les noms
        $seasonOptions = [];
        foreach ($seasons as $s) {
            $seasonOptions[$s->id] = $s->category
                ." - Saison " . $s->season_number;
        }

        $driverOptions = ["" => ""];
        foreach ($drivers as $d) {
            $driverOptions[$d->id] = $d->nickname;
        }

        $teamOptions = ["" => ""];
        foreach ($teams as $t) {
            $teamOptions[$t->id] = $t->name;
        }

        $db = new ManualAdjustmentsModel();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM manual_adjustments WHERE id=?");
        $stmt->execute([$id]);
        $manual = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$manual) {
            $message = "Erreur : cet ajustement n’existe pas.";
            $classMsg = "msg-error";

            $this->render('dashboard/manual_adjustments/index', [
                'list' => ManualAdjustmentsModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {
                $stmt = $pdo->prepare("DELETE FROM manual_adjustments WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "Ajustement supprimé avec succès";
                    $classMsg = "msg-success";

                    // AJOUT DANS LA TABLE UPDATES_LOG POUR AVOIR UN HISTORIQUE DES MAJ 
                    $gp_id = null; // manual_adjustments affecte les saisons, pas les gp
                    $season_id = $manual['season_id'];
                    UpdatesLogModel::logUpdate('manual_adjustments', $season_id, $gp_id, $_SESSION['user_id'], 'delete');
                } else {
                    $message = "Erreur lors de la suppression";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                $message = "Erreur lors de la suppression";
                $classMsg = "msg-error";
            }

            $this->render('dashboard/manual_adjustments/index', [
                'list' => ManualAdjustmentsModel::all(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Récupération des infos sinon un champ vide
        $seasonName = $seasonOptions[$manual['season_id']] ?? '';
        $driverName = $driverOptions[$manual['driver_id']] ?? '';
        $teamName   = $teamOptions[$manual['team_id']] ?? '';

        $this->render('dashboard/manual_adjustments/delete', [
            'id' => $id,
            'seasonName' => $seasonName,
            'driverName' => $driverName,
            'teamName' => $teamName,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }
}
?>
