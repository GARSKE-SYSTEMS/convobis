<?php

namespace Convobis\Util;

class AuthHelper {

    /**
     * Checks if the user is authenticated.
     *
     * @return bool True if authenticated, false otherwise.
     */
    public static function isAuthenticated(): bool {
        // Check if the user session is set
        return isset($_SESSION['user']);
    }

    /**
     * Logs in the user by setting the session variable.
     *
     * @param string $username The username of the user.
     * @return void
     */
    public static function login(string $username): void {
        $_SESSION['user'] = $username;
    }

    /**
     * Logs out the user by unsetting the session variable.
     *
     * @return void
     */
    public static function logout(): void {
        unset($_SESSION['user']);
    }

    /**
     * Hash a plaintext password using bcrypt.
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify a plaintext password against a hash.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Ensure the user is authenticated or redirect to login.
     *
     * @return void
     */
    public static function requireAuth(): void {
        if (!self::isAuthenticated()) {
            header('Location: login');
            exit;
        }
    }
}