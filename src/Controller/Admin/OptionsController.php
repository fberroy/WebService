<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CoursRepository;
use App\Repository\EnseignantRepository;
use App\Repository\AnneeRepository;


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


/**
 * @Route("/admin/options")
 */
class OptionsController extends AbstractController
{
    /**
     * @Route("/", name="app_options")
     */
    public function index( AnneeRepository $anneeRepository,CoursRepository $coursRepository, EnseignantRepository $enseignantRepository, CoursEnseignantRepository $CoursEns,  Request $request, EntityManagerInterface $doctrine ): Response
    {

	
// Annee
	$annees = $anneeRepository->findAll();
	foreach($annees as $i){
		$i->setEnCours(false);
		if( isset($_POST [ 'annee' ]) ){
			if( $i->getAnnee() ==  $_POST [ 'annee' ]) { 
			$i->setEnCours(true); 
			}
			$entityManager=$this->getDoctrine()->getManager();
			$entityManager->flush();
		}
		
	}	

//Import EXCEL
	$dbh=$this->getDoctrine()->getManager()->getConnection();
	$defaultData = ['file' => NULL];
	$form = $this->createFormBuilder($defaultData)
	    ->add('file', FileType::class)
	    ->add('send', SubmitType::class)
	    ->getForm();
	$form->handleRequest($request);

	if ($form->isSubmitted() && $form->isValid()) 
	{	
		$annee=new Annee();
		$annee->setAnnee(2022);
		$annee->setEnCours(1);
		$doctrine->persist($annee);
		$doctrine->flush();
 	   	// data is an array with "name", "email", and "message" keys
 	   	$data = $form->getData();
	   	$file = $form->get('file')->getData();
	   	dump($file);
		$csv = Reader::createFromPath($file,'r');
		$csv->setHeaderOffset(0);
		//Prepare la commande d'ajout d'enseignants
		$ensth = $dbh->prepare("INSERT INTO `enseignant` (`nom`, `prenom`, `identifiant`, `mot_de_passe`, `mail`, `nb_uc`, `nb_ucattribue`, `nom_departement`, `statut_enseignant`, `acces_admin`,`archive`) VALUES (:nomEns, NULL, NULL, 'user', NULL, :nb_uc , NULL, NULL, NULL, 0,0)");
		//Prepare la commande d'ajout d'UE
		$uesth=$dbh->prepare("INSERT INTO `ue`(`intitule`, `formation`, `semestre`, `statut`, `effectif`) VALUES (:ueNom,:formation,:semestre,:statut,:effectif)");
		
		foreach ($csv as $attribut)
		{
			$ens=$doctrine->getRepository(Enseignant::class)->findOneBy(['nom'=>$attribut['Nom']]);
			$uesrc=$doctrine->getRepository(UE::class)->findOneByIntitule($attribut['Intitule']);

			if(!$ens && !empty($attribut['Nom']))
			{
				$ensth->bindValue(':nomEns', $attribut['Nom'],PDO::PARAM_STR);
				$ensth->bindValue(':nb_uc', (int)$attribut['Dû'],PDO::PARAM_INT);
				$ensth->execute();
			}
			if(!$uesrc)
			{
				$uesth->bindValue(':ueNom', $attribut['Intitule'],PDO::PARAM_STR);
				$uesth->bindValue(':formation', $attribut['Formation'],PDO::PARAM_STR);
				$uesth->bindValue(':semestre', $attribut['Semestre'],PDO::PARAM_STR);
				$uesth->bindValue(':statut', $attribut['Statut'],PDO::PARAM_STR);
				$uesth->bindValue(':effectif', (int)$attribut['Effectif'],PDO::PARAM_INT);
				$uesth->execute();
				

				//Ajout CM
				
				if(!empty($attribut['grCM']))
				{
					$CM_Manager=$this->getdoctrine()->getManager();

					$CMcour=new Cours();
					$CMcour->setNomCours($attribut['Intitule']." CM");
					$CMcour->setNbHeures((int)$attribut['h/CM']);
					$CMcour->setTypeCours("CM");
					$CMcour->setNbEnseignants(2);
					$CMcour->setNbGroupes((int)$attribut['grCM']);
					if(!$uesrc){$uesrc=$doctrine->getRepository(UE::class)->findOneByIntitule($attribut['Intitule']);}
					$CMcour->setUe($uesrc);
					$CM_Manager->persist($CMcour);
					$CM_Manager->flush();
				}
				//Ajout TD
				$TD_Manager=$this->getdoctrine()->getManager();
				if(!empty($attribut['grTD']))
				{
					$TDcour=new Cours();
					$TDcour->setNomCours($attribut['Intitule']." TD");
					$TDcour->setNbHeures((int)$attribut['h/TD']);
					$TDcour->setTypeCours("TD");
					$TDcour->setNbEnseignants((int)$attribut['grTD']);
					$TDcour->setNbGroupes((int)$attribut['grTD']);
					if(!$uesrc){$uesrc=$doctrine->getRepository(UE::class)->findOneByIntitule($attribut['Intitule']);}
					$TDcour->setUe($uesrc);
					$TD_Manager->persist($TDcour);
					$TD_Manager->flush();
				}

				//Ajout TP
				$TP_Manager=$this->getdoctrine()->getManager();

				if(!empty($attribut['grTP']))
				{
					$TPcour=new Cours();
					$TPcour->setNomCours($attribut['Intitule']." TP");
					$TPcour->setNbHeures((int)$attribut['h/TP']);
					$TPcour->setTypeCours("TP");
					$TPcour->setNbEnseignants((int)$attribut['grTP']);
					$TPcour->setNbGroupes((int)$attribut['grTP']);
					if(!$uesrc){$uesrc=$doctrine->getRepository(UE::class)->findOneByIntitule($attribut['Intitule']);}
					$TPcour->setUe($uesrc);
					$TP_Manager->persist($TPcour);
					$TP_Manager->flush();	
				}
			}
			//Ajout des liens Cours/Enseignants
			//CM
			$CE_Manager=$this->getdoctrine()->getManager();
			if(!empty($attribut['CM']))
			{
				if($doctrine->getRepository(Cours::class)->findOneByNomCours($attribut['Intitule']." CM")!=NULL)
				{
					$CMce=new CoursEnseignant();
					$CMce->setVoeux(1);
					$CMce->setEnseigne(1);
					$CMce->setNbHeuresAtt(0);
					$CMce->setNbGroupesAtt((int)$attribut['CM']);
					$SRCEns=$doctrine->getRepository(Enseignant::class)->findOneByNom($attribut['Nom']);
					$CMce->setEnseignant($SRCEns);
					$SRCcrs=$doctrine->getRepository(Cours::class)->findOneByNomCours($attribut['Intitule']." CM");
					$CMce->setCours($SRCcrs);
					$CMce->setAnneeVoeux(2022);
					$CE_Manager->persist($CMce);
					$CE_Manager->flush();	
				}
			}
			//TD
			$CE_Manager=$this->getdoctrine()->getManager();
			if(!empty($attribut['TD']))
			{
				if($doctrine->getRepository(Cours::class)->findOneByNomCours($attribut['Intitule']." TD")!=NULL)
				{
					$TDce=new CoursEnseignant();
					$TDce->setVoeux(1);
					$TDce->setEnseigne(1);
					$TDce->setNbHeuresAtt(0);
					$TDce->setNbGroupesAtt((int)$attribut['TD']);
					$SRCEns=$doctrine->getRepository(Enseignant::class)->findOneByNom($attribut['Nom']);
					$TDce->setEnseignant($SRCEns);
					$SRCcrs=$doctrine->getRepository(Cours::class)->findOneByNomCours($attribut['Intitule']." TD");
					$TDce->setCours($SRCcrs);
					$TDce->setAnneeVoeux(2022);
					$CE_Manager->persist($TDce);
					$CE_Manager->flush();	
				}
			}
			//TP
			$CE_Manager=$this->getdoctrine()->getManager();
			if(!empty($attribut['TP']))
			{
				if($doctrine->getRepository(Cours::class)->findOneByNomCours($attribut['Intitule']." TP")!=NULL)
				{
					$TPce=new CoursEnseignant();
					$TPce->setVoeux(1);
					$TPce->setEnseigne(1);
					$TPce->setNbHeuresAtt(0);
					$TPce->setNbGroupesAtt((int)$attribut['TP']);
					$SRCEns=$doctrine->getRepository(Enseignant::class)->findOneByNom($attribut['Nom']);
					$TPce->setEnseignant($SRCEns);
					$SRCcrs=$doctrine->getRepository(Cours::class)->findOneByNomCours($attribut['Intitule']." TP");
					$TPce->setCours($SRCcrs);
					$TPce->setAnneeVoeux(2022);
					$CE_Manager->persist($TPce);
					$CE_Manager->flush();	
				}
			}
	 	}
	}

//Ajouter les données d'enseignants
	$defaultDataEns = ['fileEns' => NULL];
	$formEns = $this->createFormBuilder($defaultData)
	    ->add('fileEns', FileType::class)
	    ->add('sendEns', SubmitType::class)
	    ->getForm();
	$formEns->handleRequest($request);

	if ($formEns->isSubmitted() && $formEns->isValid()) 
	{
		$data = $formEns->getData();
	   	$file = $formEns->get('fileEns')->getData();
	   	dump($file);
		$csv = Reader::createFromPath($file,'r');
		$csv->setHeaderOffset(0);
		$th = $dbh->prepare("UPDATE `enseignant` SET `nom_departement`=:dept, `statut_enseignant`=:statutEns WHERE `nom` = :nom");

		foreach ($csv as $ensTab)
		{
			$ens=$doctrine->getRepository(Enseignant::class)->findOneBy(['nom'=>$ensTab['Enseignants']]);
			if($ens)
			{
				if(empty($ensTab['Dept'])){$th->bindvalue(':dept',NULL);}
				else {$th->bindValue(':dept', $ensTab['Dept'],PDO::PARAM_STR);}

				$th->bindValue(':statutEns', $ensTab['Statut'],PDO::PARAM_STR);
				$th->bindValue(':nom', $ensTab['Enseignants'],PDO::PARAM_STR);
				$th->execute();
			}
		}
	}
	
        return $this->render('admin/options/index.html.twig', [
            'controller_name' => 'OptionsController','annee' => $anneeRepository->annee() ,'upload' => $form->createView(), 'formEns' => $formEns->CreateView(),
        ]);
    }
}
