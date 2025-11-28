<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\TeamsDriversModel;

class TeamsDriversController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $teamsdrivers = new TeamsDriversModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $teamsdrivers->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/teams_drivers/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/teams_drivers/create");
    }

    public function update(){
        $this->render("dashboard/teams_drivers/update");
    }

    public function delete(){
        $this->render("dashboard/teams_drivers/delete");
    }
}
?>