<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\GpModel;
use App\Models\SeasonsModel;
use App\Models\CircuitsModel;
use App\Models\CountriesModel;

class GpController extends Controller {

    public function __construct()
    {
        // Accès autorisé aux Admin et Modo
        $this->authMiddleware(["Administrateur", "Moderateur"]);
    }

    public function index()
    {
        $list = GpModel::allWithCountry(); // récupère les GP avec countryName intégré

        $this->render('dashboard/gp/index', [
            'list' => $list
        ]);
    }

    // CREATE
    public function create()
    {
        $message = '';
        $classMsg = '';

        // Récupère uniquement seasons actives + circuits actifs
        $seasons  = SeasonsModel::getActive();
        $circuits = CircuitsModel::getActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'circuit_id', 'gp_ordre'])) {

                $db = new GpModel();
                $pdo = $db->getConnection();

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO gp (season_id, circuit_id, gp_ordre)
                        VALUES (?, ?, ?)
                    ");

                    if ($stmt->execute([
                        $_POST['season_id'],
                        $_POST['circuit_id'],
                        $_POST['gp_ordre']
                    ])) {
                        $message = "GP ajouté avec succès au calendrier.";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de l'ajout.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur lors de la création.";
                    $classMsg = "msg-error";
                }

                $this->render('dashboard/gp/index', [
                    'list' => GpModel::allWithCountry(),
                    'message' => $message,
                    'classMsg' => $classMsg
                ]);
                return;
            }

            $message = "Création échouée : informations manquantes.";
            $classMsg = "msg-error";

            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Préparation formulaire
        $form = new Form();

        $seasonOptions = [];
        foreach ($seasons as $s) {
            $seasonOptions[$s->id] = 
                $s->category 
                . " - Saison " . $s->season_number
                . " (" . $s->videogame
                . " - " . $s->platform
                . ")";
        }

        // Récupérer tous les pays
        $countries = CountriesModel::all();

        // Préparer les options des circuits avec le nom du pays
        $circuitOptions = [];
        foreach ($circuits as $c) {
            $country = array_filter($countries, fn($co) => $co->id == $c->country_id);
            $country = reset($country);
            $countryName = $country->name ?? 'Pays inconnu';

            $circuitOptions[$c->id] = $countryName . " - " . $c->name;
        }

        // Trier par ordre alphabétique
        asort($seasonOptions);
        asort($circuitOptions);

        $form->startForm("index.php?controller=gp&action=create", "POST")
            ->addCSRF()
            ->addLabel("season_id", "Saison :")
            ->addSelect("season_id", $seasonOptions)
            ->addLabel("circuit_id", "Circuit :")
            ->addSelect("circuit_id", $circuitOptions)
            ->addLabel("gp_ordre", "Numéro du GP dans la saison :")
            ->addInput("number", "gp_ordre")
            ->addSubmit("Créer")
            ->endForm();

        $this->render('dashboard/gp/create', [
            'form' => $form,
            'seasons' => $seasons,
            'circuits' => $circuits
        ]);
    }

    // UPDATE
    public function update($id)
    {
        $message = '';
        $classMsg = '';

        $db = new GpModel();
        $pdo = $db->getConnection();

        $row = GpModel::find($id);

        if (!$row) {
            $message = "GP introuvable.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier saison active
        $season = SeasonsModel::findById($row->season_id);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de modifier : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Elements actifs seulement
        $seasons  = SeasonsModel::getActive();
        $circuits = CircuitsModel::getActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'circuit_id', 'gp_ordre'])) {

                try {
                    $stmt = $pdo->prepare("
                        UPDATE gp
                        SET season_id=?, circuit_id=?, gp_ordre=?
                        WHERE id=?
                    ");

                    if ($stmt->execute([
                        $_POST['season_id'],
                        $_POST['circuit_id'],
                        $_POST['gp_ordre'],
                        $id
                    ])) {
                        $message = "Mise à jour réussie.";
                        $classMsg = "msg-success";
                    } else {
                        $message = "Erreur lors de la mise à jour.";
                        $classMsg = "msg-error";
                    }

                } catch (\PDOException $e) {
                    $message = "Erreur lors de la mise à jour.";
                    $classMsg = "msg-error";
                }

                $this->render('dashboard/gp/index', [
                    'list' => GpModel::allWithCountry(),
                    'message' => $message,
                    'classMsg' => $classMsg
                ]);
                return;
            }

            $message = "Mise à jour échouée : informations manquantes.";
            $classMsg = "msg-error";

            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Formulaire
        $seasonOptions = [];
        foreach ($seasons as $s) {
            $seasonOptions[$s->id] = 
                $s->category 
                . " - Saison " . $s->season_number
                . " (" . $s->videogame
                . " - " . $s->platform
                . ")";
        }

        // Récupérer tous les pays
        $countries = CountriesModel::all();

        // Préparer les options des circuits avec le nom du pays
        $circuitOptions = [];
        foreach ($circuits as $c) {
            $country = array_filter($countries, fn($co) => $co->id == $c->country_id);
            $country = reset($country);
            $countryName = $country->name ?? 'Pays inconnu';

            $circuitOptions[$c->id] = $countryName . " - " . $c->name;
        }

        // Trier par ordre alphabétique
        asort($seasonOptions);
        asort($circuitOptions);

        $form = new Form();
        $form->startForm("index.php?controller=gp&action=update&id=" . $row->id, "POST")
            ->addCSRF()
            ->addLabel("season_id", "Saison :")
            ->addSelect("season_id", $seasonOptions, ["value" => $row->season_id])
            ->addLabel("circuit_id", "Circuit :")
            ->addSelect("circuit_id", $circuitOptions, ["value" => $row->circuit_id])
            ->addLabel("gp_ordre", "Numéro du GP :")
            ->addInput("number", "gp_ordre", ["value" => $row->gp_ordre])
            ->addSubmit("Mettre à jour")
            ->endForm();

        $this->render('dashboard/gp/update', [
            'form' => $form,
            'seasons' => $seasons,
            'circuits' => $circuits,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $message = '';
        $classMsg = '';

        // Récupérer saisons et circuits
        $seasons  = SeasonsModel::all();
        $circuits = CircuitsModel::all();

        // Préparer tableaux pour accès rapide
        $seasonNames = [];
        foreach ($seasons as $s) {
            $seasonNames[$s->id] = $s->category . " - Saison " . $s->season_number;
        }

        $circuitNames = [];
        $circuitCountries = [];
        foreach ($circuits as $c) {
            $circuitNames[$c->id] = $c->name;
            $circuitCountries[$c->id] = $c->country ?? 'Pays inconnu';
        }

        $db = new GpModel();
        $pdo = $db->getConnection();

        $row = GpModel::find($id);

        if (!$row) {
            $message = "Erreur : ce GP n'existe pas.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Vérifier saison active
        $season = SeasonsModel::findById($row->season_id);
        if (!$season || $season->status !== 'active') {
            $message = "Impossible de supprimer : la saison est désactivée.";
            $classMsg = "msg-error";
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $pdo->prepare("DELETE FROM gp WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "GP supprimé du calendrier.";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression.";
                    $classMsg = "msg-error";
                }

            } catch (\PDOException $e) {
                // Ici, $e->getMessage() contient exactement le MESSAGE_TEXT du trigger SQL (contraintes de suppression)
                // $e->errorInfo[2] contient uniquement le MESSAGE_TEXT du trigger
                $message = $e->errorInfo[2] ?? $e->getMessage();
                $classMsg = "msg-error";
            }

            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Variables envoyées à la vue delete.php
        $circuitName = $circuitNames[$row->circuit_id] ?? '';
        $seasonName = $seasonNames[$row->season_id] ?? '';
        $countryName = $circuitCountries[$row->circuit_id] ?? 'Pays inconnu';

        $this->render('dashboard/gp/delete', [
            'id' => $id,
            'name' => $circuitName,
            'seasonName' => $seasonName,
            'countryName' => $countryName,
            'message' => $message,
            'classMsg' => $classMsg
        ]);
    }

}
?>
