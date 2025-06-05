<?php

namespace Convobis\Service;

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../util/AuthHelper.php';
require_once __DIR__ . '/../util/MailHelper.php';

use Convobis\Repository\UserRepository;
use Convobis\Util\AuthHelper;
use Convobis\Util\MailHelper;
use Convobis\Model\User;

class AuthService
{
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepo->findByEmail($email);
        if ($user && AuthHelper::verifyPassword($password, $user->passwordHash)) {
            AuthHelper::login($user->email);
            return true;
        }
        return false;
    }

    public function register(string $email, string $password, ?string $name = null): User
    {
        $hash = AuthHelper::hashPassword($password);
        $user = new User(0, $email, $hash, $name);
        return $this->userRepo->save($user);
    }

    public function sendResetLink(string $email): bool
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) return false;

        $token = bin2hex(random_bytes(16));
        // Store token temporarily, e.g. in $_SESSION
        $_SESSION['pw_reset'][$token] = $user->email;

        $link = sprintf('%s?route=reset_password&token=%s', $_SERVER['REQUEST_URI'], $token);
        $body = "Klicken Sie auf folgenden Link, um Ihr Passwort zurückzusetzen:\n" . $link;
        return MailHelper::send($user->email, 'Passwort zurücksetzen', $body);
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        if (!isset($_SESSION['pw_reset'][$token])) {
            return false;
        }
        $email = $_SESSION['pw_reset'][$token];
        unset($_SESSION['pw_reset'][$token]);

        $user = $this->userRepo->findByEmail($email);
        if (!$user) return false;

        $user->passwordHash = AuthHelper::hashPassword($newPassword);
        $this->userRepo->save($user);
        return true;
    }
}
