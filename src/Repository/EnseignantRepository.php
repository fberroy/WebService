<?php

namespace App\Repository;

use App\Entity\Enseignant;
use App\Repository\EnseignantRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enseignant>
 *
 * @method Enseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignant[]    findAll()
 * @method Enseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignantRepository extends ServiceEntityRepository
{

    const id_session = 8;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Enseignant $entity, bool $flush = true): void
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
    public function remove(Enseignant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
	    //$this->_em->persist($entity);
            $this->_em->flush();
        }
    }




    // modifier avec $id
    public function moi() {
    	$query = $this->getEntityManager()  
		->createQuery("SELECT e
				FROM App\Entity\Enseignant e
				WHERE e.id =  ".EnseignantRepository::id_session );
    	return $query->getResult();
    }



    public function graphProf() {
    	$query = $this->getEntityManager()  
		->createQuery('SELECT e.statutEnseignant, count(e.statutEnseignant)
				FROM App\Entity\Enseignant e
				GROUP BY e.statutEnseignant'	);
    	return $query->getResult();
	//return $query->getArrayResult();
    }


   public function graphProf2() {
    	$query = $this->getEntityManager()  
		->createQuery('SELECT count(e.id)
				FROM App\Entity\Enseignant e'	);
    	return $query->getResult();
	//return $query->getArrayResult();
    }


   public function recherche($value, $col, $tri)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT e
				FROM App\Entity\Enseignant e
				WHERE e.archive = false				
				AND ( e.nom LIKE '%"."$value"."%' 
				OR e.prenom LIKE '"."$value"."%' 
				OR e.nomDepartement LIKE '"."$value"."%' 
				OR e.statutEnseignant LIKE '"."$value"."%' )
				ORDER BY e."."$col $tri");
				 
				//WHERE u.Intitule = "."'$value'");	
	$query->setMaxResults(50);						
    	return $query->getResult();
	//return $query->getArrayResult();
    }


    public function rechercheArchives($value, $col, $tri)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT e
				FROM App\Entity\Enseignant e
				WHERE e.archive = 1				
				AND ( e.nom LIKE '%"."$value"."%' 
				OR e.prenom LIKE '"."$value"."%' 
				OR e.nomDepartement LIKE '"."$value"."%' 
				OR e.statutEnseignant LIKE '"."$value"."%' )
				ORDER BY e."."$col $tri");
				 
				//WHERE u.Intitule = "."'$value'");	
	$query->setMaxResults(50);						
    	return $query->getResult();
	//return $query->getArrayResult();
    }


        public function max() {
    	$query = $this->getEntityManager()  
		->createQuery('SELECT count( DISTINCT e.statutEnseignant)
				FROM App\Entity\Enseignant e');
	return $query->getResult();
	
    }


     public function mesUC() {   // ?
    	$query = $this->getEntityManager()  
		->createQuery('SELECT e.id, SUM( x.nbHeuresAtt * x.nbGroupesAtt ) as UCs
				FROM App\Entity\Enseignant e, App\Entity\CoursEnseignant x, App\Entity\Cours c
				WHERE e.id = x.Enseignant
				AND c.id = x.Cours			
				GROUP BY e.id');
	return $query->getResult();
    }

    


      public function UC() {
    	$query = $this->getEntityManager()  
		->createQuery('SELECT e.id, e.statutEnseignant, x.nbGroupesAtt, x.nbHeuresAtt, c.typeCours
				FROM App\Entity\Enseignant e, App\Entity\CoursEnseignant x, App\Entity\Cours c
				WHERE e.id = x.Enseignant
				AND c.id = x.Cours');
	return $query->getResult();
    }

      public function id() {
    	$query = $this->getEntityManager()  
		->createQuery('SELECT e.id as clef, SUM(x.nbHeuresAtt) as UC
				FROM App\Entity\Enseignant e, App\Entity\CoursEnseignant x, App\Entity\Cours c
				WHERE e.id = x.Enseignant
				AND c.id = x.Cours
				GROUP BY e.id');
	return $query->getResult();
    }


    public function findByNom($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
 

    // /**
    //  * @return Enseignant[] Returns an array of Enseignant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enseignant
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
