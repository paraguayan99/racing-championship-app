<?php
namespace App\Controllers;

use App\Models\StatsCircuitsModel;

class StatsCircuitsController extends Controller
{
    public function index(...$args)
    {
        // EXACTEMENT comme standings
        $circuitId = $args['circuit_id'] ?? $_GET['circuit_id'] ?? null;

        // Liste déroulante
        $circuits = StatsCircuitsModel::getAllCircuitsForSelect();
        $selectedCircuit = null; // toujours défini pour ne pas avoir d'erreur

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

