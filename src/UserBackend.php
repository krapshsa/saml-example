<?php

namespace App;

class UserBackend
{
    private array $users = [
        'admin'   => 'password',
        'user001' => 'password'
    ];

    public function userExists(string $username): bool
    {
        return array_key_exists($username, $this->users);
    }

    public function checkPassword(string $username, string $password): bool
    {
        if (!$this->userExists($username)) {
            return false;
        }

        return $this->users[$username] === $password;
    }
}