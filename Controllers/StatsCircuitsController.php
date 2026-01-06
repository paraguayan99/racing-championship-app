<?php
namespace App\Controllers;

use App\Models\StatsCircuitsModel;

class StatsCircuitsController extends Controller
{
    // Affiche les stats du circuit sélectionné
    public function index(...$args)
    {
        // Récupère le circuit id
        $circuitId = $args['circuit_id'] ?? $_GET['circuit_id'] ?? null;

        // Prépare les infos
        $circuits = StatsCircuitsModel::getAllCircuitsForSelect();
        // selectedCircuit défini pour ne pas avoir d'erreur
        $selectedCircuit = null;

        if ($circuitId) {
            $topChronos = StatsCircuitsModel::getCircuitTopChronos($circuitId);
            $driversStats = StatsCircuitsModel::getDriversStatsByCircuit($circuitId);
            $gpCountByCategory = StatsCircuitsModel::getGPCountByCategory($circuitId);

            // Calcul du total des GP
            $totalGP = 0;
            foreach ($gpCountByCategory as $row) {
                $totalGP += $row->gp_count;
            }

            // Récupération des infos du circuit
            foreach ($circuits as $c) {
                if ($c->id == $circuitId) {
                    $selectedCircuit = $c;
                    break;
                }
            }
        } else {
            $topChronos = [];
            $driversStats = [];
            $gpCountByCategory = [];
            $totalGP = 0;
        }

        // Envoie toutes les données à la vue
        $this->render(
            'classements/statscircuits',
            compact(
                'circuits',
                'circuitId',
                'selectedCircuit',
                'topChronos',
                'driversStats',
                'gpCountByCategory',
                'totalGP'
            )
        );
    }
}
?>

