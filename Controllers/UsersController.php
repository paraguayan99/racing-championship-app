<?php
namespace App\Controllers;

use App\Core\Form;
use App\Models\UsersModel;

class UsersController extends Controller {
    public function index()
    {
        // On instancie la classe
        $users = new UsersModel();

        // On stocke dans une variable le return de la méthode all()
        $list = $users->all();

        $this->render('dashboard/users/index', ['list' => $list]);
    }

    public function create(){
        $this->render("dashboard/users/create");
    }

    public function update(){
        $this->render("dashboard/users/update");
    }

    public function delete(){
        $this->render("dashboard/users/delete");
    }
}
?>