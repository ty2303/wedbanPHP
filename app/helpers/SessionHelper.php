<?php
class SessionHelper
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function delete($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    public static function destroy()
    {
        self::start();
        session_destroy();
        $_SESSION = [];
    }

    // Authentication helpers
    public static function isLoggedIn()
    {
        return self::get('user_id') !== null;
    }

    public static function setUser($user)
    {
        self::set('user_id', $user['id']);
        self::set('username', $user['username']);
        self::set('user_data', $user);
    }

    public static function getUser()
    {
        return self::get('user_data');
    }

    public static function getUserId()
    {
        return self::get('user_id');
    }

    public static function logout()
    {
        self::delete('user_id');
        self::delete('username');
        self::delete('user_data');
    }

    // Flash messages
    public static function setFlash($key, $message)
    {
        self::set('flash_' . $key, $message);
    }

    public static function getFlash($key)
    {
        $message = self::get('flash_' . $key);
        self::delete('flash_' . $key);
        return $message;
    }

    public static function hasFlash($key)
    {
        return self::get('flash_' . $key) !== null;
    }
}
?>