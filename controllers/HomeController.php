<?php
require_once 'controllers/Controller.php';

class HomeController extends Controller
{
    public function index()
    {
        $this->title = "Trang chủ";
        $this->content = $this->render('views/homes/index.php', [
            'title' => $this->title
        ]);
        require_once 'views/userLayouts/main.php';
    }

    public function page404()
    {
        require_once 'views/homes/404.php';
    }
}