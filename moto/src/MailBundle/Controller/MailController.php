<?php

namespace MailBundle\Controller;

use MailBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Mail controller.
 *
 * @Route("")
 */
class MailController extends Controller
{
    /**
     * Lists all mail entities.
     *
     * @Route("/", name="mails_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $mails = $em->getRepository('MailBundle:Mail')->findBy(array("createdBy"=>$this->getUser()), array("createdAt"=>"desc"));
        $received_mails = $em->getRepository('MailBundle:Mail')->findBy(array("sendTo"=>$this->getUser()), array("createdAt"=>"desc"));
        return $this->render('mail/index.html.twig', array(
            'mails' => $mails,
            'received_mails'=>$received_mails,
        ));
    }

    /**
     * Creates a new mail entity.
     *
     * @Route("/new", name="mails_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $isAjax = $request->isXMLHttpRequest();
        if ($isAjax) {
            $user = $this->getUser();
            $friendId = $request->get('friendId');
            $friend = $this->get('fos_user.user_manager')->findUserBy(array('id' => $friendId));
            $subject = $request->get('subject');
            $body = $request->get('body');
            try{
                $mail = new Mail();
                $mail->setSubject($subject)->setBody($body)->setSendTo($friend)->setCreatedBy($user)
                    ->setCreatedAt(new \DateTime())->setIsRead(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($mail);
                $em->flush();
                $message="Email sent successfully";
            }catch (\Exception $e){
                $message="An error occured during email sending. Please contact an admin. Error: "+$e->getMessage();
            }
            return new JsonResponse(array('message' => $message));
        }else{
            $mail = new Mail();
            $form = $this->createForm('MailBundle\Form\MailType', $mail);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($mail);
                $em->flush();

                return $this->redirectToRoute('mails_show', array('id' => $mail->getId()));
            }

            return $this->render('mail/new.html.twig', array(
                'mail' => $mail,
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * Finds and displays a mail entity.
     *
     * @Route("/{id}", name="mails_show")
     * @Method("GET")
     */
    public function showAction(Mail $mail)
    {
        $mail->setIsRead(true);
        $this->getDoctrine()->getManager()->flush();
        $deleteForm = $this->createDeleteForm($mail);

        return $this->render('mail/show.html.twig', array(
            'mail' => $mail,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing mail entity.
     *
     * @Route("/{id}/edit", name="mails_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Mail $mail)
    {
        $deleteForm = $this->createDeleteForm($mail);
        $editForm = $this->createForm('MailBundle\Form\MailType', $mail);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mails_edit', array('id' => $mail->getId()));
        }

        return $this->render('mail/edit.html.twig', array(
            'mail' => $mail,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a mail entity.
     *
     * @Route("/{id}", name="mails_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Mail $mail)
    {
        $form = $this->createDeleteForm($mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($mail);
            $em->flush();
        }

        return $this->redirectToRoute('mails_index');
    }

    /**
     * Creates a form to delete a mail entity.
     *
     * @param Mail $mail The mail entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Mail $mail)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('mails_delete', array('id' => $mail->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
