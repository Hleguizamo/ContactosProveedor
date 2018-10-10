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

use Cpdg\UsuarioBundle\Entity\Proveedores;
use Cpdg\UsuarioBundle\Form\ProveedoresType;

/**
 * Proveedores controller.
 *
 */
class ProveedoresController extends Controller
{
    private $cantidadPorPagina = 20;
    /**
    * @Route("/", defaults={"page": 1}, name="proveedoresusr_index")
    * @Method("GET")
    * @Cache(smaxage="10")
    */    
    public function indexAction($page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $request->request->all();

        $session = $request->getSession();
        $convenio = $session->get('user')->getConvenio();

        $hayConvenioActivo = ($convenio==1)?true:false;
        $parametros = array();
        if (isset($data['find'])) {
            $consulta = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Proveedores')->createQueryBuilder('e');

            if($data['proveedores']['nit'] != ""){
                $consulta->where('e.nit LIKE :nit')->setParameter('nit', '%'.$data['proveedores']['nit'].'%'); 
                $parametros['e.nit']['variable'] = 'nit';
                $parametros['e.nit']['filtro'] = 'LIKE';
                $parametros['e.nit']['valor'] = '%'.$data['proveedores']['nit'].'%';   
            }
            

            if($data['proveedores']['nombre'] != ""){
                $consulta->orWhere('e.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['proveedores']['nombre'].'%');    
                $parametros['e.nombre']['variable'] = 'nombre';
                $parametros['e.nombre']['filtro'] = 'LIKE';
                $parametros['e.nombre']['valor'] = '%'.$data['proveedores']['nombre'].'%'; 
            }
            

            if($data['proveedores']['codigo'] != ""){
                $consulta->orWhere('e.codigo LIKE :codigo')->setParameter('codigo', '%'.$data['proveedores']['codigo'].'%');    
                $parametros['e.codigo']['variable'] = 'codigo';
                $parametros['e.codigo']['filtro'] = 'LIKE';
                $parametros['e.codigo']['valor'] = '%'.$data['proveedores']['codigo'].'%'; 
            }
            

            if($data['proveedores']['representanteLegal'] != ""){
                $consulta->orWhere('e.representanteLegal LIKE :representanteLegal')->setParameter('representanteLegal', '%'.$data['proveedores']['representanteLegal'].'%');    
                $parametros['e.representanteLegal']['variable'] = 'representanteLegal';
                $parametros['e.representanteLegal']['filtro'] = 'LIKE';
                $parametros['e.representanteLegal']['valor'] = '%'.$data['proveedores']['representanteLegal'].'%'; 
            }
            

            if($data['proveedores']['emailRepresentanteLegal'] != ""){
                $consulta->orWhere('e.emailRepresentanteLegal LIKE :emailRepresentanteLegal')->setParameter('emailRepresentanteLegal', '%'.$data['proveedores']['emailRepresentanteLegal'].'%');    
                $parametros['e.emailRepresentanteLegal']['variable'] = 'emailRepresentanteLegal';
                $parametros['e.emailRepresentanteLegal']['filtro'] = 'LIKE';
                $parametros['e.emailRepresentanteLegal']['valor'] = '%'.$data['proveedores']['emailRepresentanteLegal'].'%';
            }
            

            if($data['proveedores']['telefonoRepresentanteLegal'] != ""){
                $consulta->orWhere('e.telefonoRepresentanteLegal LIKE :telefonoRepresentanteLegal')->setParameter('telefonoRepresentanteLegal', '%'.$data['proveedores']['telefonoRepresentanteLegal'].'%');    
                $parametros['e.telefonoRepresentanteLegal']['variable'] = 'telefonoRepresentanteLegal';
                $parametros['e.telefonoRepresentanteLegal']['filtro'] = 'LIKE';
                $parametros['e.telefonoRepresentanteLegal']['valor'] = '%'.$data['proveedores']['telefonoRepresentanteLegal'].'%';
            }
            

            if($data['convenioSeleccionado'] != "sinSeleccionar"){
                $consulta->orWhere('e.convenio = :convenio')->setParameter('convenio', $data['convenioSeleccionado']);
                $parametros['e.convenio']['variable'] = 'convenio';
                $parametros['e.convenio']['filtro'] = '\=';
                $parametros['e.convenio']['valor'] = $data['convenioSeleccionado'];
            }

            if($data['activacionConvenio'] != ""){
                $consulta->orWhere('e.fechaInicio '.$data['filtroFecha'].' :fechaInicio')->setParameter('fechaInicio', $data['activacionConvenio']);
                $parametros['e.fechaInicio']['variable'] = 'convenio';
                $parametros['e.fechaInicio']['filtro'] = $data['filtroFecha'];
                $parametros['e.fechaInicio']['valor'] = $data['activacionConvenio'];
            } 
            //filtroFechaUltimaModificacion
            //fechaUltimaModificacion

            if($data['fechaUltimaModificacion'] != ""){

                $consulta->orWhere('e.fechaUltimaModificacion '.$data['filtroFechaUltimaModificacion'].' :fechaUltimaModificacion')->setParameter('fechaUltimaModificacion', $data['fechaUltimaModificacion']);

                $parametros['e.fechaUltimaModificacion']['variable'] = 'fechaUltimaModificacion';
                $parametros['e.fechaUltimaModificacion']['filtro'] = $data['filtroFechaUltimaModificacion'];
                $parametros['e.fechaUltimaModificacion']['valor'] = $data['fechaUltimaModificacion'];
                
            }


        }else{
            $consulta = $em->getRepository('CpdgUsuarioBundle:Proveedores')->findAll();
        }
        /* Modal crear nuevo */
        $proveeedor = new Proveedores();
        $formnew = $this->createForm(new ProveedoresType(), $proveeedor);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );
        $areas = $em->getRepository('CpdgUsuarioBundle:Areas')->findAll();
        return $this->render('CpdgUsuarioBundle:proveedores:index.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,
            'areas'=>$areas,
            'formNew' => $formnew->createView(),
            'hayConvenioActivo' => $hayConvenioActivo,
            'parametros' => $parametros
            ));
    }

    /**
     * Creates a new Proveedores entity.
     *
     */
    public function newAction(Request $request)
    {
        $insert = new Proveedores();
        $form = $this->createForm(new ProveedoresType(), $insert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$insert->setNit(4);
            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($insert);
                $em->flush();
                $this->addFlash('success', "Creado correctamente");
           } catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('danger', "Error: No se puede crear el registro, NIT ya ingresado en el sistema");
            }

            return $this->redirectToRoute('proveedoresusr_index', array('page' => '1'));
        }
        return $this->redirectToRoute('proveedoresusr_index', array('page' => '1'));
    }

    /**
     * Finds and displays a Proveedores entity.
     *
     */
    public function showAction(Proveedores $proveedore)
    {
        $deleteForm = $this->createDeleteForm($proveedore);

        return $this->render('proveedores/show.html.twig', array(
            'proveedore' => $proveedore,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Proveedores entity.
     *
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id');
        $proveedor = $em->getRepository('CpdgUsuarioBundle:Proveedores')->findOneById($id);

        $transaccionSipProveedores = $this->buscarEnSipProveedores($proveedor);
        return $this->render('CpdgUsuarioBundle:proveedores:edit.html.twig', array(
            'proveedor' => $proveedor,
            'transaccionSipProveedores' => $transaccionSipProveedores
        ));
    }

    private function buscarEnSipProveedores ($proveedor) {

        $em = $this->getDoctrine()->getManager('sipproveedores');
        $sipProveedores = array();

        try {
          
            // $codigo = $proveedor->getCodigo();
            // $proveedor = $em->getRepository('Cpdg\AdministradorBundle\Entity\SIPProveedores\Proveedor')->findOneByCodigoCopidrogas($codigo);

            $codigoCopi = $proveedor->getCodigo();
            $divisiones = $proveedor->getDivisiones();

            $emsp = $this->getDoctrine()->getManager('sipproveedores');
            $conexion = $emsp->getConnection();

            $conexion->beginTransaction();

            $proveedorSP = $emsp->createQueryBuilder()
                            ->select('p')
                            ->from('Cpdg\AdministradorBundle\Entity\SIPProveedores\Proveedor', 'p')
                            ->where('p.codigoCopidrogas=:codigoCopi')
                            ->andWhere('p.divisiones=:divisiones')
                            ->setParameter(':codigoCopi', $codigoCopi)
                            ->setParameter(':divisiones', $divisiones)
                            ->getQuery()
                            ->setMaxResults(1)
                            ->getOneOrNullResult();


            if ($proveedorSP) {

                $sipProveedores['estado'] = 'consultado';
                $sipProveedores['proveedor'] = $proveedorSP;  

            } else {

                $sipProveedores['estado'] = 'consultado';
                $sipProveedores['proveedor'] = $proveedorSP;  

            }
            

        } catch (\Exception $e){

            $sipProveedores['estado'] = 'error';
            $sipProveedores['proveedor'] = $e->getmessage();

        }

        return $sipProveedores;

    }//end function

    public function updateAction (Request $request, Proveedores $proveedor) {

        $convenio = $request->get('estadoConvenio');
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');
        $actualizarEnSipProveedores = $request->get('actualizarEnSipProveedores');

        try {

            if ($convenio) {

                $proveedor->setConvenio($convenio);
                $proveedor->setFechaInicio(new \DateTime($desde));
                $proveedor->setFechaFin(new \DateTime($hasta));

            } else {

                $proveedor->setConvenio($convenio);
                $proveedor->setFechaInicio(null);
                $proveedor->setFechaFin(null);

            }

            $proveedor->setFechaUltimaModificacion(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            $mensaje = '';
            if ($actualizarEnSipProveedores)
                $mensaje = $this->actualizarEnSipProveedores($proveedor);

            $this->addFlash('success','Operacion exitosa. '.$mensaje);

        } catch (\Exception $e) {
            $this->addFlash('error', 'Ocurrio un error inesperado. detalle: '.$e->getMessage());
        }

        
        return $this->redirectToRoute('proveedoresusr_index', array('page' => '1'));

    }

    private function actualizarEnSipProveedores ($proveedor) {

        $mensaje = '';

        try{

            $codigoCopi = $proveedor->getCodigo();
            $estadoConvenio = $proveedor->getConvenio();
            $strConvenio = $strDesde = $strHasta = '';

            $emsp = $this->getDoctrine()->getManager('sipproveedores');
            $conexion = $emsp->getConnection();

            $conexion->beginTransaction();
            $request = $this->get('request_stack')->getCurrentRequest();
            $idEnSipProveedores = $request->get('idEnSipProveedores');
            $proveedorSP = $emsp->getRepository('Cpdg\AdministradorBundle\Entity\SIPProveedores\Proveedor')->findOneById(array('id' => $idEnSipProveedores));

            if($proveedorSP){
            
                $persist = $emsp->createQueryBuilder();

                $persist->update('Cpdg\AdministradorBundle\Entity\SIPProveedores\Proveedor','p');
                $persist->set('p.convenio ',':estado');
                $persist->set('p.fechaInicio',':inicio');
                $persist->set('p.fechaFin',':fin');

                

                if( $estadoConvenio  == 1 ){
                    
                    $inicio = $proveedor->getFechaInicio();
                    $fin = $proveedor->getFechaFin();

                    $persist->setParameter(':estado','1');
                    $persist->setParameter(':inicio',new \DateTime( $inicio->format('Y-m-d') ) );
                    $persist->setParameter(':fin',new \DateTime( $fin->format('Y-m-d') ) );

                    $strConvenio = 'convenio activo, ';
                    $strDesde = ' fecha de incio: '.$inicio->format('Y-m-d') ;
                    $strHasta = ' fecha de finalización : '.$fin->format('Y-m-d') ;

                }else{

                    $persist->setParameter(':estado','0');
                    $persist->setParameter(':inicio',null );
                    $persist->setParameter(':fin',null );

                    $strConvenio = 'convenio inactivo ';
                    $strDesde = '';
                    $strHasta = '' ;
                }

                $persist->where('p.id=:id');
                $persist->setParameter(':id', $idEnSipProveedores);
                $persist->getQuery()->execute();

                $conexion->commit();

                $complemento = $strConvenio.$strDesde.$strHasta;
                $mensaje = ' Proveedor ['.$proveedor->getNombre().'] editado en BD SIPProveedores correctamente.[ '.$complemento.' ]';

            }else{

                $mensaje = 'El Proveedor ['.$proveedor->getNombre().' - código: '.$proveedor->getCodigo().'] no exite en la base de datos de SIPProveedores.';

            }

            

        }catch( \Doctrine\DBAL\DBALException $e ){

            $mensaje = $e->getMessage();
            $conexion->rollBack();

        }catch( \Exception $e){

            $mensaje = $e->getMessage();

        }

        unset($emsp, $conexion);
        return $mensaje;


    }


    /**
     * Deletes a Proveedores entity.
     *
     */
    public function deleteAction(Request $request, Proveedores $proveedore)
    {
        $form = $this->createDeleteForm($proveedore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($proveedore);
            $em->flush();
        }

        return $this->redirectToRoute('proveedoresusr_index',array('page'=>'1'));
    }

    /**
     * Creates a form to delete a Proveedores entity.
     *
     * @param Proveedores $proveedore The Proveedores entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Proveedores $proveedore)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('proveedoresusr_delete', array('id' => $proveedore->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
        $parametros = $request->get('parametros');
        
       /* $query = $this->getDoctrine()->getRepository('CpdgUsuarioBundle:Contactos')->createQueryBuilder('e');
        $query->where('e.idArea = :idArea')->setParameter('idArea', $userIdArea);
        $query->leftJoin('CpdgUsuarioBundle:Proveedores', 'p', 'WITH', 'p.id = e.idProveedor');*/

        // recuperamos los elementos de base de datos que queremos exportar
        $query = $em->getRepository('CpdgUsuarioBundle:Proveedores')
            ->createQueryBuilder('e');
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
            ->setCellValue('A1', 'Coopidrogas Proveedores');
        
        // escribimos en distintas celdas del documento el título de los campos que vamos a exportar
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A2', 'Empresa / Razón Social')
            ->setCellValue('B2', 'Nit')
            ->setCellValue('C2', 'Código')
            ->setCellValue('D2', 'Representante Legal')
            ->setCellValue('E2', 'Representante Legal Email')
            ->setCellValue('F2', 'Representante Legal Teléfono')
            ->setCellValue('G2', 'Convenio')
            ->setCellValue('H2', 'Fecha Inicio')
            ->setCellValue('I2', 'Fecha Fin')
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
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('H')
            ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
            ->getColumnDimension('I')
            ->setWidth(30);      


        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        $row = 3;
        foreach ($result as $item) {
            if($item->getConvenio() == 1){
                $convenio = "Activo";
                $fechaInicio = $item->getFechaInicio()->format('Y-m-d');
                $fechaFin = $item->getFechaFin()->format('Y-m-d');
            }else{
                $convenio = "Inactivo";
                $fechaInicio = "";
                $fechaFin = "";
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$row, $item->getNombre())
                ->setCellValue('B'.$row, $item->getNit())
                ->setCellValue('C'.$row, $item->getCodigo())
                ->setCellValue('D'.$row, $item->getRepresentanteLegal())
                ->setCellValue('E'.$row, $item->getEmailRepresentanteLegal())
                ->setCellValue('F'.$row, $item->getTelefonoRepresentanteLegal())
                ->setCellValue('G'.$row, $convenio)
                ->setCellValue('H'.$row, $fechaInicio)
                ->setCellValue('I'.$row, $fechaFin)
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
            'proveedores.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
