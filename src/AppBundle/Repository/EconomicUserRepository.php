<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Repository;

/**
 * Description of EconomicUser
 *
 * @author lambert
 */

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;

class EconomicUserRepository extends EntityRepository implements UserProviderInterface{
    
    public function loadUserByUsername($username)
           {
    $user = $this->createQueryBuilder('u')
                 ->where('u.username = :username OR u.email = :email')
                 ->setParameter('username', $username)
                 ->setParameter('email', $username)
                 ->getQuery()
                 ->getOneOrNullResult();
            if (null === $user) {
              $message = sprintf('Unable to find an active user AppBundle:EconomicUser object identified by "%s".',$username);
               throw new UsernameNotFoundException($message);
               }
            return $user;
           }
           
    public function refreshUser(UserInterface $user)
          {
             $class = get_class($user);
             if (!$this->supportsClass($class)) {
                throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.',$class));
               }
            return $this->find($user->getId());
          }
          
    public function supportsClass($class)
          {
            return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
          }
}
