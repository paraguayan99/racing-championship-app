<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\ActualTeamsModel;
use App\Models\Team;
use App\Models\Driver;

class ActualTeamsController extends Controller {
    public function index()
    {
        // On instancie la classe
        $actual_teams = new ActualTeamsModel();

        // On stocke dans une variable le return de la méthode all()
        $list = $actual_teams->all();

        $this->render('dashboard/actual_teams/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/actual_teams/create");
    }

    public function update(){
        $this->render("dashboard/actual_teams/update");
    }

    public function delete(){
        $this->render("dashboard/actual_teams/delete");
    }
}
?>