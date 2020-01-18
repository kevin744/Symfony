<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Friendship;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Friendship controller.
 *
 * @Route("api/friendship")
 */
class FriendshipController extends Controller
{
    /**
     * Lists all friendship entities.
     *
     * @Route("/", name="api_friendship_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $friendships = $em->getRepository('AppBundle:Friendship')->findBy(array('user'=>$user), array());
        $followers = $em->getRepository('AppBundle:Friendship')->findBy(array('friend'=>$user), array());

        return $this->render('friendship/index.html.twig', array(
            'friendships' => $friendships,
            'followers' => $followers,
        ));
    }



    /**
     * Creates a new friendship entity.
     *
     * @Route("/new", name="api_friendship_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $isAjax = $request->isXMLHttpRequest();
        if ($isAjax) {
            $friendId = $request->get('friendId');
            $friend = $this->get('fos_user.user_manager')->findUserBy(array('id' => $friendId));
            try{
                $friendship = new Friendship();
                $friendship->setUser($this->getUser())->setFriend($friend)->setCreatedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($friendship);
                $em->flush();
                $message="Request sent successfully";
            }catch (\Exception $e){
                $message="An error occured during friendship request. Please contact an admin. Error: "+$e->getMessage();
            }
            return new JsonResponse(array('message' => $message));
        }else{
            $friendship = new Friendship();
            $form = $this->createForm('AppBundle\Form\FriendshipType', $friendship);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($friendship);
                $em->flush();

                return $this->redirectToRoute('api_friendship_show', array('id' => $friendship->getId()));
            }

            return $this->render('friendship/new.html.twig', array(
                'friendship' => $friendship,
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * Accept friendship request.
     *
     * @Route("/accept", name="api_friendship_accept")
     * @Method({"POST"})
     */
    public function acceptFriendshipAction(Request $request)
    {
        $isAjax = $request->isXMLHttpRequest();
        if ($isAjax) {
            $user = $this->getUser();

            $friendId = $request->get('friendId');
            $friend = $this->get('fos_user.user_manager')->findUserBy(array('id' => $friendId));
            try{
                $friendship = $this->getDoctrine()->getRepository(Friendship::class)
                    ->findBy(array("user"=>$friend, "friend"=>$user));
                $friendship[0]->setIsAccepted(true)->setAcceptedOn(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $message="Request accepted successfully";
            }catch (\Exception $e){
                $message="An error occured during friendship request acceptation. Please contact an admin. Error: ".$e->getMessage();
            }
            return new JsonResponse(array('message' => $message));
        }
    }

    /**
     * Finds and displays a friendship entity.
     *
     * @Route("/{id}", name="api_friendship_show")
     * @Method("GET")
     */
    public function showAction(Friendship $friendship)
    {
        $deleteForm = $this->createDeleteForm($friendship);

        return $this->render('friendship/show.html.twig', array(
            'friendship' => $friendship,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing friendship entity.
     *
     * @Route("/{id}/edit", name="api_friendship_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Friendship $friendship)
    {
        $deleteForm = $this->createDeleteForm($friendship);
        $editForm = $this->createForm('AppBundle\Form\FriendshipType', $friendship);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('api_friendship_edit', array('id' => $friendship->getId()));
        }

        return $this->render('friendship/edit.html.twig', array(
            'friendship' => $friendship,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a friendship entity.
     *
     * @Route("/{id}", name="api_friendship_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Friendship $friendship)
    {
        $form = $this->createDeleteForm($friendship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendship);
            $em->flush();
        }

        return $this->redirectToRoute('api_friendship_index');
    }

    /**
     * Creates a form to delete a friendship entity.
     *
     * @param Friendship $friendship The friendship entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Friendship $friendship)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('api_friendship_delete', array('id' => $friendship->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
