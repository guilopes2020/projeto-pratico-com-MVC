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
     * App Constructor
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP);

        if (!Auth::user()) {
            $this->message->warning('efetue login para acessar o APP')->flash();
            redirect('/entrar');
        }
    }

    public function home()
    {
        echo flash();
        var_dump(Auth::user());
        echo "<a title='Sair' href='" . url('/app/sair') . "'>Sair</a>";
    }

    public function logout()
    {
        (new Message())->info(Auth::user()->first_name . ", voce foi deslogado com sucesso!")->flash();
        
        Auth::logout();
        redirect('/entrar');
    }
}