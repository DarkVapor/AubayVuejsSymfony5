<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function filter($filters = null){
      
        $entityManager = $this->getEntityManager();
        $qs ='   
            SELECT u 
            FROM App\Entity\User u 
        ';
        
        if(isset($filters['search'])){
            $qs .= ' WHERE LOWER(CONCAT(u.name, u.email, u.id)) LIKE CONCAT(\'%\', LOWER(:search), \'%\')';
        }

        
        if(isset($filters['field']) && isset($filters['direction'])){
            $qs .= ' ORDER BY u.'.$filters['field'].' '.$filters['direction'];
        }
       
        $query = $entityManager->createQuery($qs);
        if(isset($filters['limit']) && isset($filters['offset'])){
            $query->setMaxResults($filters['limit']);
            $query->setFirstResult($filters['offset']);
        }
        if(isset($filters['search'])){
            $query->setParameter('search', $filters['search']);
        }
        return $query->getResult();
    
    }
}
