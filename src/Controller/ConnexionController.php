<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/", name="app_connexion")
     */
    public function index(): Response
    {
        return $this->render('connexion.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
