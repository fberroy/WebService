<?php

namespace App\Controller;

use App\Entity\Annee;
use App\Form\AnneeType;
use App\Repository\AnneeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/annee")
 */
class AnneeController extends AbstractController
{
    /**
     * @Route("/", name="app_annee")
     */
    public function index(): Response
    {
        return $this->render('admin/annee/index.html.twig', [
            'controller_name' => 'AnneeController',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_annee_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Annee $annee, AnneeRepository $anneeRepository): Response
    {
        $form = $this->createForm(AnneeType::class, $annee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $anneeRepository->add($annee);
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/annee/edit.html.twig', [
            'annee' => $annee,
            'form' => $form,
        ]);
    }

     /**
     * @Route("/new", name="app_annee_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AnneeRepository $anneeRepository): Response
    {
        $annee = new Annee();
        $form = $this->createForm(AnneeType::class, $annee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annee->setEnCours(false);
	    $anneeRepository->add($annee);
	    
	    //dump($annee);
            return $this->redirectToRoute('app_options', [], Response::HTTP_SEE_OTHER);
	    
        }
	
        return $this->renderForm('admin/annee/new.html.twig', [
            'annee' => $annee,
            'form' => $form,
        ]);
    }

}
