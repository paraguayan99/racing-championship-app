<?php
namespace App\Controllers;

class MentionsController extends Controller
{

    // Redirige sur la page Mentions légales
    public function index()
    {
        $this->render('mentions/index');
    }

}
?>