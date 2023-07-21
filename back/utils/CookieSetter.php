<?php

interface CookieSetter {
    public function set($name, $value, $expires, $path, $domain, $secure, $httponly);
}