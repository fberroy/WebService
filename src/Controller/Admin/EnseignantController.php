<?php

namespace App\Controller\Admin;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/enseignant")
 */
class EnseignantController extends AbstractController
{
     /**
     * @Route("/", name="app_enseignant_index", methods={"GET"})
     */
    public function index(EnseignantRepository $enseignantRepository): Response
    {
	
    $col = "nom";
    if( isset($_GET [ 'champ' ]) ){
		switch ($_GET [ 'champ' ]) {
		    case "nom":
			$col = "nom";
			break;
		    case "nombre d'UC":
			$col = "nbUC";
			break;
		    case "UC attribué":
			$col = "nbUCattribue";
			break;
		    case "Statut":
			$col = "statutEnseignant";
			break;
                    case "Departement":
			$col = "nomDepartement";
		break;
	}
    }
	
	$recherche = "%";
	//$col = "nom";
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

        //var_dump($enseignantRepository->mesUC());

	$tab = $enseignantRepository->UC();
	$res = $enseignantRepository->id(); //tab à remplir 
	
	//dump($res);
	
	foreach ($res as $x){  // chaque ligne à remplir 
	$attrib = 0;
		foreach ($tab as $i){ // pour chaque ligne coursEnseignant
			if( $i['id'] == $x['clef']){  
				$coeff = 1;
				$id = $x['clef'];
				if($i['statutEnseignant'] == "ATER" && $i['typeCours'] == "TP" ) {
					$coeff = 0.75;
				}
				elseif ($i['typeCours'] == "CM"){
					$coeff = 1.5;
				}
		   		$attrib += ($i['nbGroupesAtt'] * $i['nbHeuresAtt']) * $coeff;
			}
		}
	$val = 0;
	for ($i = 1; $i <= sizeof($res)-1; $i++) {
		if ( $res[$i]['clef']== $x['clef'] ){
			//echo $i;
			 $res[$i]['UC'] = $attrib; 
		} 
		
	}
	
	//$res[$x['clef']]['UC'] = $attrib;
	//echo $attrib;
	}
	
	//dump($res);


        return $this->render('admin/enseignant/index.html.twig', [
            'enseignants' => $enseignantRepository->recherche($recherche, $col, $tri), 'attrib'=> $res,
        ]);
    }




     /**
     * @Route("/archives", name="app_enseignant_archive", methods={"GET"})
     */
    public function archive(EnseignantRepository $enseignantRepository): Response
    {
	
    $col = "nom";
    if( isset($_GET [ 'champ' ]) ){
		switch ($_GET [ 'champ' ]) {
		    case "nom":
			$col = "nom";
			break;
		    case "nombre d'UC":
			$col = "nbUC";
			break;
		    case "UC attribué":
			$col = "nbUCattribue";
			break;
		    case "Statut":
			$col = "statutEnseignant";
			break;
                    case "Departement":
			$col = "nomDepartement";
		break;
	}
    }
	
	$recherche = "%";
	//$col = "nom";
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

        return $this->render('admin/enseignant/archive.html.twig', [
            'enseignants' => $enseignantRepository->rechercheArchives($recherche, $col, $tri),
        ]);
    }

    /**
     * @Route("/", name="app_enseignant_index", methods={"GET"})
     */
    /* public function swapTri($tri){
		if($tri == 'ASC' ){ $tri = 'DESC';}
		else {$tri = 'ASC';}
	}*/
	

    /**
     * @Route("/new", name="app_enseignant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EnseignantRepository $enseignantRepository): Response
    {
        $enseignant = new Enseignant();
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enseignantRepository->add($enseignant);
            return $this->redirectToRoute('app_enseignant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/enseignant/new.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_enseignant_show", methods={"GET"})
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

        return $this->render('admin/enseignant/show.html.twig', [
            'enseignant' => $enseignant, 'mesUC' => $attrib,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_enseignant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Enseignant $enseignant, EnseignantRepository $enseignantRepository): Response
    {
        $form = $this->createForm(EnseignantType::class, $enseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $enseignantRepository->add($enseignant);
            return $this->redirectToRoute('app_enseignant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/enseignant/edit.html.twig', [
            'enseignant' => $enseignant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_enseignant_delete", methods={"POST"})
     */
    public function delete(Request $request, Enseignant $enseignant, EnseignantRepository $enseignantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enseignant->getId(), $request->request->get('_token'))) {
            $enseignantRepository->remove($enseignant);
        }

        return $this->redirectToRoute('app_enseignant_index', [], Response::HTTP_SEE_OTHER);
    }
}
