<?php

namespace App\Domains\User;

use JsonSerializable;

class User implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $email;



    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
