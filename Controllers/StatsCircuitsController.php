<?php
namespace App\Controllers;

use App\Models\StatsCircuitsModel;

class StatsCircuitsController extends Controller
{
    // Affiche les stats du Circuit sélectionné
    public function index($circuit_id = null)
    {
        $circuitId = $circuit_id ?? $_GET['circuit_id'] ?? null;

        $circuits = StatsCircuitsModel::getAllCircuitsForSelect();
        $selectedCircuit = null;

        $topChronos = [];
        $driversStats = [];
        $gpCountByCategory = [];
        $totalGP = 0;

        if ($circuitId) {
            $topChronos = StatsCircuitsModel::getCircuitTopChronos($circuitId);

            // Si une abréviation existe, on l'utilise, sinon on garde le nom complet du Jeu vidéo
            foreach ($topChronos as $chrono) {
                if (!empty($chrono->videogame_short)) {
                    $chrono->videogame = $chrono->videogame_short;
                }
            }
            $driversStats = StatsCircuitsModel::getDriversStatsByCircuit($circuitId);
            $gpCountByCategory = StatsCircuitsModel::getGPCountByCategory($circuitId);

            foreach ($gpCountByCategory as $row) {
                $totalGP += $row->gp_count;
            }

            foreach ($circuits as $c) {
                if ($c->id == $circuitId) {
                    $selectedCircuit = $c;
                    break;
                }
            }
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
?>

