<?php

namespace Cpdg\UsuarioBundle\Controller;

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
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Cpdg\UsuarioBundle\Entity\Contactos;
use Cpdg\UsuarioBundle\Form\ContactosType;
use Cpdg\UsuarioBundle\Form\ContactosDepartamentoType;
use Cpdg\UsuarioBundle\Form\ContactosFindType;
use Cpdg\UsuarioBundle\Entity\Logs;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Cpdg\UsuarioBundle\Entity\Archivos;
use Cpdg\UsuarioBundle\Form\ArchivosType;
use Cpdg\UsuarioBundle\Entity\Cargos;
use Cpdg\UsuarioBundle\Entity\Proveedores;

/**
 * Contactos controller.
 *
 */
class ContactosController extends Controller
{
    private $cantidadPorPagina = 20;
      
    public function indexAction($page, $ordervar, $ordervaro, Request $request)
    {        
       
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();

        if($this->get('request')->query->get('ordervar', $ordervar)){
            $_ordervar = $this->get('request')->query->get('ordervar', $ordervar);
        }else{
            $_ordervar = "p.nombre";
        }
        $_ordervaro = $this->get('request')->query->get('ordervaro', $ordervaro);
        $parametros = array();
        if(isset($data['contactos_find'])){
            $consulta = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');

            if($data['contactos_find']['idProveedor'] != ""){
                $consulta->andWhere('p.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['contactos_find']['idProveedor'].'%');  
                $parametros['p.nombre']['variable'] = 'nombreProv';
                $parametros['p.nombre']['filtro'] = 'LIKE';
                $parametros['p.nombre']['valor'] = '%'.$data['contactos_find']['idProveedor'].'%';  
            } 
            

            if($data['contactos_find']['idCargo'] != ""){
                $consulta->andWhere('c.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['contactos_find']['idCargo'].'%');
                $parametros['c.nombre']['variable'] = 'cargoNombre';
                $parametros['c.nombre']['filtro'] = 'LIKE';
                $parametros['c.nombre']['valor'] = '%'.$data['contactos_find']['idCargo'].'%';
            } 
            

           /* if($data['contactos_find']['nit'] != "") 
            $consulta->orWhere('e.nit LIKE :nit')->setParameter('nit', '%'.$data['contactos_find']['nit'].'%'); */

            if($data['contactos_find']['ciudad'] != ""){
                $consulta->andWhere('e.ciudad LIKE :ciudad')->setParameter('ciudad', '%'.$data['contactos_find']['ciudad'].'%');
                $parametros['e.ciudad']['variable'] = 'ciudad';
                $parametros['e.ciudad']['filtro'] = 'LIKE';
                $parametros['e.ciudad']['valor'] = '%'.$data['contactos_find']['ciudad'].'%';
            } 
            

            if($data['contactos_find']['email'] != ""){
                $consulta->andWhere('e.email LIKE :email')->setParameter('email', '%'.$data['contactos_find']['email'].'%');
                $parametros['e.email']['variable'] = 'email';
                $parametros['e.email']['filtro'] = 'LIKE';
                $parametros['e.email']['valor'] = '%'.$data['contactos_find']['email'].'%';
            } 
            

            if($data['contactos_find']['telefono'] != ""){
                $consulta->andWhere('e.telefono LIKE :telefono')->setParameter('telefono', '%'.$data['contactos_find']['telefono'].'%');
                $parametros['e.telefono']['variable'] = 'telefono';
                $parametros['e.telefono']['filtro'] = 'LIKE';
                $parametros['e.telefono']['valor'] = '%'.$data['contactos_find']['telefono'].'%';
            } 
            

            if($data['contactos_find']['movil'] != ""){
                $consulta->andWhere('e.movil LIKE :movil')->setParameter('movil', '%'.$data['contactos_find']['movil'].'%');
                $parametros['e.movil']['variable'] = 'movil';
                $parametros['e.movil']['filtro'] = 'LIKE';
                $parametros['e.movil']['valor'] = '%'.$data['contactos_find']['movil'].'%';
            } 
            

            if($data['contactos_find']['nombreContacto'] != ""){
                $consulta->andWhere('e.nombreContacto LIKE :nombreContacto')->setParameter('nombreContacto', '%'.$data['contactos_find']['nombreContacto'].'%');
                $parametros['e.nombreContacto']['variable'] = 'nombreContacto';
                $parametros['e.nombreContacto']['filtro'] = 'LIKE';
                $parametros['e.nombreContacto']['valor'] = '%'.$data['contactos_find']['nombreContacto'].'%';
            } 
            

            if($data['convenioSeleccionado'] != "sinSeleccionar"){
                $consulta->andWhere('p.convenio = :convenio')->setParameter('convenio', $data['convenioSeleccionado']);
                $parametros['p.convenio']['variable'] = 'convenio';
                $parametros['p.convenio']['filtro'] = '\=';
                $parametros['p.convenio']['valor'] = $data['convenioSeleccionado'];
            }

            if($data['activacionConvenio'] != ""){
                $consulta->andWhere('p.fechaInicio '.$data['filtroFecha'].' :fechaInicio')->setParameter('fechaInicio', $data['activacionConvenio']);
                $parametros['p.fechaInicio']['variable'] = 'convenio';
                $parametros['p.fechaInicio']['filtro'] = $data['filtroFecha'];
                $parametros['p.fechaInicio']['valor'] = $data['activacionConvenio'];
            }

            $consulta->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');
            $consulta->leftJoin('CpdgUsuarioBundle:Areas', 'a', 'WITH', 'a.id = e.idArea');
            $consulta->leftJoin('CpdgUsuarioBundle:Cargos', 'c', 'WITH', 'c.id = e.idCargo');
            $consulta->orderBy($_ordervar, $_ordervaro);
            $page = 1;

        }else{
            $consulta = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');
            $consulta->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');
            $consulta->leftJoin('CpdgUsuarioBundle:Areas', 'a', 'WITH', 'a.id = e.idArea');
            $consulta->leftJoin('CpdgUsuarioBundle:Cargos', 'c', 'WITH', 'c.id = e.idCargo');
            $consulta->orderBy($_ordervar, $_ordervaro);
        }        
        /* Modal crear nuevo */
        $contactoFind = new Contactos();
        $formFind = $this->createForm(new ContactosFindType(), $contactoFind);

        $archiv = new Archivos();
        $formArchivo = $this->createForm(ArchivosType::class, $archiv);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );
               
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(1,$userid, "Consulta lista de contactos");

        return $this->render('CpdgUsuarioBundle:contactos:index.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,            
            'formFind' => $formFind->createView(),
            'formArchivo' => $formArchivo->createView(),
            'ordervar' => $_ordervar,
             'ordervaro' => $_ordervaro,
             'parametros' => $parametros
            ));
    }

    public function departamentoAction($page, $ordervar, $ordervaro, Request $request)
    {    
        
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $userIdArea=$useridObj->getIdArea();

        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $parametros = array();


        if($this->get('request')->query->get('ordervar', $ordervar)){
            $_ordervar = $this->get('request')->query->get('ordervar', $ordervar);
        }else{
            $_ordervar = "p.nombre";
        }
        $_ordervaro = $this->get('request')->query->get('ordervaro', $ordervaro);
        

        if(isset($data['contactos_find'])) {

            $consulta = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');
            $consulta->where('e.idArea = :idArea')->setParameter('idArea', $userIdArea); 

            // if($data['contactos_find']['idProveedor'] != "") 
            // $consulta->andWhere('p.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['contactos_find']['idProveedor'].'%');

            if($data['contactos_find']['idProveedor'] != "") {

                $consulta->andWhere('p.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['contactos_find']['idProveedor'].'%');  
                $parametros['p.nombre']['variable'] = 'nombre';
                $parametros['p.nombre']['filtro'] = 'LIKE';
                $parametros['p.nombre']['valor'] = '%'.$data['contactos_find']['idProveedor'].'%';

            } 



            if($data['contactos_find']['idCargo'] != "") {

                $consulta->andWhere('c.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['contactos_find']['idCargo'].'%');
                $parametros['c.nombre']['variable'] = 'nombre';
                $parametros['c.nombre']['filtro'] = 'LIKE';
                $parametros['c.nombre']['valor'] = '%'.$data['contactos_find']['nombre'].'%';

            }

           /* if($data['contactos_find']['nit'] != "") 
            $consulta->orWhere('e.nit LIKE :nit')->setParameter('nit', '%'.$data['contactos_find']['nit'].'%'); */

            if($data['contactos_find']['ciudad'] != "") {

                $consulta->andWhere('e.ciudad LIKE :ciudad')->setParameter('ciudad', '%'.$data['contactos_find']['ciudad'].'%');
                $parametros['e.ciudad']['variable'] = 'ciudad';
                $parametros['e.ciudad']['filtro'] = 'LIKE';
                $parametros['e.ciudad']['valor'] = '%'.$data['contactos_find']['ciudad'].'%';
                                    

            }

            if($data['contactos_find']['email'] != "") {

                $consulta->andWhere('e.email LIKE :email')->setParameter('email', '%'.$data['contactos_find']['email'].'%');
                $parametros['e.email']['variable'] = 'email';
                $parametros['e.email']['filtro'] = 'LIKE';
                $parametros['e.email']['valor'] = '%'.$data['contactos_find']['email'].'%';

            }

            if($data['contactos_find']['telefono'] != "") {

                $consulta->andWhere('e.telefono LIKE :telefono')->setParameter('telefono', '%'.$data['contactos_find']['telefono'].'%');
                $parametros['e.telefono']['variable'] = 'telefono';
                $parametros['e.telefono']['filtro'] = 'LIKE';
                $parametros['e.telefono']['valor'] = '%'.$data['contactos_find']['telefono'].'%';

            }

            if($data['contactos_find']['movil'] != "") {

                $consulta->andWhere('e.movil LIKE :movil')->setParameter('movil', '%'.$data['contactos_find']['movil'].'%');
                $parametros['e.movil']['variable'] = 'movil';
                $parametros['e.movil']['filtro'] = 'LIKE';
                $parametros['e.movil']['valor'] = '%'.$data['contactos_find']['movil'].'%';

            }


            if($data['contactos_find']['nombreContacto'] != "") 
            $consulta->andWhere('e.nombreContacto LIKE :nombreContacto')->setParameter('nombreContacto', '%'.$data['contactos_find']['nombreContacto'].'%');

            $consulta->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');
            $consulta->leftJoin('CpdgUsuarioBundle:Areas', 'a', 'WITH', 'a.id = e.idArea');
            $consulta->leftJoin('CpdgUsuarioBundle:Cargos', 'c', 'WITH', 'c.id = e.idCargo');
            $consulta->orderBy($_ordervar, $_ordervaro);
            $page = 1;
        }else{
            $consulta = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');
            $consulta->where('e.idArea = :idArea')->setParameter('idArea', $userIdArea);
            $consulta->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');
            $consulta->leftJoin('CpdgUsuarioBundle:Areas', 'a', 'WITH', 'a.id = e.idArea');
            $consulta->leftJoin('CpdgUsuarioBundle:Cargos', 'c', 'WITH', 'c.id = e.idCargo');
            $consulta->orderBy($_ordervar, $_ordervaro);
        }        
        /* Modal crear nuevo */
        $contacto = new Contactos();
        $formnew = $this->createForm(new ContactosDepartamentoType(), $contacto);

        $contactoFind = new Contactos();
        $formFind = $this->createForm(new ContactosFindType(), $contactoFind);

        $archiv = new Archivos();
        $formArchivo = $this->createForm(ArchivosType::class, $archiv);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );
                
        $this->processLogAction(1,$userid, "Consulta lista de contactos del departamento id: ".$this->get('request')->query->get('d'));
       
        return $this->render('CpdgUsuarioBundle:contactos:index_departamentos.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,
            'formNew' => $formnew->createView(),
            'formFind' => $formFind->createView(),
             'formArchivo' => $formArchivo->createView(),
             'ordervar' => $_ordervar,
             'ordervaro' => $_ordervaro,
             'parametros' => $parametros
             
        ));
    }


    /**
     * Creates a new Contactos entity.
     *
     */
    public function newAction(Request $request)
    {
        $contacto = new Contactos();
        $form = $this->createForm(new ContactosType(), $contacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($insert);
                $em->flush();
                $this->addFlash('success', "Creado correctamente");
            }catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('danger', "Error: No se puede crear el registro, NIT ya ingresado en el sistema");
            }*/

            $em = $this->getDoctrine()->getManager();
            $contacto->setFechaCreacion(new \DateTime(date("Y-m-d H:i:s")));
            $em->persist($contacto);
            $em->flush();

            $data = $request->request->all();
            $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
            $userid=$useridObj->getId();
            $this->processLogAction(1,$userid, "Crea nuevo contacto id: ".$contacto->getId()." Nombre: ".$contacto->getNombreContacto());
            return $this->redirectToRoute('contactosusr_index', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
        }

        return $this->render('contactos/new.html.twig', array(
            'contacto' => $contacto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Contactos entity.
     *
     */
    public function newfromdepartamentoAction(Request $request)
    {
        $contacto = new Contactos();
        $form = $this->createForm(new ContactosDepartamentoType(), $contacto);
        $form->handleRequest($request);
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            try{                
                $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
                $userid=$useridObj->getId();
                $userIdArea=$useridObj->getIdArea();
                $contacto->setIdArea($userIdArea);
                $contacto->setFechaCreacion(new \DateTime(date("Y-m-d H:i:s")));
                $em->persist($contacto);
                $em->flush();

                
                $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
                $userid=$useridObj->getId();
                $this->processLogAction(1,$userid, "Crea nuevo contacto id: ".$contacto->getId()." Nombre: ".$contacto->getNombreContacto());
                $this->addFlash('success', "Creado correctamente");  
            }catch(\Doctrine\DBAL\DBALException $e) {

                $consulta = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');
                $consulta->where('e.email = :email')->setParameter('email', $data['contactos_departamento']['email']); 
                $inicount = $consulta->getQuery()->execute();    
                foreach($inicount as $repoc){ 
                    $contactoId = $repoc->getId(); 
                    $contactoProveedor = $repoc->getIdProveedor()->getNombre(); 
                    $contactoArea = $repoc->getIdArea()->getNombre(); 
                }

                $this->addFlash('danger', "Error: No se puede crear el registro, el email ".$data['contactos_departamento']['email']." ya ha sido ingresado en el sistema, en el proveedor: " .$contactoProveedor. ", del departamento: ".$contactoArea);
            }
            

            return $this->redirectToRoute('contactosusr_departamento', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
        }

        return $this->redirectToRoute('contactosusr_departamento', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
    }


    /**
     * Displays a form to edit an existing Contactos entity.
     *
     */
    public function editAction(Request $request, Contactos $contacto)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $areas = $em->getRepository('CpdgUsuarioBundle:Areas')->findAll();
        $editForm = $this->createForm(new ContactosType(), $contacto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($contacto);
            $em->flush();
            $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
            $userid=$useridObj->getId();
            $this->processLogAction(1,$userid, "Edita contacto id: ".$contacto->getId()." Nombre: ".$contacto->getNombreContacto());
            return $this->redirectToRoute('contactosusr_index', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
        }

        return $this->render('CpdgUsuarioBundle:contactos:edit.html.twig', array(
            'contacto' => $contacto,
            'edit_form' => $editForm->createView(),
            'areas'=>$areas,
        ));
    }

    /**
     * Displays a form to edit an existing Contactos entity.
     *
     */
    public function editfromdepartamentoAction(Request $request, Contactos $contacto)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $areas = $em->getRepository('CpdgUsuarioBundle:Areas')->findAll();
        $editForm = $this->createForm(new ContactosDepartamentoType(), $contacto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($contacto);
            $em->flush();
            $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
            $userid=$useridObj->getId();
            $this->processLogAction(1,$userid, "Edita contacto id: ".$contacto->getId()." Nombre: ".$contacto->getNombreContacto());
             $idArea = $contacto->getIdArea();
             return $this->redirectToRoute('contactosusr_departamento', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc', 'd' => $idArea->getId()));
        }

        return $this->render('CpdgUsuarioBundle:contactos:edit_departamentos.html.twig', array(
            'contacto' => $contacto,
            'edit_form' => $editForm->createView(),
            'areas'=>$areas,
        ));
    }

     /**
     * Deletes a Contactos entity.
     *
     */
    public function deleteAction(Request $request, Contactos $contacto)
    {
        $data = $request->request->all();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(1,$userid, "Elimina contacto id: ".$contacto->getId()." Nombre: ".$contacto->getNombreContacto());

        $this->addFlash('success', 'Eliminado correctamente');
        $em = $this->getDoctrine()->getManager();
        $em->remove($contacto);
        $em->flush();
        
        return $this->redirectToRoute('contactosusr_index', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
    }
    /**
     * Deletes a Proveedores entity.
     *
     */
    public function deletefromdepartamentoAction(Request $request, Contactos $contacto)
    {
        $data = $request->request->all();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $this->processLogAction(1,$userid, "Elimina contacto id: ".$contacto->getId()." Nombre: ".$contacto->getNombreContacto());

        $this->addFlash('success', 'Eliminado correctamente');
        $em = $this->getDoctrine()->getManager();
        $em->remove($contacto);
        $em->flush();
        
        //return $this->redirectToRoute('contactosusr_index', array('page' => '1'));
        $idArea = $contacto->getIdArea();
        return $this->redirectToRoute('contactosusr_departamento', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc', 'd' => $idArea->getId()));
    }
    
    /**
     * @Route("/exportar/excel", name="exportar_excel")
     */
    public function exportarExcelAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $userIdArea=$useridObj->getIdArea();
        
        // recuperamos los elementos de base de datos que queremos exportar
        // $query = $em->getRepository('CpdgUsuarioBundle:Contactos')
        //     ->createQueryBuilder('e')
        //     ->where('e.idArea = :idArea')->setParameter('idArea', $userIdArea)
        //     ->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor')
        //     ->getQuery();

        // $result = $query->getResult();


        $parametros = $request->get('parametros');

        // recuperamos los elementos de base de datos que queremos exportar
        $query = $em->getRepository('CpdgUsuarioBundle:Contactos')
            ->createQueryBuilder('e')
            ->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');
            //->getQuery();

        if($parametros){
            foreach($parametros as $key => $parametro){
                $query->andWhere($key.' '.str_replace("\\", "", $parametro['filtro']).' :'.$parametro['variable'])->setParameter($parametro['variable'], $parametro['valor']);
            }
        }
        

        $query = $query->getQuery();
        $result = $query->getResult();




        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Coopidrogas")
            ->setLastModifiedBy("Coopidrogas")
            ->setTitle("Contactos")
            ->setSubject("Coopidrogas")
            ->setDescription("Lista de contactos")
            ->setKeywords("Coopidrogas");

        // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Coopidrogas Contactos Proveedores por Departamento');
        
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Empresa / Razón Social')
            ->setCellValue('B2', 'Nit')
            ->setCellValue('C2', 'Código')
            ->setCellValue('D2', 'Representante Legal')
            ->setCellValue('E2', 'Representante Legal Email')
            ->setCellValue('F2', 'Representante Legal Teléfono')
            ->setCellValue('G2', 'Nombre Contacto')
            ->setCellValue('H2', 'Rol')
            ->setCellValue('I2', 'Ciudad')
            ->setCellValue('J2', 'Email')
            ->setCellValue('K2', 'Telefono')
            ->setCellValue('L2', 'Movil')
            ->setCellValue('M2', 'Departamento')
            ;

        // fijamos un ancho a las distintas columnas
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('A')
            ->setWidth(50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(25);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('G')
            ->setWidth(50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('J')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('K')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('L')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('M')
            ->setWidth(20);


        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        $row = 3;
        foreach ($result as $item) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$row, $item->getIdProveedor()->getNombre())
                ->setCellValue('B'.$row, $item->getIdProveedor()->getNit())
                ->setCellValue('C'.$row, $item->getIdProveedor()->getCodigo())
                ->setCellValue('D'.$row, $item->getIdProveedor()->getRepresentanteLegal())
                ->setCellValue('E'.$row, $item->getIdProveedor()->getEmailRepresentanteLegal())
                ->setCellValue('F'.$row, $item->getIdProveedor()->getTelefonoRepresentanteLegal())
                ->setCellValue('G'.$row, $item->getNombreContacto())
                ->setCellValue('H'.$row, $item->getIdCargo()->getNombre())
                ->setCellValue('I'.$row, $item->getCiudad())
                ->setCellValue('J'.$row, $item->getEmail())
                ->setCellValue('K'.$row, $item->getTelefono())
                ->setCellValue('L'.$row, $item->getMovil())
                ->setCellValue('M'.$row, $item->getIdArea()->getNombre())
                ;
            $row++;
        }

        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'contactosPorDepartamento.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @Route("/exportar/excel", name="exportar_excel_todos")
     */
    public function exportarExcelTodosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $userIdArea=$useridObj->getIdArea();
        $parametros = $request->get('parametros');

        // recuperamos los elementos de base de datos que queremos exportar
        $query = $em->getRepository('CpdgUsuarioBundle:Contactos')
            ->createQueryBuilder('e')
            ->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');
            //->getQuery();

        if($parametros){
            foreach($parametros as $key => $parametro){
                $query->andWhere($key.' '.str_replace("\\", "", $parametro['filtro']).' :'.$parametro['variable'])->setParameter($parametro['variable'], $parametro['valor']);
            }
        }
        

        $query = $query->getQuery();
        $result = $query->getResult();

        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Coopidrogas")
            ->setLastModifiedBy("Coopidrogas")
            ->setTitle("Contactos")
            ->setSubject("Coopidrogas")
            ->setDescription("Lista de contactos")
            ->setKeywords("Coopidrogas");

        // establecemos como hoja activa la primera, y le asignamos un título
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Coopidrogas Contactos Proveedores Nacionales');
        
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Empresa / Razón Social')
            ->setCellValue('B2', 'Nit')
            ->setCellValue('C2', 'Código')
            ->setCellValue('D2', 'Representante Legal')
            ->setCellValue('E2', 'Representante Legal Email')
            ->setCellValue('F2', 'Representante Legal Teléfono')
            ->setCellValue('G2', 'Nombre Contacto')
            ->setCellValue('H2', 'Rol')
            ->setCellValue('I2', 'Ciudad')
            ->setCellValue('J2', 'Email')
            ->setCellValue('K2', 'Telefono')
            ->setCellValue('L2', 'Movil')
            ->setCellValue('M2', 'Departamento')
            ->setCellValue('N2', 'Convenio')
            ->setCellValue('O2', 'Fecha Inicio')
            ->setCellValue('P2', 'Fecha Final')
            ;

        // fijamos un ancho a las distintas columnas
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('A')
            ->setWidth(50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(25);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('G')
            ->setWidth(50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('J')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('K')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('L')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('M')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('N')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('O')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('P')
            ->setWidth(20);


        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        $row = 3;
        foreach ($result as $item) {
            if($item->getIdProveedor()->getConvenio() == 1){
                $convenio = "Activo";
                $fechaInicio = $item->getIdProveedor()->getFechaInicio()->format('Y-m-d');
                $fechaFin = $item->getIdProveedor()->getFechaFin()->format('Y-m-d');
            }else{
                $convenio = "Inactivo";
                $fechaInicio = "";
                $fechaFin = "";
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$row, $item->getIdProveedor()->getNombre())
                ->setCellValue('B'.$row, $item->getIdProveedor()->getNit())
                ->setCellValue('C'.$row, $item->getIdProveedor()->getCodigo())
                ->setCellValue('D'.$row, $item->getIdProveedor()->getRepresentanteLegal())
                ->setCellValue('E'.$row, $item->getIdProveedor()->getEmailRepresentanteLegal())
                ->setCellValue('F'.$row, $item->getIdProveedor()->getTelefonoRepresentanteLegal())
                ->setCellValue('G'.$row, $item->getNombreContacto())
                ->setCellValue('H'.$row, $item->getIdCargo()->getNombre())
                ->setCellValue('I'.$row, $item->getCiudad())
                ->setCellValue('J'.$row, $item->getEmail())
                ->setCellValue('K'.$row, $item->getTelefono())
                ->setCellValue('L'.$row, $item->getMovil())
                ->setCellValue('M'.$row, $item->getIdArea()->getNombre())
                ->setCellValue('N'.$row, $convenio)
                ->setCellValue('O'.$row, $fechaInicio)
                ->setCellValue('P'.$row, $fechaFin)
                ;
            $row++;
        }

        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'contactos.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    public function downloadExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();

        // solicitamos el servicio 'phpexcel' y creamos el objeto vacío...
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        // ...y le asignamos una serie de propiedades
        $phpExcelObject->getProperties()
            ->setCreator("Coopidrogas")
            ->setLastModifiedBy("Coopidrogas")
            ->setTitle("Contactos")
            ->setSubject("Coopidrogas")
            ->setDescription("Lista de contactos")
            ->setKeywords("Coopidrogas");
        
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Empresa / Razón Social')
            ->setCellValue('B1', 'Nit')
            ->setCellValue('C1', 'Código')
            ->setCellValue('D1', 'Representante Legal')
            ->setCellValue('E1', 'Representante Legal Email')
            ->setCellValue('F1', 'Representante Legal Teléfono')
            ->setCellValue('G1', 'Nombre Contacto')
            ->setCellValue('H1', 'Rol - El rol va identificado por el numero del inicio')
            ->setCellValue('I1', 'Ciudad')
            ->setCellValue('J1', 'Email')
            ->setCellValue('K1', 'Telefono')
            ->setCellValue('L1', 'Movil')
            ;

        // fijamos un ancho a las distintas columnas
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('A')
            ->setWidth(50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('B')
            ->setWidth(25);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('C')
            ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('D')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('E')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('F')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('G')
            ->setWidth(50);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(60);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('J')
            ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('K')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('L')
            ->setWidth(20);
            
        // recuperamos los elementos de base de datos de los cargos
        $query = $em->getRepository('CpdgUsuarioBundle:Cargos')
            ->createQueryBuilder('e')
            ->getQuery();

        $result = $query->getResult();
        $row = 3;
        foreach ($result as $item) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('H'.$row, $item->getId()." - ".$item->getNombre())
                ;
            $row++;
        }
        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'FormatoCargaContactos.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    public function loadFileAction(Request $request){
        $archivo = new Archivos();
        $form = $this->createForm(ArchivosType::class, $archivo);
        $form->handleRequest($request);

        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
        $userIdArea=$useridObj->getIdArea();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // $file stores the uploaded xls file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            

            $file = $archivo->getNombre();

            $fileName = md5(uniqid()).'.xls';

            $ArchivoDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
            $file->move($ArchivoDir, $fileName);

            $archivo->setNombre($fileName);
            $archivo->setFecha(new \DateTime(date("Y-m-d H:i:s")));

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $file = $this->get('kernel')->getRootDir()."/../web/uploads/".$fileName;
            if (!file_exists($file)) {
                $this->addFlash('error', 'Archivo no cargado contactese con el administrador del sistema'); 
            }
            $objPHPExcel = \PHPExcel_IOFactory::load($file);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $retVar = "";
            $retVarError = "";
            $dataInsert = array();
            $boolAlguno = false;

            $dataProveedores = $em->getRepository('CpdgUsuarioBundle:Proveedores')->findAll();
            $proveedoresRegistrados = array();
            foreach ($dataProveedores as $proveedor) 
                $proveedoresRegistrados[$proveedor->getNit()] = $proveedor;
            unset($dataProveedores, $proveedor);
            
            $dataContactos= $em->getRepository('CpdgUsuarioBundle:Contactos')->findAll();
            $contactosRegistrados = array();
            foreach ($dataContactos as $contacto) 
                $contactosRegistrados[$contacto->getEmail()] = $contacto;
            unset($dataContactos, $contacto);

            $proveedoresInssertados = $contactosInsertados = array();
            $proveedorid = null;

            //dump($contactosRegistrados['ventasfarpag@yahoo.com']);exit;
            foreach ($sheetData as $n_fila=>$data) {

                if ($n_fila > 1) {

                    unset($dataInsert);
                    $dataInsert = array();
                    $boolInsert = false;
                    $boolInsertProveedor = false;

                    if (!is_null($data)) {

                        $nit = str_replace(' ', '', $data['B']);
                        //$email = str_replace(' ', '', strtolower($data['J']));
                        $email = $data['J'];

                        if ((count($proveedoresRegistrados) == 0 || ! array_key_exists($nit,$proveedoresRegistrados)) && ! array_key_exists($nit,$proveedoresInssertados))
                            $boolInsertProveedor = true;

                        //if ((count($contactosRegistrados) == 0 || ! array_key_exists($email,$contactosRegistrados)) && ! array_key_exists($email,$contactosInsertados))
                        //   $boolInsert = true;

                        /*
                            $proveedor = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Proveedores')->createQueryBuilder('e');
                            $proveedor->where('e.nit = :nit')->setParameter('nit', intval($data['B'])); 
                            $inicount = $proveedor->getQuery()->execute();
                            $total = -1;      
                            foreach($inicount as $repoc){ $total ++; $proveedorid = $repoc->getId(); } 
                            if($total == 0){
                                $boolInsertProveedor = false;
                            }else{
                               $boolInsertProveedor = true;
                            }
                        
                            $proveedor = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');
                            $proveedor->where('e.email = :email')->setParameter('email', $data['J']); 
                            $inicount = $proveedor->getQuery()->execute();
                            $total = -1;      
                            foreach($inicount as $repoc){ $total ++; } 
                            if($total == 0){
                               $boolInsert = false;
                            }else{
                               $boolInsert = true;
                            }
                        */

                        $proveedor = $em->createQueryBuilder()
                                        ->select(' count(c.id) as contador ')
                                        ->from('CpdgUsuarioBundle:Contactos', 'c')
                                        ->where('c.email = :email')
                                        ->setParameter('email', $email)
                                        ->getQuery()->getSingleResult();

                        if ($proveedor['contador'] == 0 && ! array_key_exists($email,$contactosInsertados)) 
                            $boolInsertProveedor = true;

                    

                        if ($boolInsertProveedor == true) {

                            $retVar .= ' Insertado Proveedor: '.$data['B'];
                            $insert = new Proveedores();

                            if($data['A'] == NULL) $data['A'] = "-"; else $data['A'];
                            if($data['C'] == NULL) $data['C'] = "-"; else $data['C'];
                            if($data['D'] == NULL) $data['D'] = "-"; else $data['D'];
                            if($data['E'] == NULL) $data['E'] = "-"; else $data['E'];
                            if($data['F'] == NULL) $data['F'] = "-"; else $data['F'];

                            try {                                    
                                
                                $insert->setNit(intval($data['B']));
                                $insert->setNombre($data['A']);
                                $insert->setCodigo($data['C']);
                                $insert->setRepresentanteLegal($data['D']);
                                $insert->setEmailRepresentanteLegal($data['E']);
                                $insert->setTelefonoRepresentanteLegal($data['F']);
                                $insert->setFechaUltimaModificacion(new \DateTime('now'));

                                $em->persist($insert);
                                $em->flush();
                                $proveedorid = $insert->getId();

                                $proveedoresInssertados[$insert->getNit()] = $insert;
                                //dump($insert->getNit());

                            } catch(\Doctrine\DBAL\DBALException $e) {
                                
                                $retVarError .= ' Registro: '.$data['B'].' No se pudo crear.';
                            }
                        }

                        if ($boolInsert == true) {
            
                            $retVar .= ' Insertado Contacto: '.$data['J'];
                            $insertc = new Contactos();
                            if($data['G'] == NULL) $data['G'] = "-"; else $data['G'];
                            if($data['H'] == NULL) $data['H'] = "-"; else $data['H'];
                            if($data['I'] == NULL) $data['I'] = "-"; else $data['I'];
                            if($data['J'] == NULL) $data['J'] = "-"; else $data['J'];
                            if($data['K'] == NULL) $data['K'] = "-"; else $data['K'];
                            if($data['L'] == NULL) $data['L'] = "-"; else $data['L'];

                            try {                                   

                                if (is_null($proveedorid)) {

                                    if (isset($proveedoresRegistrados[$nit])) {

                                        $proveedorid = $proveedoresRegistrados[$nit]->getId();

                                    } else if (isset($proveedoresInssertados[$nit])) {

                                        $proveedorid = $proveedoresInssertados[$nit]->getId();

                                    }//end if                                    

                                }//end if

                                $proveedoridobj = $em->getReference('CpdgUsuarioBundle:Proveedores', $proveedorid);
                                $tmparea = $userIdArea;

                                $areaGetArea = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Areas')->createQueryBuilder('e');
                               $areaGetArea->where('e.id = :id')->setParameter('id', $userIdArea); 
                               $inicounta = $areaGetArea->getQuery()->execute();                                           
                               foreach($inicounta as $repoca){ /*$total ++; */$areaid = $repoca->getId(); }

                                $areaidobj = $em->getReference('CpdgUsuarioBundle:Areas', $areaid); 

                                $userIdCargoobj = $em->getReference('CpdgUsuarioBundle:Cargos', intval($data['H']));                               
                                $insertc->setIdProveedor($proveedoridobj);
                                $insertc->setNombreContacto($data['G']);
                                $insertc->setIdCargo($userIdCargoobj);
                                $insertc->setCiudad($data['I']);
                                $insertc->setEmail($data['J']);
                                $insertc->setTelefono($data['K']);
                                $insertc->setMovil($data['L']);
                                $insertc->setIdArea($areaidobj);
                                $insertc->setFechaCreacion(new \DateTime(date("Y-m-d H:i:s")));
                                
                                $em->persist($insertc);
                                $em->flush();

                                $boolAlguno = true;

                                $contactosInsertados[$insertc->getEmail()] = $insertc;

                                $proveedorid = null;

                            } catch(\Doctrine\DBAL\DBALException $e) {
                                dump($e->getMessage());exit;
                                $retVarError .= ' Registro: '.$data['B'].' No se pudo crear.';
                            }
                        }//end if

                    }
                }
            }  

            if($boolAlguno == true){
                $this->addFlash('success', 'Solo se ingresan los contactos nuevos que no se encuentren registrados en el sistema usando como identificador único el e-mail. '.$retVar);
            }else{
               $this->addFlash('danger', 'Todos los usuarios que intenta ingresar ya se encuentran registrados en el sistema'); 
            }
                        
            return $this->redirectToRoute('contactosusr_departamento', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
        }

        return $this->render('product/new.html.twig', array(
            'form' => $form->createView(),
        ));
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
