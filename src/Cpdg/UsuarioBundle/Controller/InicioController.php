<?php

namespace Cpdg\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cpdg\UsuarioBundle\Entity\Logs;
use Symfony\Component\HttpFoundation\RedirectResponse;

class InicioController extends Controller
{    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(1, $userid, "Inicio de Sesión");
        $areas = $em->getRepository('CpdgUsuarioBundle:Areas')->findAll();
        return $this->render('CpdgUsuarioBundle:inicio:index.html.twig', array('areas'=>$areas));
    }
    public function loginAction()
    {
        return $this->render('CpdgUsuarioBundle:inicio:login.html.twig');
    }
	public function logoutAction()
    {
    
    }
    public function logoutBridgeAction()
    {
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(1,$userid, "Finaliza la Sesión");
        $uri = $this->container->get('router')->generate('usuario_inicio_logout');
        return new RedirectResponse($uri);
    }
    public function loginErrorAction()
    {       
        if(isset($_GET['u'])){
            $user = $_GET['u'];
        }else{
            $user = "Anónimo";
        }        
        $this->processLogAction(1,$user, "Inicio de Sesión Fallido");
        $this->addFlash('danger', 'Usuario y/o Contraseña Invalidos, por favor intente nuevamente el ingreso.');
        return $this->render('CpdgUsuarioBundle:inicio:login.html.twig');
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
