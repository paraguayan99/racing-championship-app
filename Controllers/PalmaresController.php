<?php
namespace App\Controllers;

use App\Models\PalmaresModel;

class PalmaresController extends Controller
{
    // Affiche la page Palmarès de toutes les catégories
    // public function index()
    // {
    //     $drivers = PalmaresModel::getDriversStats();
    //     $teams = PalmaresModel::getTeamsStats();

    //     // Groupement par catégorie
    //     $driversByCategory = [];
    //     foreach ($drivers as $d) {
    //         $driversByCategory[$d->category][] = $d;
    //     }

    //     $teamsByCategory = [];
    //     foreach ($teams as $t) {
    //         $teamsByCategory[$t->category][] = $t;
    //     }

    //     $this->render(
    //         'classements/palmares',
    //         compact('driversByCategory', 'teamsByCategory')
    //     );
    // }





    // public function index($category_name = null)
    // {
    //     $categoryFilter = $category_name ?? $_GET['category_name'] ?? null;

    //     $categories = PalmaresModel::getAllCategoriesForSelect();
    //     $selectedCategory = null;

    //     $driversByCategory = [];
    //     $teamsByCategory = [];

    //     if ($categoryFilter) {
    //         // Filtrage par catégorie sélectionnée
    //         $drivers = PalmaresModel::getDriversStats($categoryFilter);
    //         $teams = PalmaresModel::getTeamsStats($categoryFilter);

    //         foreach ($categories as $c) {
    //             if ($c->name === $categoryFilter) {
    //                 $selectedCategory = $c;
    //                 break;
    //             }
    //         }
    //     } else {
    //         // Toutes les catégories
    //         $drivers = PalmaresModel::getDriversStats();
    //         $teams = PalmaresModel::getTeamsStats();
    //     }

    //     // Groupement par catégorie
    //     foreach ($drivers as $d) {
    //         $driversByCategory[$d->category][] = $d;
    //     }
    //     foreach ($teams as $t) {
    //         $teamsByCategory[$t->category][] = $t;
    //     }

    //     $this->render(
    //         'classements/palmares',
    //         compact(
    //             'categories',
    //             'categoryFilter',
    //             'selectedCategory',
    //             'driversByCategory',
    //             'teamsByCategory'
    //         )
    //     );
    // }



    public function index($category_name = null)
    {
        $categoryFilter = $category_name ?? $_GET['category_name'] ?? null;

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
