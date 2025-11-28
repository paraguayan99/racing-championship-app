<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\CircuitsModel;

class CircuitsController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $circuits = new CircuitsModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $circuits->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/circuits/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/circuits/create");
    }

    public function update(){
        $this->render("dashboard/circuits/update");
    }

    public function delete(){
        $this->render("dashboard/circuits/delete");
    }
}
?>