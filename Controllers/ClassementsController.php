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

    // public function driversStandings(...$args)
    // {
    //     $seasonFilter = $args['season_id'] ?? $_GET['season_id'] ?? 'active';

    //     if ($seasonFilter === 'active') {
    //         $drivers = ClassementsModel::getDriversStandingsActive();
    //     } else {
    //         $drivers = ClassementsModel::getDriversStandingsBySeason($seasonFilter);
    //     }

    //     // Regrouper par catégorie
    //     $listByCategory = [];
    //     foreach ($drivers as $driver) {
    //         $listByCategory[$driver->category][] = $driver;
    //     }

    //     // Pour le select détaillé
    //     $seasons = ClassementsModel::getAllSeasonsForSelect();

    //     $this->render('classements/drivers_standings', compact('listByCategory', 'seasons', 'seasonFilter'));
    // }

    public function driversStandings(...$args)
    {
        $seasonFilter = $args['season_id'] ?? $_GET['season_id'] ?? 'active';

        if ($seasonFilter === 'active') {
            $drivers = ClassementsModel::getDriversStandingsActive();
            $teams = ClassementsModel::getTeamsStandingsActive();
        } else {
            $drivers = ClassementsModel::getDriversStandingsBySeason($seasonFilter);
            $teams = ClassementsModel::getTeamsStandingsBySeason($seasonFilter);
        }

        // Regrouper par catégorie pour les pilotes
        $listByCategory = [];
        foreach ($drivers as $driver) {
            $listByCategory[$driver->category][] = $driver;
        }

        // Regrouper par catégorie pour les équipes
        $teamsByCategory = [];
        foreach ($teams as $team) {
            $teamsByCategory[$team->category][] = $team;
        }

        $seasons = ClassementsModel::getAllSeasonsForSelect();

        $this->render('classements/drivers_standings', compact('listByCategory', 'teamsByCategory', 'seasons', 'seasonFilter'));
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
