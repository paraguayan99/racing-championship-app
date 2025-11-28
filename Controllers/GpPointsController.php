<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\GpPointsModel;

class GpPointsController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $gppoints = new GpPointsModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $gppoints->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/gp_points/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/gp_points/create");
    }

    public function update(){
        $this->render("dashboard/gp_points/update");
    }

    public function delete(){
        $this->render("dashboard/gp_points/delete");
    }
}
?>