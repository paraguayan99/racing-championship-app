<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\PenaltiesModel;

class PenaltiesController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $penalties = new PenaltiesModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $penalties->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/penalties/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/penalties/create");
    }

    public function update(){
        $this->render("dashboard/penalties/update");
    }

    public function delete(){
        $this->render("dashboard/penalties/delete");
    }
}
?>