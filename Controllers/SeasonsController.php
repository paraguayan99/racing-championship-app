<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\SeasonsModel;

class SeasonsController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $seasons = new SeasonsModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $seasons->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/seasons/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/seasons/create");
    }

    public function update(){
        $this->render("dashboard/seasons/update");
    }

    public function delete(){
        $this->render("dashboard/seasons/delete");
    }
}
?>