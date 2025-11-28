<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\TeamsModel;

class TeamsController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $teams = new TeamsModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $teams->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/teams/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/teams/create");
    }

    public function update(){
        $this->render("dashboard/teams/update");
    }

    public function delete(){
        $this->render("dashboard/teams/delete");
    }
}
?>