<?php
namespace App\Controllers;

use App\Models\StatsDriversModel;

class StatsDriversController extends Controller
{
    public function index($driver_id = null)
    {
        $historyByCategory = [];
        $ranksBySeason     = [];
        $driver            = null;

        if ($driver_id && is_numeric($driver_id) && (int)$driver_id > 0) {
            $driver_id = (int) $driver_id;

            $driver = StatsDriversModel::getDriverById($driver_id);

            if ($driver) {
                $history = StatsDriversModel::getDriverHistory($driver_id);

                foreach ($history as $row) {
                    $historyByCategory[$row->category][] = $row;
                }

                $seasonIds     = array_unique(array_column($history, 'season_id'));
                $ranksBySeason = StatsDriversModel::getDriverRanksBySeason($driver_id, $seasonIds);
            }
        }

        $this->render(
            'classements/statsdrivers',
            compact('driver', 'historyByCategory', 'ranksBySeason')
        );
    }
}