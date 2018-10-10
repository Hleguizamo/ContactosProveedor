<?php

namespace Cpdg\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Cpdg\AdministradorBundle\Entity\Logs;
use Cpdg\AdministradorBundle\Form\LogsType;

/**
 * Logs controller.
 *
 */
class LogsController extends Controller
{
     private $cantidadPorPagina = 20;
    /**
    * @Route("/", defaults={"page": 1}, name="proveedores_index")
    * @Method("GET")
    * @Cache(smaxage="10")
    */
    public function indexAction($page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        if(isset($data['buscar'])){
            $consulta = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Logs')->createQueryBuilder('e');
            $consulta->orWhere('e.usuario LIKE :usuario')->setParameter('usuario', '%'.$data["buscar"].'%');
            $consulta->orWhere('e.accion LIKE :accion')->setParameter('accion', '%'.$data["buscar"].'%');
            $consulta->addOrderBy('e.fecha', 'DESC');
        }else{
             $consulta = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Logs')->createQueryBuilder('e');
             $consulta->addOrderBy('e.fecha', 'DESC');
        } 

        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );
        return $this->render('CpdgAdministradorBundle:logs:index.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,
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
