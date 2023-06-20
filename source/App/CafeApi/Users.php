<?php

namespace Source\App\CafeApi;

use Source\Models\CafeApp\AppInvoice;

/**
 * Users Class
 * @package Source\App\CafeApi
 */
class Users extends CafeApi
{
    /**
     * Users Constructor
     */
    public function __construct()
    {
        parent::__construct();    
    }

    /**
     * Index Method
     *
     * @return void
     */
    public function index(): void
    {
        $user = $this->user->data();
        $user->photo = CONF_URL_BASE . '/' . CONF_UPLOAD_DIR . "/{$user->photo}";
        unset($user->password, $user->forget);

        $response['user'] = $user;
        $response['user']->balance = (new AppInvoice())->balance($this->user);

        $this->back($response);
        return;
    }

    /**
     * Update Method
     *
     * @param array $data
     * @return void
     */
    public function update(array $data): void
    {
        
    }

    /**
     * Photo Method
     *
     * @return void
     */
    public function photo(): void
    {
        
    }
}