<?php
namespace App\Controllers;

use App\Models\ClassementsModel;

class ClassementsController extends Controller
{
    public function standings(...$args)
    {
        $seasonFilter = $args['season_id'] ?? $_GET['season_id'] ?? 'active';

        if ($seasonFilter === 'active') {
            // Pilotes et Teams pour toutes les saisons actives
            $drivers = ClassementsModel::getDriversStandingsActive();
            $teams = ClassementsModel::getTeamsStandingsActive();
            $gpList = ClassementsModel::getSeasonGPResultsActive();
            $penalties = ClassementsModel::getPenaltiesActive();
        } else {
            // Pilotes et Teams pour une saison spécifique
            $drivers = ClassementsModel::getDriversStandingsBySeason($seasonFilter);
            $teams = ClassementsModel::getTeamsStandingsBySeason($seasonFilter);
            $gpList = ClassementsModel::getSeasonGPResultsBySeason($seasonFilter);
            $penalties = ClassementsModel::getPenaltiesBySeason($seasonFilter);
        }

        // Récupérer couleur de la catégorie
        $categoryColors = [];
        // Drivers
        foreach ($drivers as $d) {
            if (!isset($categoryColors[$d->category])) {
                $categoryColors[$d->category] = $d->category_color;
            }
        }

        // Grouper pilotes par catégorie
        $listByCategory = [];
        foreach ($drivers as $d) {
            $listByCategory[$d->category][] = $d;
        }

        // Grouper teams par catégorie
        $teamsByCategory = [];
        foreach ($teams as $t) {
            $teamsByCategory[$t->category][] = $t;
        }

        // Grouper GP par catégorie
        $gpByCategory = [];
        foreach ($gpList as $gp) {
            $gpByCategory[$gp->category][] = $gp;
        }

        // Grouper Pénalités par catégorie
        $penaltiesByCategory = [];
        foreach ($penalties as $p) {
            $penaltiesByCategory[$p->category_name][] = $p;
        }

        // Toutes les saisons pour le select
        $seasons = ClassementsModel::getAllSeasonsForSelect();

        // Récupérer la dernière mise à jour GP (sans filtrer par saison)
        $lastGPUpdate = ClassementsModel::getLastGPUpdate();

        // Passer toutes les variables à la vue
        $this->render(
            'classements/standings',
            compact(
                'listByCategory',
                'teamsByCategory',
                'gpByCategory',
                'seasons',
                'seasonFilter',
                'penaltiesByCategory',
                'lastGPUpdate',
                'categoryColors'
            )
        );
    }

    public function gpDetails($gp_id)
    {
        // Vérifie que l'ID du GP est fourni
        if (!$gp_id) {
            echo "<p>GP non trouvé.</p>";
            return;
        }

        // Récupère les détails complets du GP depuis le modèle
        $gp = ClassementsModel::getGPDetails($gp_id);

        // Vérifie que le GP existe réellement
        if (!$gp) {
            echo "<p>GP non trouvé.</p>";
            return;
        }

        // Inclut la vue pour afficher le modal
        include __DIR__ . '/../Views/classements/standings_gp_details.php';
    }


}
?>
