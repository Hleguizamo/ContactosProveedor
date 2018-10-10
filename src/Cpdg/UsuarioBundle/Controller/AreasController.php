<?php

namespace Cpdg\UsuarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cpdg\UsuarioBundle\Entity\Areas;
use Cpdg\UsuarioBundle\Form\AreasType;

/**
 * Areas controller.
 *
 */
class AreasController extends Controller
{
    /**
     * Lists all Areas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $areas = $em->getRepository('CpdgUsuarioBundle:Areas')->findAll();

        return $this->render('areas/index.html.twig', array(
            'areas' => $areas,
        ));
    }

    /**
     * Creates a new Areas entity.
     *
     */
    public function newAction(Request $request)
    {
        $area = new Areas();
        $form = $this->createForm(new AreasType(), $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($area);
            $em->flush();

            return $this->redirectToRoute('areas_show', array('id' => $areas->getId()));
        }

        return $this->render('areas/new.html.twig', array(
            'area' => $area,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Areas entity.
     *
     */
    public function showAction(Areas $area)
    {
        $deleteForm = $this->createDeleteForm($area);

        return $this->render('areas/show.html.twig', array(
            'area' => $area,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Areas entity.
     *
     */
    public function editAction(Request $request, Areas $area)
    {
        $deleteForm = $this->createDeleteForm($area);
        $editForm = $this->createForm(new AreasType(), $area);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($area);
            $em->flush();

            return $this->redirectToRoute('areas_edit', array('id' => $area->getId()));
        }

        return $this->render('areas/edit.html.twig', array(
            'area' => $area,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Areas entity.
     *
     */
    public function deleteAction(Request $request, Areas $area)
    {
        $form = $this->createDeleteForm($area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($area);
            $em->flush();
        }

        return $this->redirectToRoute('areas_index');
    }

    /**
     * Creates a form to delete a Areas entity.
     *
     * @param Areas $area The Areas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Areas $area)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('areas_delete', array('id' => $area->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
