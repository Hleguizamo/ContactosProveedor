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

use Cpdg\AdministradorBundle\Entity\Areas;
use Cpdg\AdministradorBundle\Form\AreasType;
use Cpdg\UsuarioBundle\Entity\Logs;

/**
 * Areas controller.
 *
 */
class AreasController extends Controller
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
        /* Modal crear nuevo */
        $proveedore = new Areas();
        $formnew = $this->createForm(new AreasType(), $proveedore);
        $data = $request->request->all();
        if(isset($data['buscar'])){
            $consulta = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Areas')->createQueryBuilder('e');
            $consulta->where('e.nombre LIKE :nombre')->setParameter('nombre', '%'.$data["buscar"].'%');
        }else{
            $consulta = $em->getRepository('CpdgAdministradorBundle:Areas')->findAll();
        } 
        // Añadimos el paginador (En este caso el parámetro "1" es la página actual, y parámetro "10" es el número de páginas a mostrar)
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );
        return $this->render('CpdgAdministradorBundle:areas:index.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,
            'formNew' => $formnew->createView(),
            ));
    }

    /**
     * Creates a new Administrador entity.
     *
     */
    public function newAction(Request $request)
    {
        $area = new Areas();
        $form = $this->createForm(new AreasType(), $area);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($area);
                $em->flush();
                $this->addFlash('success', "Creado correctamente");
                $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
                $userid=$useridObj->getId();
                $this->processLogAction(0,$userid, "Crea nueva Area id: ".$area->getId()." Nombre: ".$area->getNombre());
           } catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('danger', "Error: No se puede crear el registro, Usuario ya ingresado en el sistema");
           }
            return $this->redirectToRoute('areas_index', array('page' => '1'));
        }

       return $this->redirectToRoute('areas_index', array('page' => '1'));
    }

    /**
     * Displays a form to edit an existing Administrador entity.
     *
     */
    public function editAction(Request $request, Areas $area)
    {
        $editForm = $this->createForm(new AreasType(), $area);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($area);
            $em->flush();

            return $this->redirectToRoute('areas_index', array('page' => '1'));
        }

        return $this->render('CpdgAdministradorBundle:areas:edit.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Contactos entity.
     *
     */
    public function deleteAction(Request $request, Areas $area)
    {
        $data = $request->request->all();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(0,$userid, "Elimina Area id: ".$area->getId()." Nombre: ".$area->getNombre());

        $this->addFlash('success', 'Eliminado correctamente');
        $em = $this->getDoctrine()->getManager();
        $em->remove($area);
        $em->flush();
        
        return $this->redirectToRoute('areas_index', array('page' => '1'));
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
    public function processLogAction($tipoUsuario, $usuario, $mensaje)
    {
        $em = $this->getDoctrine()->getManager();
        $log = new Logs();
        $log->setTipoUsuario($tipoUsuario);
        $log->setUsuario($usuario);
        $log->setAccion($mensaje);
        $log->setIp($_SERVER['REMOTE_ADDR']);
        $log->setFecha(new \DateTime(date("Y-m-d H:i:s")));
        $em->persist($log);
        $em->flush();
    }
}
