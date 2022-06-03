<?php

namespace App\Controller\Admin;

use App\Entity\CoursEnseignant;
use App\Form\CoursEnseignantType;
use App\Repository\CoursEnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/coursenseignant")
 */
class CoursEnseignantController extends AbstractController
{

    /**
     * @Route("/", name="app_cours_enseignant_index", methods={"GET"})
     */
    public function index(CoursEnseignantRepository $coursEnseignantRepository): Response
    {
	
	// La recherche
    $recherche = "%";

	// Le champ
    $col = "Enseignant";
    $var = "x";
    if( isset($_GET [ 'champ' ]) ){
		switch ($_GET [ 'champ' ]) {
		    case "Enseignant":
			$col = "nom";
			$var = "e";
			break;
		    case "Cours":
			$col = "nomCours";
			$var = "c";
			break;
		    case "Type de cours":
			$col = "typeCours";
			$var = "c";
			break;
		    case "Nombre de groupe":
			$col = "nbGroupes";
			$var = "c";
		
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
	return $this->render('admin/cours_enseignant/index.html.twig', [
            'cours_enseignants' => $coursEnseignantRepository->recherche($recherche, $col, $tri, $var),
        ]);
    }


    /**
     * @Route("/new", name="app_cours_enseignant_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CoursEnseignantRepository $coursEnseignantRepository): Response
    {
        $coursEnseignant = new CoursEnseignant();
        $form = $this->createForm(CoursEnseignantType::class, $coursEnseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursEnseignantRepository->add($coursEnseignant);
            return $this->redirectToRoute('app_cours_enseignant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/cours_enseignant/new.html.twig', [
            'cours_enseignant' => $coursEnseignant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_cours_enseignant_show", methods={"GET"})
     */
    public function show(CoursEnseignant $coursEnseignant): Response
    {
        return $this->render('admin/cours_enseignant/show.html.twig', [
            'cours_enseignant' => $coursEnseignant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_cours_enseignant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CoursEnseignant $coursEnseignant, CoursEnseignantRepository $coursEnseignantRepository): Response
    {
        $form = $this->createForm(CoursEnseignantType::class, $coursEnseignant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursEnseignantRepository->add($coursEnseignant);
            return $this->redirectToRoute('app_cours_enseignant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/cours_enseignant/edit.html.twig', [
            'cours_enseignant' => $coursEnseignant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_cours_enseignant_delete", methods={"POST"})
     */
    public function delete(Request $request, CoursEnseignant $coursEnseignant, CoursEnseignantRepository $coursEnseignantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coursEnseignant->getId(), $request->request->get('_token'))) {
            $coursEnseignantRepository->remove($coursEnseignant);
        }

        return $this->redirectToRoute('app_cours_enseignant_index', [], Response::HTTP_SEE_OTHER);
    }
}
