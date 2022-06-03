<?php

namespace App\Controller\Enseignant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CoursRepository;
use App\Repository\EnseignantRepository;
use App\Repository\UERepository;
use App\Repository\AnneeRepository;

class EnsHomeController extends AbstractController
{
    /**
     * @Route("/ens", name="app_ens_home")
     */
    public function index(CoursRepository $coursRepository, EnseignantRepository $enseignantRepository,UERepository $ueRepository, AnneeRepository $anneeRepository): Response
    {
	$annee=$anneeRepository->actuelle();
	return $this->render('enseignant/index.html.twig', array('ues' => $ueRepository->mesUE(), 'voeux' => $coursRepository->mesVoeux((int)$annee[0]['annee']+1),'moi' => $enseignantRepository->moi(), 'mesUC' => $enseignantRepository->mesUC(),  'mesCours' => $coursRepository->mesCours(), ['controller_name' => 'EnsHomeController' ]) );
    }
}
