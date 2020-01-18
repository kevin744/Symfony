<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comments;
use AppBundle\Entity\Friendship;
use AppBundle\Form\PostType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $all_users  = $this->getDoctrine()->getManager()->createQueryBuilder()->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.username !=:username')
            ->setParameter('username', $user->getUsername())->getQuery()->execute();
        $friends = $user->getFriends();
        $friends_with_me = $user->getFriendsWithMe();

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)->findByFriends($user);
        $friendship_requests=$this->getDoctrine()->getRepository(Friendship::class)->getFriendshipRequest($user);
        $post = new Post();
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);
        $commentForms = array();
        foreach ($posts as $p){
            $commentForm[$p->getId()] = $this->createForm('AppBundle\Form\CommentsType', new Comments());
            array_push($commentForms, $commentForm[$p->getId()]->createView());
        }

        return $this->render('default/index.html.twig', [
                'form'=>$postForm->createView(),
                'posts'=>$posts,
                'commentForms'=>$commentForms,
                'all_users' => $all_users,
                'friends'=>$friends,
                'friends_with_me'=>$friends_with_me,
                'friendship_requests' =>$friendship_requests
            ]
        );
    }


    /**
     * @Route("/friend-profile/{username}", name="friend_profile")
     */
    public function friendProfileAction(Request $request, $username){
        $user = $this->getUser();
        $friend = $this->get('fos_user.user_manager')->findUserByUsername($username);
        if ($user == $friend){
            return $this->redirect("/profile/");
        }
        $my_friends = $user->getFriends();
        $friend_friends = $friend->getFriends();

        $friends_with_me = $user->getFriendsWithMe();

        $visible = 0;
        $posts = null;

        foreach ($my_friends as $f){

            if($f->getFriend() == $friend){
                if($f->getIsAccepted()){
                    $visible = 2; // friendship is accepted
                    $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findBy(array('createdBy'=>$friend));
                }else{
                    $visible = 1; // friendship request is made but not accepted yet
                }
            }
        }

        foreach ($friend_friends as $f){
            if($f->getFriend() == $user){
                if($f->getIsAccepted()){
                    $visible = 2; // friendship is accepted
                    $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findBy(array('createdBy'=>$friend));
                }else{
                    $visible = 3; // friendship request is made and has to be accepted
                }
            }
        }

        return $this->render('default/friend_profile.html.twig', [
                'user'=>$user,
                'friend'=>$friend,
                'visible' => $visible,
                'my_friends'=>$my_friends,
                'friends_with_me'=>$friends_with_me,
                'posts'=>$posts
            ]
        );
    }


    /**
     * @Route("/friends", name="friends")
     */
    public function friendsAction(Request $request){
        $user = $this->getUser();
        $friends = $user->getFriends();
        $friends_with_me = $user->getFriendsWithMe();

        return $this->render('default/friends.html.twig', [
                'friends'=>$friends,
                'friends_with_me'=>$friends_with_me
            ]
        );
    }
}
