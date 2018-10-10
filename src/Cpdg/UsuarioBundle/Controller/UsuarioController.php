<?php

namespace Cpdg\UsuarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cpdg\UsuarioBundle\Entity\Usuario;
use Cpdg\UsuarioBundle\Form\UsuarioType;


/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller
{
    /**
     * Lists all Usuario entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('CpdgUsuarioBundle:Usuario')->findAll();

        return $this->render('usuario/index.html.twig', array(
            'usuarios' => $usuarios,
        ));
    }

        /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
    public function editAction(Request $request, Usuario $usuario)
    {
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createForm(new UsuarioType(), $usuario);
        $editForm->handleRequest($request);
         $areas = $em->getRepository('CpdgUsuarioBundle:Areas')->findAll();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
           
            $em->persist($usuario);
            $em->flush();
            $this->addFlash('success', "Información actualizada correctamente");
            return $this->redirectToRoute('usuario_inicio');
        }
       
        return $this->render('CpdgUsuarioBundle:usuario:edit.html.twig', array(
            'usuario' => $usuario,
            'edit_form' => $editForm->createView(),
            'areas'=>$areas,
        ));
    }
}
