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

    // Afficher liste des GP
    public function index()
    {
        $list = GpModel::allWithCountry(); 
        // récupère les GP avec countryName intégré

        $this->render('dashboard/gp/index', [
            'list' => $list
        ]);
    }

    // Créer un GP
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
                    // Requete préparée
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

                // Retour liste avec message succès ou erreur
                $this->render('dashboard/gp/index', [
                    'list' => GpModel::allWithCountry(),
                    'message' => $message,
                    'classMsg' => $classMsg
                ]);
                return;
            }

            $message = "Création échouée : informations manquantes.";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
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

        $form->startForm("/gp/create", "POST")
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

    // Mettre à jour un GP
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

            // Retour liste avec message erreur
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

            // Retour liste avec message erreur
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Elements des saisons actives seulement
        $seasons  = SeasonsModel::getActive();
        $circuits = CircuitsModel::getActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Form::validatePost($_POST, ['season_id', 'circuit_id', 'gp_ordre'])) {

                try {
                    // Requete préparée
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

                // Retour liste avec message succès ou erreur
                $this->render('dashboard/gp/index', [
                    'list' => GpModel::allWithCountry(),
                    'message' => $message,
                    'classMsg' => $classMsg
                ]);
                return;
            }

            $message = "Mise à jour échouée : informations manquantes.";
            $classMsg = "msg-error";

            // Retour liste avec message erreur
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
        $form->startForm("/gp/update/" . $row->id, "POST")
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

    // Supprimer un GP
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

            // Retour liste avec message erreur
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

            // Retour liste avec message erreur
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Requete préparée
                $stmt = $pdo->prepare("DELETE FROM gp WHERE id=?");

                if ($stmt->execute([$id])) {
                    $message = "GP supprimé du calendrier.";
                    $classMsg = "msg-success";
                } else {
                    $message = "Erreur lors de la suppression.";
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
                        'fk_gp_points_gp' => 'Impossible de supprimer : Le GP contient des résultats',
                        'fk_gp_stats_gp' => 'Impossible de supprimer : Le GP contient une Pole Position et un Fastest Lap',
                        'fk_penalties_gp' => 'Impossible de supprimer : Le GP contient des pénalités',
                    ];

                    // On choisit le message correspondant sinon un message générique
                    $message = $messages[$constraint] ?? "Impossible de supprimer : des éléments liés existent";
                } else {
                    // Autre type d'erreur SQL
                    $message = $e->errorInfo[2] ?? $e->getMessage();
                }
            }

            // Retour liste avec message succès ou erreur
            $this->render('dashboard/gp/index', [
                'list' => GpModel::allWithCountry(),
                'message' => $message,
                'classMsg' => $classMsg
            ]);
            return;
        }

        // Variables envoyées à la vue
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
