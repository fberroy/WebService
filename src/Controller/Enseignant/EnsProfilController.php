<?php

namespace App\Controller\Enseignant;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\CoursEnseignant;
use App\Repository\CoursEnseignantRepository;
use App\Entity\Annee;
use App\Repository\AnneeRepository;



class EnsProfilController extends AbstractController
{
    /**
     * @Route("/ens/profil/{id}", name="app_ens_profil")
     */
    public function index(EnseignantRepository $enseignantRepository, AnneeRepository $anneeRepository): Response
    {
	dump($anneeRepository->actuelle());
        return $this->render('enseignant/ens_profil/index.html.twig', array('m' => $enseignantRepository->moi(), 'annee' => $anneeRepository->actuelle() ));
    }


    /**
     * @Route("/ens/profil/{id}/edit", name="app_ens_profil_edit")
     */

    public function edit(Request $request, EnseignantRepository $enseignantRepository, Enseignant $enseignant,AnneeRepository $anneeRepository, EntityManagerInterface $manager): Response
    {

	$formEns=$this->createFormBuilder($enseignant)
	    ->add('nom')
	    //->add('prenom')
	    ->add('mail', EmailType::Class)
	    ->add('nbUC')
	    ->add('nomDepartement')
	    ->add('statutEnseignant')
	    ->getForm();

	$formEns->handleRequest($request);

	if($formEns->isSubmitted() && $formEns->IsValid())
	{
		$manager->persist($enseignant);
		$manager->flush();
		return $this->render('enseignant/ens_profil/index.html.twig', array('m' => $enseignantRepository->moi(), 'annee' => $anneeRepository->actuelle()));
	}

        return $this->render('enseignant/ens_profil/edit.html.twig',[
	'form'=> $formEns->createView(),
	'enseignants'=> $enseignant
	]);
    }


   /**
     * @Route("/ens/profil/{id}/reportVoeux", name="app_ens_reportVoeux")
     */
    public function ReportVoeux(CoursEnseignantRepository $CE, EnseignantRepository $enseignantRepository,Enseignant $enseignant,EntityManagerInterface $manager): Response
    {
	$CourEns=$CE->findByEns($enseignant);
	foreach($CourEns as $Cour)
	{

		$AlreadyExists=$CE->Doublon($Cour->getCours()->getId(),$enseignant->getId(),(int)date("Y")+1);
		dump($AlreadyExists[0][1]);
		if($AlreadyExists[0][1]==0)
		{
			$C=new CoursEnseignant();
			$C->setEnseignant($enseignant);
			$C->setCours($Cour->getCours());
			$C->setVoeux(1);
			$C->setEnseigne(0);
			$C->setNbHeuresAtt($Cour->getNbHeuresAtt());
			$C->setNbGroupesAtt($Cour->getNbGroupesAtt());
			$C->setValidation(1);
			$C->setAnneeVoeux((int)date("Y")+1);
			dump($C);
			$manager->persist($C);
			$manager->flush();

		}
	}
        return $this->redirectToRoute('app_ens_profil', array('m' => $enseignantRepository->moi(),'id'=>$enseignant));
    }


}
