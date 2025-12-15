<?php
namespace App\Controllers;

use App\Models\PalmaresModel;

class PalmaresController extends Controller
{
    public function index()
    {
        $drivers = PalmaresModel::getDriversStats();
        $teams = PalmaresModel::getTeamsStats();

        // Groupement par catégorie
        $driversByCategory = [];
        foreach ($drivers as $d) {
            $driversByCategory[$d->category][] = $d;
        }

        $teamsByCategory = [];
        foreach ($teams as $t) {
            $teamsByCategory[$t->category][] = $t;
        }

        $this->render(
            'classements/palmares',
            compact('driversByCategory', 'teamsByCategory')
        );
    }
}
