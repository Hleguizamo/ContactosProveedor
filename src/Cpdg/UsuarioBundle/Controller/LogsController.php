<?php

namespace Cpdg\UsuarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cpdg\UsuarioBundle\Entity\Logs;
use Cpdg\UsuarioBundle\Form\LogsType;
use Doctrine\ORM\EntityManager;

/**
 * Logs controller.
 *
 */
class LogsController extends Controller 
{
    protected  $entityManager;
    public $entman;

    public function __construct(EntityManager $entityManager){
       //$this->entman = $this->getDoctrine()->getManager();
        //$this->entityManager = $entityManager;
       // $this->entman = $entityManager;
    }
    /**
     * Lists all Logs entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $logs = $em->getRepository('CpdgUsuarioBundle:Logs')->findAll();

        return $this->render('CpdgUsuarioBundle:logs:index.html.twig', array(
            'logs' => $logs,
        ));
    }

    /**
     * Creates a new Logs entity.
     *
     */
    public function newAction(Request $request)
    {
        $log = new Logs();
        $form = $this->createForm(new LogsType(), $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();

            return $this->redirectToRoute('logs_show', array('id' => $logs->getId()));
        }

        return $this->render('logs/new.html.twig', array(
            'log' => $log,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Logs entity action.
     *
     */
    public function newLogAction($value1)
    {         
         //$em = $this->getDoctrine()->getManager();
        /* $logs = new Logs();
         $logs->setTipoUsuario(1);
         $logs->setUsuario($value1);
         $logs->setAccion($value1);*/
        // $this->addFlash('danger', 'sad');

             //$em->persist($logs);
    }

    /**
     * Finds and displays a Logs entity.
     *
     */
    public function showAction(Logs $log)
    {
        $deleteForm = $this->createDeleteForm($log);

        return $this->render('logs/show.html.twig', array(
            'log' => $log,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Logs entity.
     *
     */
    public function editAction(Request $request, Logs $log)
    {
        $deleteForm = $this->createDeleteForm($log);
        $editForm = $this->createForm(new LogsType(), $log);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();

            return $this->redirectToRoute('logs_edit', array('id' => $log->getId()));
        }

        return $this->render('logs/edit.html.twig', array(
            'log' => $log,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Logs entity.
     *
     */
    public function deleteAction(Request $request, Logs $log)
    {
        $form = $this->createDeleteForm($log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($log);
            $em->flush();
        }

        return $this->redirectToRoute('logs_index');
    }

    /**
     * Creates a form to delete a Logs entity.
     *
     * @param Logs $log The Logs entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Logs $log)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('logs_delete', array('id' => $log->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
