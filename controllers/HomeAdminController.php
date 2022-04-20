<?php
require_once 'controllers/Controller.php';

class HomeAdminController extends Controller
{
    public function index()
    {
        $this->title = "Dashboard";
        $this->content = $this->render('views/homes/dashboard.php', [
            'title' => $this->title
        ]);
        require_once 'views/adminLayouts/main.php';
    }
}