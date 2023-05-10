<?php

namespace Source\Models;

use Source\Core\Model;
use Source\Core\Session;
use Source\Core\View;
use Source\Support\Email;

/**
 * Class Auth
 * @package Source\Models
 */
class Auth extends Model
{
    /**
     * Auth Constructor
     */
    public function __construct()
    {
        parent::__construct('user', ['id'], ['email', 'password']);    
    }

    /**
     * Get User Logged Method
     *
     * @return null|User
     */
    public static function user(): ?User
    {
        $session = new Session();

        if (!$session->has('authUser')) {
            return null;
        }
        
        return (new User())->findById($session->authUser);
    }

    /**
     * Logout Method
     *
     * @return void
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset('authUser');
    }

    /**
     * Register Method
     *
     * @param User $user
     * @return bool
     */
    public function register(User $user): bool
    {
        if (!$user->save()) {
            $this->message = $user->message;
            return false;
        }

        $view = new View(__DIR__ . '/../../shared/views/email');
        $message = $view->render('confirm', [
            'first_name'   => $user->first_name,
            'confirm_link' => url("/obrigado/" . base64_encode($user->email)),
        ]);

        (new Email())->bootstrap("Ative sua conta no " . CONF_SITE_NAME, $message, $user->email, "{$user->first_name} {$user->last_name}")->send();

        return true;
    }

    /**
     * Login Method
     *
     * @param string $email
     * @param string $password
     * @param bool $save
     * @return bool
     */
    public function login(string $email, string $password, bool $save = false): bool
    {
        if (!is_email($email)) {
            $this->message->warning('o email informado não é válido');
            return false;
        }

        if (!$save) {
            setcookie('authEmail', null, time() - 3600, '/');
        }

        setcookie('authEmail', $email, time() + 604800, '/');

        if (!is_passwd($password)) {
            $this->message->warning('a senha informada não é válida');
            return false;
        }

        $user = (new User())->findByEmail($email);
        if (!$user) {
            $this->message->error('o email informado não está cadastrado');
            return false;
        }

        if (!passwd_verify($password, $user->password)) {
            $this->message->error('senha incorreta');
            return false;
        }

        if (passwd_rehash($user->password)) {
            $user->password = $password;
            $user->save();
        }

        (new Session())->set('authUser', $user->id);
        $this->message->success('Login efetuado com sucesso!')->flash();
        return true;
    }

    /**
     * Forget Method
     *
     * @param string $email
     * @return boll
     */
    public function forget(string $email): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        $user->save();

        $view = new View(__DIR__ . '/../../shared/views/email');
        $message = $view->render("forget", [
            'first_name'  => $user->first_name,
            'forget_link' => url("/recuperar/{$user->email}|{$user->forget}"),
        ]);

        (new Email())->bootstrap(
            "Recupere sua senha no " . CONF_SITE_NAME, 
            $message, 
            $user->email, 
            "{$user->first_name} {$user->last_name}"
        )->send();

        return true;
    }

    /**
     * Resets password Method
     *
     * @param string $email
     * @param string $code
     * @param string $password
     * @param string $passwordRe
     * @return bool
     */
    public function reset(string $email, string $code, string $password, string $passwordRe): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->message->warning('A conta para recuperação nao foi encontrada');
            return false;
        }

        if ($user->forget != $code) {
            $this->message->error('Desculpe, mas o código de verificação não é válido');
            return false;
        }

        if (!is_passwd($password)) {
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;

            $this->message->info("A senha deve ter no mínimo {$min} e no máximo {$max} caracteres");
            return false;
        }

        if ($password != $passwordRe) {
            $this->message->warning('as senhas digitadas não conferem');
            return false;
        }

        $user->password = $password;
        $user->forget = null;
        $user->save();

        return true;
    }
}