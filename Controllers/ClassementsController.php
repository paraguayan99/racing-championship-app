<?php
namespace App\Controllers;

use App\Models\ClassementsModel;

class ClassementsController extends Controller
{
    // Page d'accueil des classements
    public function index()
    {
        $this->render('classements/index');
    }

    public function standings(...$args)
    {
        $seasonFilter = $args['season_id'] ?? $_GET['season_id'] ?? 'active';

        if ($seasonFilter === 'active') {
            // Pilotes et Teams pour toutes les saisons actives
            $drivers = ClassementsModel::getDriversStandingsActive();
            $teams = ClassementsModel::getTeamsStandingsActive();
            $gpList = ClassementsModel::getSeasonGPResultsActive();
        } else {
            // Pilotes et Teams pour une saison spécifique
            $drivers = ClassementsModel::getDriversStandingsBySeason($seasonFilter);
            $teams = ClassementsModel::getTeamsStandingsBySeason($seasonFilter);
            $gpList = ClassementsModel::getSeasonGPResultsBySeason($seasonFilter);
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

        // Toutes les saisons pour le select
        $seasons = ClassementsModel::getAllSeasonsForSelect();

        $this->render(
            'classements/standings',
            compact('listByCategory', 'teamsByCategory', 'gpByCategory', 'seasons', 'seasonFilter')
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
        include __DIR__ . '/../Views/classements/_gp_details.php';
    }













    public function driverAwards()
    {
        $list = ClassementsModel::getDriverAwards();
        $this->render('classements/driver_awards', compact('list'));
    }

    public function driverStatsAllSeasons()
    {
        $list = ClassementsModel::getDriverStatsAllSeasons();
        $this->render('classements/driver_stats_all_seasons', compact('list'));
    }

    // ----- GP STATS -----
    public function gpStatsSummary()
    {
        $list = ClassementsModel::getGpStatsSummary();
        $this->render('classements/gp_stats_summary', compact('list'));
    }

    // ----- CONSTRUCTEURS / TEAMS -----
    public function teamsStandings()
    {
        $list = ClassementsModel::getTeamsStandings();
        $this->render('classements/teams_standings', compact('list'));
    }

    public function teamAwards()
    {
        $list = ClassementsModel::getTeamAwards();
        $this->render('classements/team_awards', compact('list'));
    }

    public function teamPointsAllSeasons()
    {
        $list = ClassementsModel::getTeamPointsAllSeasons();
        $this->render('classements/team_points_all_seasons', compact('list'));
    }
}
?>
