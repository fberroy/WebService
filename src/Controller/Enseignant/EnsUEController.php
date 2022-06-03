<?php

namespace App\Controller\Enseignant;

use App\Entity\UE;
use App\Form\UEType;
use App\Repository\UERepository;
use App\Repository\EnseignantRepository;
use App\Repository\AnneeRepository;
use App\Repository\CoursEnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnsUEController extends AbstractController
{
    

    /**
     * @Route("/ens/ue", name="app_ens_u_e", methods={"GET"})
     */
    public function index(UERepository $uERepository, CoursEnseignantRepository $coursEnseignantRepository ): Response
     {
	
	// La recherche
    $recherche = "%";

	// Le champ
    $col = "Intitule";
    if( isset($_GET [ 'champ' ]) ){
		switch ($_GET [ 'champ' ]) {
		    case "Intitule":
			$col = "Intitule";
			break;
		    case "formation":
			$col = "formation";
			break;
		    case "semestre":
			$col = "semestre";
			break;
		    case "statut":
			$col = "statut";
			break;
                    case "effectif":
			$col = "effectif";
		break;
	}
    }
	
	
	//$col = "nom";
	// Le Tri
	$tri = "ASC";
	
	if( isset($_GET [ 'tri' ]) && $_GET [ 'tri' ] == 'decroissant'){
		$tri = 'DESC';
	}
	else {
		$tri = 'ASC';
	}
	
	
	if(isset($_GET [ 'recherche' ])){
		$recherche = $_GET [ 'recherche' ];
	}

	return $this->render('enseignant/ens_ue/index.html.twig', [
            'u_es' => $uERepository->recherche($recherche, $col, $tri), 'voeux' => $coursEnseignantRepository->voeux(EnseignantRepository::id_session) , 'mesUE' => $uERepository->mesUE(),
        ]);


    }

/**
     * @Route("/ens/ue/{id}", name="app_ens_u_e_show", methods={"GET"})
     */
    public function show(UE $uE, AnneeRepository $anneeRepository): Response
    {
	$anneeVerif=$anneeRepository->actuelle();
        return $this->render('enseignant/ens_ue/show.html.twig', [
            'u_e' => $uE,
	    'annee'=>$anneeVerif[0]['annee']
        ]);
    }

}
