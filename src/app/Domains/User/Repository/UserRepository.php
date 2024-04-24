<?php

namespace App\Domains\User\Repository;

use App\Database\AbstractRepository;
use App\Domains\User\User;

class UserRepository extends AbstractRepository
{

    protected function connectionName(): string
    {
        return 'mysql';
    }
    protected function fillable(): array
    {
        return ['name', 'email'];
    }

    protected function model(): string
    {
        return User::class;
    }

    protected function getTableName(): string
    {
        return 'users';
    }

    protected function getPrimaryKey(): string
    {
        return 'id';
    }
}
