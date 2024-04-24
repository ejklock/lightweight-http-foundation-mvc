<?php

namespace App\Domains\User\Controllers;

use App\Domains\User\Repository\UserRepository;
use App\Domains\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    public function index(Request $request, Response $response)
    {
        return $this->userRepository->findAll();
    }

    public function create(Request $request, Response $response)
    {
        $requestData = $request->toArray();

        $data = [
            'name' => $requestData['name'],
            'email' => $requestData['email'],
        ];

        return  $this->userRepository->insert($data);
    }
}
