<?php
namespace Cpdg\AdministradorBundle\Listener;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Cpdg\AdministradorBundle\Entity\Administrador;

/**
 * Custom authentication success handler
 */
class LoginListener implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface {
    private $container;
    private $em;
    /**
     * Constructor
     * @param container   $container
     */
    public function __construct($container){
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }
    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from AbstractAuthenticationListener.
     * @param Request        $request
     * @param TokenInterface $token
     * @return Response The response to return
     */
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {       
        
        $admin = $token->getUser();
        $session = $request->getSession();
        $session->set('admin', $admin);


        $uri = $this->container->get('router')->generate('administrador_inicio');
        return new RedirectResponse($uri);
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {   
      
        $datos = $request->request->all();
        $user = $datos["_username"];
        $uri = $this->container->get('router')->generate('administrador_inicio_login_error', array('u'=> $user));
        $response = new RedirectResponse($uri);
        return $response;
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        if ($this->container->get('security.context')->getToken())
        {
            $usuario = $this->container->get('security.context')->getToken()->getUser();
            /*$log = $this->container->get('Util');
            $log->registralog('(PRPROV2) Sesión de usuario cerrada', $usuario->getId());*/
        }

        $uri = $this->container->get('router')->generate('administrador_inicio_login');
        $response = new RedirectResponse($uri);
        return $response;
    }

}
