<?php

namespace Source\Core;

use Source\Core\View;
use Source\Support\Seo;
use Source\Support\Message;

/**
 * Class Controller
 *
 * @author Guilherme L. Armindo <guilopesdev@hotmail.com>
 * @package Source\Core
 */
class Controller
{
    /** @var View */
    protected $view;

    /** @var Seo */
    protected $seo;

    /** @var Message */
    protected $message;

    /**
     * Controller constructor
     *
     * @param string|null $pathToViews
     */
    public function __construct(string $pathToViews = null)
    {
        $this->view = new View($pathToViews);
        $this->seo  = new Seo();
        $this->message = new Message();
    }
}