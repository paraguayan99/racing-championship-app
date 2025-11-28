<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\GpModel;

class GpController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $gp = new GpModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $gp->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/gp/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/gp/create");
    }

    public function update(){
        $this->render("dashboard/gp/update");
    }

    public function delete(){
        $this->render("dashboard/gp/delete");
    }
}
?>