<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user/check-auth", name="user", methods={"POST"})
     */
    public function checkAuth(Request $request, UserRepository $userRepository)
    {


        return $this->json([]);
    }
}
