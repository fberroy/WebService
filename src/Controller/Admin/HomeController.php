<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CoursRepository;
use App\Repository\EnseignantRepository;

use App\Repository\CoursEnseignantRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use League\Csv\Reader;
use \PDO;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\UE;
use App\Entity\Enseignant;
use App\Entity\Cours;
use App\Entity\Annee;
use App\Entity\CoursEnseignant;
use Doctrine\ORM\EntityManagerInterface;


class HomeController extends AbstractController
{
	
   
    /**
     * @Route("/admin", name="app_home", methods={"POST","GET"})
     */
    public function index(CoursRepository $coursRepository, EnseignantRepository $enseignantRepository, CoursEnseignantRepository $CoursEns,  Request $request, EntityManagerInterface $doctrine ): Response
    {
	
	
	//$entityManager=$this->getDoctrine()->getManager();
	//$Annee->setEnCours(true);
	//$Annee->flush();

	 $total = $enseignantRepository->graphProf2();
         $total = $enseignantRepository->graphProf2();
	 $max = $enseignantRepository->max();
	 $data = $enseignantRepository->graphProf();
	 shuffle($data);

	
	 

        return $this->render('admin/home/index.html.twig', array('cours5' => $coursRepository->manques() , 'conflits5' => $coursRepository->conflits(), 'style' => file_get_contents(__DIR__ . '/../../../public/assets/pie.css'),'style2' => file_get_contents(__DIR__ . '/../../../public/assets/histogram.css'), 'total' => $total[0][1], 'max' => $max[0][1],  'data' => $data,  ['controller_name' => 'HomeController' ],'voeux'=>$CoursEns->voeux()));
    }

/**
     * @Route("/conflits", name="app_voirPlus", methods={"GET"})
     */
    public function VoirPLus(CoursRepository $coursRepository): Response
    {
         return $this->render('admin/home/VoirPLus.html.twig', array('conflits5' => $coursRepository->conflitsMax()));
    }



      /**
     * @Route("/manques", name="app_voirPlus2", methods={"GET"})
     */
    public function VoirPLus2(CoursRepository $coursRepository): Response
    {
         return $this->render('admin/home/VoirPlus2.html.twig', array('manques5' => $coursRepository->manquesMax()));
    }

      /**
     * @Route("/voeux", name="app_voirPlus3", methods={"GET"})
     */
    public function VoirPLus3(CoursEnseignantRepository $coursEnseignantRepository): Response
    {
         return $this->render('admin/home/VoirPlus3.html.twig', array('Voeux' => $coursEnseignantRepository->VoeuxMax()));
    }

      /**
     * @Route("/voeux/accepter/{id}", name="app_voeux_accepter", methods={"GET"})
     */
    public function AccepterVoeux(CoursEnseignant $CoursEns, CoursEnseignantRepository $CoursEnsRepo, EntityManagerInterface $doctrine): Response
    {
	dump($CoursEns);
	$entityManager=$this->getDoctrine()->getManager();
	$CoursEns->setVoeux(true);
	$CoursEns->setValidation(false);
	$entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

      /**
     * @Route("/voeux/voirplus/accepter/{id}", name="app_voeux_accepter_plus", methods={"GET"})
     */
    public function AccepterVoeuxVoirPlus(CoursEnseignant $CoursEns, CoursEnseignantRepository $CoursEnsRepo, EntityManagerInterface $doctrine): Response
    {
	$entityManager=$this->getDoctrine()->getManager();
	$CoursEns->setVoeux(true);
	$CoursEns->setValidation(false);
	$entityManager->flush();
        return $this->redirectToRoute('app_voirPlus3');
    }


     /**
     * @Route("/voeux/refuser/{id}", name="app_voeux_refuser", methods={"GET"})
     */
    public function RefuserVoeux(CoursEnseignant $CoursEns, CoursEnseignantRepository $CoursEnsRepo, EntityManagerInterface $doctrine): Response
    {
	dump($CoursEns);
	$entityManager=$this->getDoctrine()->getManager();
	$CoursEns->setVoeux(false);
	$CoursEns->setValidation(false);
	$entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

     /**
     * @Route("/voeux/voirplus/refuser/{id}", name="app_voeux_refuser_plus", methods={"GET"})
     */
    public function RefuserVoeuxVoirPlus(CoursEnseignant $CoursEns, CoursEnseignantRepository $CoursEnsRepo, EntityManagerInterface $doctrine): Response
    {
	$entityManager=$this->getDoctrine()->getManager();
	$CoursEns->setVoeux(false);
	$CoursEns->setValidation(false);
	$entityManager->flush();
        return $this->redirectToRoute('app_voirPlus3');
    }
     
      /**
     * @Route("/changeAnnee/{Annee}", name="app_changeAnnee", methods={"POST"})
     */

/*
    public function Annee( AnneeRepository $AnneeRepo, EntityManagerInterface $doctrine): Response
    {
	$annees = $AnneeRepo->findAll();
	echo 8;
	echo $_POST [ 'annee' ];
	foreach($annees as $i){
		$i->setEnCours(false);
		if( $i->getAnnee() == $_POST [ 'annee' ]) { 
			$i->setEnCours(true); 
		}
		$entityManager->flush();
	}	
	//$entityManager=$this->getDoctrine()->getManager();
	//$Annee->setEnCours(true);
	//$Annee->flush();
        return $this->redirectToRoute('app_home');
    }
 */


}
