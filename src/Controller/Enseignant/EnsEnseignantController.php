<?php

namespace App\Controller\Enseignant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route("/ens/enseignant")
     */
class EnsEnseignantController extends AbstractController
{
    /**
     * @Route("/", name="app_ens_enseignant")
     */
    public function index(): Response
    {
        return $this->render('enseignant/ens_enseignant/index.html.twig', [
            'controller_name' => 'EnsEnseignantController',
        ]);
    }


     /**
     * @Route("/{id}", name="app_ens_enseignant_show", methods={"GET"})
     */
    public function show(Enseignant $enseignant, EnseignantRepository $enseignantRepository): Response
    {

	$tab = $enseignantRepository->UC();
	
	$attrib = 0;
		foreach ($tab as $i){ // pour chaque ligne coursEnseignant
			if( $i['id'] == $enseignant->getId()){  
				$coeff = 1;
				if($i['statutEnseignant'] == "ATER" && $i['typeCours'] == "TP" ) {
					$coeff = 0.75;
				}
				elseif ($i['typeCours'] == "CM"){
					$coeff = 1.5;
				}
		   		$attrib += ($i['nbGroupesAtt'] * $i['nbHeuresAtt']) * $coeff;
			}
		} 

        return $this->render('enseignant/ens_enseignant/show.html.twig', [
            'enseignant' => $enseignant, 'mesUC' => $attrib,
        ]);
    }
}
