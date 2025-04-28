<?php

namespace App\Controllers;

use App\Core\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $this->view('faq-view');
    }

}