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

    // ----- PILOTES -----
    public function driversStandings(...$args)
    {
        // Récupère season_id depuis $args ou $_GET
        $seasonFilter = $args['season_id'] ?? $_GET['season_id'] ?? 'active';

        if ($seasonFilter === 'active') {
            $list = ClassementsModel::getDriversStandingsActive();
        } else {
            $list = ClassementsModel::getDriversStandingsBySeason($seasonFilter);
        }

        // Pour le select détaillé
        $seasons = ClassementsModel::getAllSeasonsForSelect();

        $this->render('classements/drivers_standings', compact('list', 'seasons', 'seasonFilter'));
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
