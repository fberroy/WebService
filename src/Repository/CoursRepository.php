<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UERepository;

/**
 * @extends ServiceEntityRepository<Cours>
 *
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Cours $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Cours $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
	    //$this->_em->persist($entity);
            $this->_em->flush();
        }
    }



    public function manques() {
    	$query = $this->getEntityManager()  
		->createQuery("SELECT c ,(SELECT count(x.Enseignant) FROM App\Entity\CoursEnseignant x WHERE x.Cours = c.id)
				FROM App\Entity\Cours c, App\Entity\CoursEnseignant y
				WHERE c.id = y.Cours
				AND y.anneeVoeux = (SELECT a.annee FROM App\Entity\Annee a WHERE a.enCours = 1)
				AND c.NbEnseignants > (SELECT count(e.Enseignant) 
							FROM App\Entity\CoursEnseignant e
							WHERE e.Cours = c.id) ");
	$query->setMaxResults(20);
    	return $query->getResult();
    }

   public function conflits() {
    	$query = $this->getEntityManager()   
		 ->createQuery("SELECT c ,(SELECT count('x.Enseignant') FROM App\Entity\CoursEnseignant x WHERE x.Cours = c.id)
				FROM App\Entity\Cours c, App\Entity\CoursEnseignant y
				WHERE c.id = y.Cours
				AND y.anneeVoeux = (SELECT a.annee FROM App\Entity\Annee a WHERE a.enCours = 1)
				AND c.NbEnseignants < (SELECT count(e.Enseignant) 
							FROM App\Entity\CoursEnseignant e
							WHERE e.Cours = c.id) ");
	$query->setMaxResults(20);
    	return $query->getResult();
    }



  public function conflitsMax() {   
    	$query = $this->getEntityManager()   
		  ->createQuery("SELECT c ,(SELECT count('x.Enseignant') FROM App\Entity\CoursEnseignant x WHERE x.Cours = c.id)
				FROM App\Entity\Cours c, App\Entity\CoursEnseignant y
				WHERE c.id = y.Cours
				AND y.anneeVoeux = (SELECT a.annee FROM App\Entity\Annee a WHERE a.enCours = 1)
				AND c.NbEnseignants < (SELECT count(e.Enseignant) 
							FROM App\Entity\CoursEnseignant e
							WHERE e.Cours = c.id) ");
    	return $query->getResult();
    }


     public function manquesMax() {
    	$query = $this->getEntityManager()  
		->createQuery("SELECT c ,(SELECT count(x.Enseignant) FROM App\Entity\CoursEnseignant x WHERE x.Cours = c.id)
				FROM App\Entity\Cours c, App\Entity\CoursEnseignant y
				WHERE c.id = y.Cours
				AND y.anneeVoeux = (SELECT a.annee FROM App\Entity\Annee a WHERE a.enCours = 1)
				AND c.NbEnseignants > (SELECT count(e.Enseignant) 
							FROM App\Entity\CoursEnseignant e
							WHERE e.Cours = c.id) ");
    	return $query->getResult();
    }



// modifier avec $id
    public function mesCours() {
    	$query = $this->getEntityManager()  
	->createQuery("SELECT c, x.nbHeuresAtt, x.nbGroupesAtt, x.anneeVoeux
				FROM App\Entity\Cours c, App\Entity\CoursEnseignant x
				WHERE c.id = x.Cours
				AND x.anneeVoeux = (SELECT a.annee FROM App\Entity\Annee a WHERE a.enCours = 1)
				AND x.Enseigne = 1
				AND x.Enseignant = ".EnseignantRepository::id_session);
							

	$query->setMaxResults(20);
    	return $query->getResult();
    }


// modifier avec $id
    public function mesVoeux($annee) {
    	$query = $this->getEntityManager()  
		->createQuery("SELECT c, x.nbHeuresAtt, x.nbGroupesAtt, x.anneeVoeux
				FROM App\Entity\Cours c, App\Entity\CoursEnseignant x
				WHERE c.id = x.Cours
				AND x.anneeVoeux = ". $annee ."
				AND x.Voeux = 1
				AND x.Enseignant = ".EnseignantRepository::id_session);
							

	//$query->setMaxResults(5);
    	return $query->getResult();
    }




/* ->createQuery("SELECT c ,(SELECT count('x.Enseignant') FROM App\Entity\CoursEnseignant x WHERE x.Cours = c.id)
				FROM App\Entity\Cours c
				WHERE 'c.NbEnseignants' > (SELECT count('e.Enseignant') 
							FROM App\Entity\CoursEnseignant e
							WHERE e.Cours = c.id)");   */




/*

    public function searchName($data) {

        $string = $data['nom'];

    	$query = $this->getEntityManager()
                  ->createQuery("SELECT c FROM App\Entity\Livre l 
                                          WHERE UPPER(l.titre) LIKE UPPER('%$string%')");
    	return $query->getResult();
    }

*/

      // Find/search articles by title/content
    public function findCoursByName(string $query)
    {
        $query = $this->getEntityManager()
                  ->createQuery("SELECT c FROM App\Entity\Cours c 
                                          WHERE UPPER(c.nomCours) LIKE UPPER('%$string%')");
    	return $query->getResult();
    }

/*
 public function findCoursByName(string $query)
    {
       $query = $this->getEntityManager()
                  ->createQuery("SELECT c FROM App\Entity\Livre l 
                                          WHERE UPPER(l.titre) LIKE UPPER('%$string%')");
    	return $query->getResult();
    }

*/

    // /**
    //  * @return Cours[] Returns an array of Cours objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByNomCours($value): ?Cours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nomCours = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
