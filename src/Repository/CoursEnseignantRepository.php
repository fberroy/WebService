<?php

namespace App\Repository;

use App\Entity\CoursEnseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoursEnseignant>
 *
 * @method CoursEnseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursEnseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursEnseignant[]    findAll()
 * @method CoursEnseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursEnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursEnseignant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CoursEnseignant $entity, bool $flush = true): void
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
    public function remove(CoursEnseignant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function recherche($value, $col, $tri, $var)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT x
				FROM App\Entity\CoursEnseignant x, App\Entity\Cours c, App\Entity\Enseignant e			
				WHERE x.Enseignant = e.id
				AND x.Cours = c.id
				AND c.nomCours LIKE '"."$value"."%' OR c.nomCours LIKE '"."$value"."%' OR c.typeCours LIKE '"."$value"."%'
				ORDER BY $var.$col $tri");
	$query->setMaxResults(50);							
    	return $query->getResult();

    }

public function etatValidation($idc, $ide)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT x.Validation
                FROM App\Entity\Enseignant e, App\Entity\CoursEnseignant x
                WHERE e.id = x.Enseignant
                AND x.Cours = ' "." $idc'
                AND e.id = "." $ide" );							
    	return $query->getResult();

    }

public function etatVoeu($idc, $ide)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT x.Voeux
                FROM App\Entity\Enseignant e, App\Entity\CoursEnseignant x
                WHERE e.id = x.Enseignant
                AND x.Cours = ' "." $idc'
                AND e.id = "." $ide" );							
    	return $query->getResult();

    }

public function voeux()   
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT x
				FROM App\Entity\CoursEnseignant x			
				WHERE x.Validation=1");
	$query->setMaxResults(10);					
    	return $query->getResult();

    }

public function voeuxMax()
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT x
				FROM App\Entity\CoursEnseignant x			
				WHERE x.Validation=1");							
    	return $query->getResult();

    }

public function findByEns($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.Enseignant = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

public function Doublon($idc, $ide, $annee)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT count(x)
                FROM App\Entity\Enseignant e, App\Entity\CoursEnseignant x
                WHERE e.id = x.Enseignant
                AND x.Cours = "." $idc
                AND e.id = "." $ide
		AND x.anneeVoeux = "." $annee");							
    	return $query->getResult();

    }

    // /**
    //  * @return CoursEnseignant[] Returns an array of CoursEnseignant objects
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
    public function findOneBySomeField($value): ?CoursEnseignant
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
