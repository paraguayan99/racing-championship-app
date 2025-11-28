<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\DriversModel;

class DriversController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $drivers = new DriversModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $drivers->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/drivers/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/drivers/create");
    }

    public function update(){
        $this->render("dashboard/drivers/update");
    }

    public function delete(){
        $this->render("dashboard/drivers/delete");
    }
}
?>