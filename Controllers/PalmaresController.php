<?php
namespace App\Controllers;

use App\Models\PalmaresModel;

class PalmaresController extends Controller
{
    // Affiche la page Palmarès de la catégorie sélectionnée
    public function index($category_name = null)
    {
        $categoryFilter = $category_name ?? $_GET['category_name_id'] ?? $_GET['category_name'] ?? null;

        $categories = PalmaresModel::getAllCategoriesForSelect();
        $selectedCategory = null;
        $driversByCategory = [];
        $teamsByCategory = [];

        if ($categoryFilter) {
            $drivers = PalmaresModel::getDriversStats($categoryFilter);
            $teams = PalmaresModel::getTeamsStats($categoryFilter);

            foreach ($categories as $c) {
                if ($c->name === $categoryFilter) {
                    $selectedCategory = $c;
                    break;
                }
            }

            foreach ($drivers as $d) {
                $driversByCategory[$d->category][] = $d;
            }
            foreach ($teams as $t) {
                $teamsByCategory[$t->category][] = $t;
            }
        }

        $this->render(
            'classements/palmares',
            compact(
                'categories',
                'categoryFilter',
                'selectedCategory',
                'driversByCategory',
                'teamsByCategory'
            )
        );
    }
}
?>
