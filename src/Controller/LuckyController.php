<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LuckyController extends AbstractController
{
    #[Route('/luck')]
    public function number(LoggerInterface $pgLogger): Response
    {
        $number = random_int(0, 100);

        $pgLogger->info($number);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}