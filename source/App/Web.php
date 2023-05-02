<?php

namespace Source\App;

use stdClass;
use Source\Support\Pager;
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
        $head = $this->seo->render(CONF_SITE_NAME . " - " . CONF_SITE_TITLE, CONF_SITE_DESC, url(), theme("/assets/images/share.jpg"));

        echo $this->view->render("home", [
            'head'  => $head,
            'video' => 'IN5SyMJRmGY',
        ]);
    }

    /**
     * About Method
     *
     * @return void
     */
    public function about(): void
    {
        $head = $this->seo->render("Descubra o " . CONF_SITE_NAME . " - " . CONF_SITE_DESC, CONF_SITE_DESC, url("/sobre"), theme("/assets/images/share.jpg"));

        echo $this->view->render("about", [
            'head'  => $head,
            'video' => 'IN5SyMJRmGY',
        ]);
    }

    /**
     * Blog Method
     *
     * @param array|null $data
     * @return void
     */
    public function blog(?array $data): void
    {
        $head = $this->seo->render("Blog - " . CONF_SITE_NAME, "Confira em nosso blog dicas e casadas de como controlar melhor suas contas. Vamos tomar um café?", url("/blog"), theme("/assets/images/share.jpg"));

        $pager = new Pager(url('/blog/page/'));
        $pager->pager(100, 10, ($data['page'] ?? 1));

        echo $this->view->render("blog", [
            'head'      => $head,
            'paginator' => $pager->render(),
        ]);
    }

    /**
     * BlogPost method
     *
     * @param array $data
     * @return void
     */
    public function blogPost(array $data): void
    {
        $postName = $data['post_name'];

        $head = $this->seo->render("POST_NAME - " . CONF_SITE_NAME, "POST_HEADLINE", url("/blog/{$postName}"), theme("/assets/images/share.jpg"));

        echo $this->view->render("blog-post", [
            'head' => $head,
            'data' => $this->seo->data(),
        ]);

    }

    /**
     * Login Method
     *
     * @return void
     */
    public function login(): void
    {
        $head = $this->seo->render("Entrar - " . CONF_SITE_NAME, CONF_SITE_DESC, url("/entrar"), theme("/assets/images/share.jpg"));

        echo $this->view->render("auth-login", [
            'head'  => $head,
        ]);
    }

    /**
     * Forget Method
     *
     * @return void
     */
    public function forget(): void
    {
        $head = $this->seo->render("Recuperar Senha - " . CONF_SITE_NAME, CONF_SITE_DESC, url("/recuperar"), theme("/assets/images/share.jpg"));

        echo $this->view->render("auth-forget", [
            'head'  => $head,
        ]);
    }

    /**
     * Register Method
     *
     * @return void
     */
    public function register(): void
    {
        $head = $this->seo->render("Criar Conta - " . CONF_SITE_NAME, CONF_SITE_DESC, url("/cadastrar"), theme("/assets/images/share.jpg"));

        echo $this->view->render("auth-register", [
            'head'  => $head,
        ]);
    }

    /**
     * Confirm Method
     *
     * @return void
     */
    public function confirm(): void
    {
        $head = $this->seo->render("Confirme seu Cadastro - " . CONF_SITE_NAME, CONF_SITE_DESC, url("/confirma"), theme("/assets/images/share.jpg"));

        echo $this->view->render("optin-confirm", [
            'head'  => $head,
        ]);
    }

    /**
     * Success Method
     *
     * @return void
     */
    public function success(): void
    {
        $head = $this->seo->render("Bem Vindo ao " . CONF_SITE_NAME, CONF_SITE_DESC, url("/obrigado"), theme("/assets/images/share.jpg"));

        echo $this->view->render("optin-success", [
            'head'  => $head,
        ]);
    }
    
    /**
     * Terms Method
     *
     * @return void
     */
    public function terms(): void
    {
        $head = $this->seo->render(CONF_SITE_NAME . " - Termos de uso", CONF_SITE_DESC, url("/termos"), theme("/assets/images/share.jpg"));

        echo $this->view->render("terms", [
            'head'  => $head,
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

        $head = $this->seo->render("{$error->code} | {$error->title}", $error->message, url_back("/ops/{$error->code}"), theme("/assets/images/share.jpg"), false);

        echo $this->view->render("error", [
            'head'  => $head,
            'error' => $error,
        ]);
    }
}