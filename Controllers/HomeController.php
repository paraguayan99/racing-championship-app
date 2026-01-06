<?php
namespace App\Controllers;

class HomeController extends Controller
{
    // Redirige sur la page Accueil
    public function index()
    {
        $this->render('home/index');
    }

}
?>