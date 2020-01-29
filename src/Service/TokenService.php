<?php 

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TokenService {
    /**
     * 
     *
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(UserRepository $ur, EntityManagerInterface $em)
    {
        $this->userRepository = $ur;
        $this->em = $em;
    }
    /** 
     * @param string $email
     * @return string
     */
    public function generateToken($email){


        $options = [
            'cost' => 12,
        ];

        $token = password_hash($email, PASSWORD_BCRYPT, $options);

        $user = $this->userRepository->findOneBy(['email' => $email]);
        $user->setToken($token);

        $date = new \DateTime("now");
        $date->add(new DateInterval('P1D'));

        $user->setTokenDate($date);

        $this->em->persist($user);
        $this->em->flush($user);

        return $token;

    }

    public function validateToken($token){
        
        $user = $this->userRepository->findOneBy(['token' => $token]);
        
        if($user instanceof \App\Entity\User){
            
            $now = new \DateTime("now");
            $date = $user->getTokenDate();

            if($now >= $date){
                return false; 
            }else{
                return true;
            }
        }
        
        throw new Exception('Access Denied');
    }
}