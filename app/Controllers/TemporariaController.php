<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class TemporariaController extends Controller
{
    public function navbarADM()
    {
        $this->render('components/navbar.admin', ['title' => '']);
        //require_once __DIR__ . '/../views/components/navbar.admin.phtml';
    }

    public function sidenavADM()
    {
        $this->render('components/sidenav.admin', ['title' => '']);
    }

    public function navbarUser()
    {
        $this->render('components/navbar.user', ['title' => '']);
    }

    public function sidebarUser()
    {
        $this->render('components/sidenav.user', ['title' => '']);
    }

    public function dashboardADM()
    {
        $this->render('buildings/dashboard.admin', ['title' => '']);
    }

    public function newBuilding()
    {
        $this->render('buildings/new', ['title' => 'Cadastrar Prédio']);
    }

    public function newFloor()
    {
        $this->render('buildings/new.floor', ['title' => 'Cadastrar Andar']);
    }

    public function newitens()
    {
        $this->render('/buildings/new.itens', ['title' => 'Cadastrar Itens']);
    }

    public function edit()
    {
        $this->render('/buildings/edit', ['title' => 'Editar Cadastro']);
    }

    





















    //    public function sidenavADM()
    // {
    //    $is_admin = Auth::user()->hasRule('admin');
    // Inclui a view do componente
    //  $title = '';
    //$this->render('components/sidenav.adm', compact('title', 'is_admin'));
    //require_once __DIR__ . '/../views/components/navbar.admin.phtml';
    //}
}
