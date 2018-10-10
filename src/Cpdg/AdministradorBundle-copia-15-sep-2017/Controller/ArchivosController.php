<?php

namespace Cpdg\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cpdg\AdministradorBundle\Entity\Archivos;
use Cpdg\AdministradorBundle\Form\ArchivosType;

/**
 * Archivos controller.
 *
 */
class ArchivosController extends Controller
{
    /**
     * Lists all Archivos entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $archivos = $em->getRepository('CpdgAdministradorBundle:Archivos')->findAll();

        return $this->render('archivos/index.html.twig', array(
            'archivos' => $archivos,
        ));
    }

    /**
     * Creates a new Archivos entity.
     *
     */
    public function newAction(Request $request)
    {
        $archivo = new Archivos();
        $form = $this->createForm(new ArchivosType(), $archivo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($archivo);
            $em->flush();

            return $this->redirectToRoute('archivos_show', array('id' => $archivos->getId()));
        }

        return $this->render('archivos/new.html.twig', array(
            'archivo' => $archivo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Archivos entity.
     *
     */
    public function showAction(Archivos $archivo)
    {
        $deleteForm = $this->createDeleteForm($archivo);

        return $this->render('archivos/show.html.twig', array(
            'archivo' => $archivo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Archivos entity.
     *
     */
    public function editAction(Request $request, Archivos $archivo)
    {
        $deleteForm = $this->createDeleteForm($archivo);
        $editForm = $this->createForm(new ArchivosType(), $archivo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($archivo);
            $em->flush();

            return $this->redirectToRoute('archivos_edit', array('id' => $archivo->getId()));
        }

        return $this->render('archivos/edit.html.twig', array(
            'archivo' => $archivo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Archivos entity.
     *
     */
    public function deleteAction(Request $request, Archivos $archivo)
    {
        $form = $this->createDeleteForm($archivo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($archivo);
            $em->flush();
        }

        return $this->redirectToRoute('archivos_index');
    }

    /**
     * Creates a form to delete a Archivos entity.
     *
     * @param Archivos $archivo The Archivos entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Archivos $archivo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('archivos_delete', array('id' => $archivo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
