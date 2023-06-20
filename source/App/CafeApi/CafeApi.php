<?php

namespace Source\App\CafeApi;

use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\CafeApp\AppInvoice;
use Source\Models\CafeApp\AppWallet;

/**
 * CafeApi Controller Class
 * @package Source\App\CafeApi
 */
class CafeApi extends Controller
{
    /** @var */
    protected $user;

    /** @var */
    protected $headers;

    /** @var */
    protected $response;

    /**
     * CafeApi constructor
     */
    public function __construct()
    {
        parent::__construct('/');
        header('Content-Type: application/json; charset=UTF-8');
        $this->headers = getallheaders();

        $auth = $this->auth();
        if (!$auth) {
            exit();
        }

        (new AppWallet())->start($this->user);
        (new AppInvoice())->findById($this->user, 3);
    }

    /**
     * Method Call
     *
     * @param integer $code
     * @param string|null $type
     * @param string|null $message
     * @param string $rule
     * @return CafeApi
     */
    protected function call(int $code, string $type = null, string $message = null, string $rule = 'errors'): CafeApi
    {
        http_response_code($code);

        if (!empty($type)) {
            $this->response = [
                $rule => [
                    "type"    => $type,
                    "message" => (!empty($message) ? $message : null),
                ]
            ];
        }

        return $this;
    }

    /**
     * Back Method
     *
     * @param array|null $response
     * @return CafeApi
     */
    protected function back(array $response = null): CafeApi
    {
        if (!empty($response)) {
            $this->response = (!empty($this->response) ? array_merge($this->response, $response) : $response);
        }

        echo json_encode($this->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $this;
    }

    /**
     * Auth Method
     *
     * @return boolean
     */
    private function auth(): bool
    {
        if (empty($this->headers['email']) || empty($this->headers['password'])) {
            $this->call(400, 'auth_empty', 'Favor, informe seu e-mail e senha')->back();
            return false;
        }

        $auth = new Auth();
        $user = $auth->attempt($this->headers['email'], $this->headers['password'], 1);
        if (!$user) {
            $this->call(401, 'invalid_auth', $auth->message()->getText())->back();
            return false;
        }

        $this->user = $user;
        return true;
    }

    protected function requestLimit(string $endpoint, int $limit, int $seconds, $attempt = false): bool
    {
        $userToken = (!empty($this->headers['email']) ? base64_encode($this->headers['email']) : null);

        if (!$userToken) {
            $this->call(400, 'invalid_data', 'você precisa informar seu e-mail')->back();

            return false;
        }

        $cacheDir = __DIR__ . '/../../../' . CONF_UPLOAD_DIR . '/requests';
        if (!file_exists($cacheDir) || !is_dir($cacheDir)) {
            mkdir($cacheDir, 0755);
        }

        $cacheFile = "{$cacheDir}/{$userToken}.json";

        if (!file_exists($cacheFile) || !is_file($cacheFile)) {
            fopen($cacheFile, 'w');
        }

        $userCache = json_decode(file_get_contents($cacheFile));
        $cache = (array)$userCache;

        $save = function ($cacheFile, $cache) {
            $saveCache = fopen($cacheFile, 'w');
            fwrite($saveCache, json_decode($cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fclose($saveCache);
        };

        if (empty($cache[$endpoint]) || $cache[$endpoint]->time <= time()) {
            if (!$attempt) {
                $cache[$endpoint] = [
                    'limit'    => $limit,
                    'requests' => 1,
                    'time'     => time() + $seconds,
                ];

                $save($cacheFile, $cache);
            }

            return true;
        }

        if ($cache[$endpoint]->requests >= $limit) {
            $this->call(400, 'request_limit', 'Você exedeu o limite de requisições para essa ação')->back();

            return false;
        }

        if (!$attempt) {
            $cache[$endpoint] = [
                'limit'    => $limit,
                'requests' => $cache[$endpoint]->requests + 1,
                'time'     => $cache[$endpoint]->time
            ];
        }
    }
}