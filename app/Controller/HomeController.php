<?php
namespace App\Controller;

class HomeController
{
    public function __invoke()
    {
        return view('home');
    }
}
