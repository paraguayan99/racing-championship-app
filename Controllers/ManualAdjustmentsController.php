<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\ManualAdjustmentsModel;

class ManualAdjustmentsController extends Controller {
    public function index()
    {
        // // On instancie la classe
        // $manualadjustments = new ManualAdjustmentsModel();

        // // On stocke dans une variable le return de la méthode all()
        // $list = $manualadjustments->all();
        $list = "Model à créer pour instancier Classe et Méthode";
        $this->render('dashboard/manual_adjustments/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/manual_adjustments/create");
    }

    public function update(){
        $this->render("dashboard/manual_adjustments/update");
    }

    public function delete(){
        $this->render("dashboard/manual_adjustments/delete");
    }
}
?>