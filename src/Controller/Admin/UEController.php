<?php

namespace App\Controller\Admin;

use App\Entity\UE;
use App\Entity\Cours;
use App\Form\UEType;
use App\Repository\UERepository;
use App\Repository\CoursRepository;
use App\Repository\AnneeRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class UEController extends AbstractController
{


     /**
     * @Route("/admin/ue/", name="app_u_e_index", methods={"GET"})
     */
    public function index(UERepository $uERepository): Response
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
        return $this->render('admin/ue/index.html.twig', [
            'u_es' => $uERepository->recherche($recherche, $col, $tri),
        ]);
    }


    /**
     * @Route("/admin/ue/new", name="app_u_e_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UERepository $uERepository,CoursRepository $coursRepository ): Response
    {
        $dataArray=['nouvelleUE'=>'Création d\'une nouvelle UE'];
        $form = $this->createFormBuilder($dataArray)
		    ->add('Intitule')
	    	    ->add('formation')
	    	    ->add('semestre')
	    	    ->add('statut')
	    	    ->add('effectif')
		    //CM
		    ->add('checkCM',CheckboxType::class,['required'=>false])
		    ->add('nbHeuresCM',IntegerType::class,['required'=>false])
		    ->add('NbEnseignantsCM',IntegerType::class,['required'=>false])
		    ->add('nbGroupesCM', IntegerType::class,['required'=>false])
		    //TD
		    ->add('checkTD',CheckboxType::class,['required'=>false])
		    ->add('nbHeuresTD',IntegerType::class,['required'=>false])
		    ->add('NbEnseignantsTD',IntegerType::class,['required'=>false])
		    ->add('nbGroupesTD',IntegerType::class,['required'=>false])
		    //TP
		    ->add('checkTP',CheckboxType::class,['required'=>false])
		    ->add('nbHeuresTP',IntegerType::class,['required'=>false])
		    ->add('NbEnseignantsTP',IntegerType::class,['required'=>false])
		    ->add('nbGroupesTP',IntegerType::class,['required'=>false])
		    ->add('Valider',SubmitType::class)
		    ->getForm();
	
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	    
		    $data=$form->getData();
		    dump($data);
		    //Création de l'UE
		    $uE=new UE();
		    $uE->setIntitule($data['Intitule']);
		    $uE->setFormation($data['formation']);
		    $uE->setSemestre($data['semestre']);
		    $uE->setStatut($data['statut']);
	    	    $uE->setEffectif($data['effectif']);
	 	    $uERepository->add($uE);
		    //Création du CM
		    if($data['checkCM']==true)
		    {
			    $CM=new Cours();
			    $CM->setNomCours($data['Intitule'].' CM');
			    $CM->setNbHeures($data['nbHeuresCM']);
			    $CM->setTypeCours("CM");
			    $CM->setNbEnseignants($data['NbEnseignantsCM']);
			    $CM->setNbGroupes($data['nbGroupesCM']);
			    $CM->setUe($uE);
			    $coursRepository->add($CM);
		    }
		    //Création du TD
		    if($data['checkTD']==true)
		    {
			    $TD=new Cours();
			    $TD->setNomCours($data['Intitule'].' TD');
			    $TD->setNbHeures($data['nbHeuresTD']);
			    $TD->setTypeCours("TD");
			    $TD->setNbEnseignants($data['NbEnseignantsTD']);
			    $TD->setNbGroupes($data['nbGroupesTD']);
			    $TD->setUe($uE);
			    $coursRepository->add($TD);
		    }
		    //Création du TP
		    if($data['checkTP']==true)
		    {
			    $TP=new Cours();
			    $TP->setNomCours($data['Intitule'].' TP');
			    $TP->setNbHeures($data['nbHeuresTP']);
			    $TP->setTypeCours("TP");
			    $TP->setNbEnseignants($data['NbEnseignantsTP']);
			    $TP->setNbGroupes($data['nbGroupesTP']);
			    $TP->setUe($uE);
			    $coursRepository->add($TP);
		    }
	    
	    
	   
	    
            
            return $this->redirectToRoute('app_u_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/ue/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/ue/{id}", name="app_u_e_show", methods={"GET"})
     */
    public function show(UE $uE, AnneeRepository $anneeRepository): Response
    {
	$anneeVerif=$anneeRepository->actuelle();
        return $this->render('admin/ue/show.html.twig', [
            'u_e' => $uE,
	    'annee'=>$anneeVerif[0]['annee']
        ]);
    }

    /**
     * @Route("/admin/ue/{id}/edit", name="app_u_e_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UE $uE, UERepository $uERepository): Response
    {
        $form = $this->createForm(UEType::class, $uE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uERepository->add($uE);
            return $this->redirectToRoute('app_u_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/ue/edit.html.twig', [
            'u_e' => $uE,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/ue/{id}", name="app_u_e_delete", methods={"POST"})
     */
    public function delete(Request $request, UE $uE, UERepository $uERepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uE->getId(), $request->request->get('_token'))) {
            $uERepository->remove($uE);
        }

        return $this->redirectToRoute('app_u_e_index', [], Response::HTTP_SEE_OTHER);
    }
}
