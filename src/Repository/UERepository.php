<?php

namespace App\Repository;

use App\Entity\UE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\EnseignantRepository;

/**
 * @extends ServiceEntityRepository<UE>
 *
 * @method UE|null find($id, $lockMode = null, $lockVersion = null)
 * @method UE|null findOneBy(array $criteria, array $orderBy = null)
 * @method UE[]    findAll()
 * @method UE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UE::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(UE $entity, bool $flush = true): void
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
    public function remove(UE $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    
    public function recherche($value, $col, $tri)
    {
	$query = $this->getEntityManager()  
		->createQuery("SELECT u
				FROM App\Entity\Ue u				
				 WHERE u.Intitule LIKE '"."$value"."%' OR u.formation LIKE '"."$value"."%' OR u.statut LIKE '"."$value"."%' 
				 ORDER BY u."."$col $tri");
				//WHERE u.Intitule = "."'$value'");							
    	return $query->getResult();

    }

    public function findOneByIntitule($value): ?UE
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.Intitule = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }



    // modifier avec $id
    public function mesUE() {
    	$query = $this->getEntityManager()  
	->createQuery("SELECT u
			FROM App\Entity\Cours c, App\Entity\CoursEnseignant x, App\Entity\UE u
			WHERE c.id = x.Cours
			AND c.Ue = u.id
			AND x.Enseigne = 1
			AND x.Enseignant = ".EnseignantRepository::id_session);
							

	$query->setMaxResults(20);
    	return $query->getResult();
    }


    // /**
    //  * @return UE[] Returns an array of UE objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UE
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
