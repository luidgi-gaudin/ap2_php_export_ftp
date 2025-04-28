<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if(isset($_SESSION['userId'])) {
            // Redirection vers la page dashboard
            $this->redirect('/dashboard');
        }else {
            // Redirection vers la page login
            $this->redirect('/user/login');
        }
    }
}