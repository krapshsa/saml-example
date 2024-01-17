<?php

namespace App;

class UserBackend
{
    private array $users = [
        'admin'   => 'password',
        'user001' => 'password'
    ];

    public function checkPassword(string $username, string $password): bool
    {
        if (!array_key_exists($username, $this->users)) {
            return false;
        }

        return $this->users[$username] === $password;
    }
}