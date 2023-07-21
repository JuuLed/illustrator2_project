<?php

require_once __DIR__ . '/../utils/CookieSetter.php';

class RealCookieSetter implements CookieSetter {
    public function set($name, $value, $expires, $path, $domain, $secure, $httponly) {
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }
}