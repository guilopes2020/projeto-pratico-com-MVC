<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Support\Message;

/**
 * Class App
 * @package Source\App
 */
class App extends Controller
{
    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP);

        if (!Auth::user()) {
            $this->message->warning("Efetue login para acessar o APP.")->flash();
            redirect("/entrar");
        }
    }

    /**
     * App Home
     *
     * @return void
     */
    public function home(): void
    {
        echo flash();
        var_dump(Auth::user());
        echo "<a title='Sair' href='" . url("/app/sair") . "'>Sair</a>";
    }

    /**
     * App Logout
     *
     * @return void
     */
    public function logout(): void
    {
        (new Message())->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();

        Auth::logout();
        redirect("/entrar");
    }
}