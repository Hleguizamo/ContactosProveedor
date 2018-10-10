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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use Cpdg\AdministradorBundle\Entity\Usuario;
use Cpdg\AdministradorBundle\Form\UsuarioType;
use Cpdg\UsuarioBundle\Entity\Logs;


/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller
{
    private $cantidadPorPagina = 20;
    /**
    * @Route("/", defaults={"page": 1}, name="proveedores_index")
    * @Method("GET")
    * @Cache(smaxage="10")
    */    
    public function indexAction($page, Request $request)
    {        

        $session = $request->getSession();
        $convenio = $session->get('admin')->getConvenio();

        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $data = $request->request->all();
        if(isset($data['buscar'])){
            $consulta = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Usuario')->createQueryBuilder('e');
            $consulta->where('e.nombre LIKE :nombre')->setParameter('nombre', '%'.$data["buscar"].'%');
            $consulta->orWhere('e.usuario LIKE :usuario')->setParameter('usuario', '%'.$data["buscar"].'%');
            $consulta->orWhere('e.email LIKE :email')->setParameter('email', '%'.$data["buscar"].'%');
        }else{
            $consulta = $em->getRepository('CpdgAdministradorBundle:Usuario')->findAll();
        }        
        /* Modal crear nuevo */
        $contacto = new Usuario();
        $formnew = $this->createForm(new UsuarioType(), $contacto);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );
        


        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(0,$userid, "Consulta lista de Usuarios");

        //dump($convenio);exit;

        return $this->render('CpdgAdministradorBundle:usuario:index.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,            
            'formNew' => $formnew->createView(),
            'convenio' => $convenio
            ));
    }

    /**
     * Creates a new Contactos entity.
     *
     */
    public function newAction(Request $request)
    {
        $usuario = new Usuario();
        $form = $this->createForm(new UsuarioType(), $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try{

                $session = $request->getSession();
                $admin = $session->get('admin');

                $convenio = $request->get('usuario')['convenio'];

                if ($admin->getConvenio()) {

                    $convenio = ($convenio)?$convenio:0;

                } else {    
                    $convenio = 0;
                }

                $usuario->setConvenio($convenio);

                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $em->flush();

                $this->addFlash('success', "Creado correctamente");
                $data = $request->request->all();
                $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
                $userid=$useridObj->getId();
                $this->processLogAction(0,$userid, "Crea nuevo usuario id: ".$usuario->getId()." Usuario: ".$usuario->getUsuario());

            } catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('danger', "Error: No se puede crear el registro, Usuario ya ingresado en el sistema");
            }            
            return $this->redirectToRoute('usuario_index', array('page' => '1'));
        }
        return $this->redirectToRoute('usuario_index', array('page' => '1'));;
    }
    

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
    public function editAction(Request $request, Usuario $usuario)
    {

        $session = $request->getSession();
        $convenio = $session->get('admin')->getConvenio();

        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createForm(new UsuarioType(), $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $session = $request->getSession();
            $admin = $session->get('admin');

            $convenio = $usuario->getConvenio();

            if ($admin->getConvenio()) {

                $convenio = ($convenio)?$convenio:0;

            } else {    
                $convenio = 0;
            }

            $usuario->setConvenio($convenio);

            $em->persist($usuario);
            $em->flush();
            $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
            $userid=$useridObj->getId();
            $this->processLogAction(1,$userid, "Edita usuario id: ".$usuario->getId()." Nombre: ".$usuario->getNombre());
            return $this->redirectToRoute('usuario_index', array('page' => '1'));
        }

        return $this->render('CpdgAdministradorBundle:usuario:edit.html.twig', array(
            'usuario' => $usuario,
            'edit_form' => $editForm->createView(),
            'convenio' => $convenio
        ));
    }

    /**
     * Deletes a Usuario entity.
     *
     */
    public function deleteAction(Request $request, Usuario $usuario)
    {
        $form = $this->createDeleteForm($usuario);
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        try {
          
            $em = $this->getDoctrine()->getManager();
            
            $this->addFlash('success', "Usuario eliminado correctamente.");
            $em->remove($usuario);
            $em->flush();

        } catch (\Exception $e) {
            $this->addFlash('danger', "Error: No se puede eliminar el usuario debido a que tiene historial en el sistema.");
        }

        //}

        return $this->redirectToRoute('usuario_index');
    }

    /**
     * Creates a form to delete a Usuario entity.
     *
     * @param Usuario $usuario The Usuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usuario $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $usuario->getId())))
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
