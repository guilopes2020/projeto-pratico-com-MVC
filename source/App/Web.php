<?php

namespace Source\App;

use Source\Core\Controller;
use stdClass;

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
        $head = $this->seo->render(CONF_SITE_NAME . " - " . CONF_SITE_TITLE, CONF_SITE_DESC, url(), url("/assets/images/share.jpg"));

        echo $this->view->render("home", [
            'head'  => $head,
            'video' => 'IN5SyMJRmGY',
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
        $error = new stdClass();
        $error->code = $data['errcode'];
        $error->title = "Ooops. conteúdo indisponivel.";
        $error->message = "Sentimos muito, mas o conteúdo que vc esta tentando acessar nao existe, esta indisponivel no momento ou foi removido.";
        $error->linkTitle = "continue navegando";
        $error->link = url_back();

        $head = $this->seo->render("{$error->code} | {$error->title}", $error->message, url_back("/ops/{$error->code}"), url("/assets/images/share.jpg"), false);

        echo $this->view->render("error", [
            'head'  => $head,
            'error' => $error,
        ]);
    }
}