<?php

namespace Source\App;

use Source\Core\Controller;

/**
 * Web Controller
 * @package Source\App
 */
class Web extends Controller
{
    /**
     * Web Constructor
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");    
    }

    /**
     * Home Method
     *
     * @return void
     */
    public function home(): void
    {
        echo $this->view->render("home", [
            "title" => "CafeControl - Gerencie suas contas com o melhor café"
        ]);
    }
    

    /**
     * Error Method
     *
     * @param array $data
     * @return void
     */
    public function error(array $data): void
    {
        echo $this->view->render("error", [
            'title' => "{$data['errcode']} | Ooops!"
        ]);
    }
}