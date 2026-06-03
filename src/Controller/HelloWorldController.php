<?php

namespace App\Controller;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HelloWorldController extends AbstractController
{
    #[Route('/hello', name: 'hello_world', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(#[CurrentUser] User $user, LoggerInterface $logger): Response
    {
        $logger->warning('User has joined');
        return new Response('Well hi there '.$user->getEmail());
    }
}
