<?php

declare(strict_types=1);

namespace src\Service;

class SessionService
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function clear(): void
    {
        $_SESSION = [];
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        session_destroy();
    }

    public function flash(string $key, mixed $value = null): mixed
    {
        $flashKey = "flash_{$key}";
        
        if ($value !== null) {
            $this->set($flashKey, $value);
            return null;
        }
        
        $value = $this->get($flashKey);
        $this->remove($flashKey);
        
        return $value;
    }
}
