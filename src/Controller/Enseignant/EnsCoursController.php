<?php

namespace App\Controller\Enseignant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Entity\CoursEnseignant;
use App\Form\CoursEnseignantType;
use App\Repository\CoursEnseignantRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Enseignant;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EnseignantRepository;
use App\Repository\AnneeRepository;
    /**
     * @Route("/ens/cours")
     */
class EnsCoursController extends AbstractController
{

    /**
     * @Route("/", name="app_ens_cours")
     */
    public function index(Cours $cour): Response
    {
        return $this->render('enseignant/ens_cours/index.html.twig', [
            'controller_name' => 'EnsCoursController',
        ]);
    }


     /**
     * @Route("/{id}", name="app_ens_cours_show")
     */
    public function show(Cours $cour,Request $request, CoursEnseignantRepository $coursEnseignantRepository, AnneeRepository $anneeRepository, EntityManagerInterface $doctrine ): Response
    {
	$anneeVerif=$anneeRepository->actuelle();
	$coursEnseignant = new CoursEnseignant();
	$form = $this->createForm(CoursEnseignantType::class, $coursEnseignant);
        $form->handleRequest($request);
	
	if ($form->isSubmitted() && $form->isValid()) {
	    $ens=$doctrine->getRepository(Enseignant::class)->findOneBy(['id'=>EnseignantRepository::id_session]);
	    $coursEnseignant->setVoeux(0);
	    $coursEnseignant->setEnseigne(0);
	    $coursEnseignant->setEnseignant($ens);
	    $coursEnseignant->setCours($cour);
	    $coursEnseignant->setValidation(1);
	    $annee=(int)date("Y");
	    $coursEnseignant->setAnneeVoeux($annee+1);
            $coursEnseignantRepository->add($coursEnseignant);
            return $this->render('enseignant/ens_cours/show.html.twig', [
            'cour' => $cour,
	    'form' => $form->CreateView(),
	    'Inscription' => $coursEnseignantRepository->etatValidation($cour->getId(),EnseignantRepository::id_session),
	    'Voeu'=> $coursEnseignantRepository->etatVoeu($cour->getId(),EnseignantRepository::id_session),
	    'annee'=>$anneeVerif[0]['annee']
        ]);
        }

	$idc=$cour->getId();
	dump($coursEnseignantRepository->etatValidation($cour->getId(),EnseignantRepository::id_session));
	dump($coursEnseignantRepository->etatVoeu($cour->getId(),EnseignantRepository::id_session));
        return $this->render('enseignant/ens_cours/show.html.twig', [
            'cour' => $cour,
	    'form' => $form->CreateView(),
	    'Inscription' => $coursEnseignantRepository->etatValidation($cour->getId(),EnseignantRepository::id_session),
	    'Voeu'=> $coursEnseignantRepository->etatVoeu($cour->getId(),EnseignantRepository::id_session),
	    'annee'=>$anneeVerif[0]['annee']
        ]);
    }
}
