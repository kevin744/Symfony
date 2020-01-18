<?php

namespace AppBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{

    function findByFriends($user){
        $users = array();
        $friends = $user->getFriends();

        foreach ($friends as $f){
            array_push($users, $f->getFriend());
        }

        $friends_with_me = $user->getFriendsWithMe();
        foreach ($friends_with_me as $f){
            array_push($users, $f->getUser());
        }

        array_push($users, $user);

        return $this->createQueryBuilder('p')
            ->andWhere('p.createdBy in (:users)')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('users', $users)
            ->getQuery()
            ->getResult();


    }

}