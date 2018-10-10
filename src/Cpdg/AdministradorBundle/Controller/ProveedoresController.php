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

use Cpdg\AdministradorBundle\Entity\Proveedores;
use Cpdg\AdministradorBundle\Entity\Contactos;
use Cpdg\AdministradorBundle\Form\ProveedoresType;
use Cpdg\AdministradorBundle\Entity\Archivos;
use Cpdg\AdministradorBundle\Form\ArchivosType;
use Cpdg\UsuarioBundle\Entity\Logs;
use Cpdg\UsuarioBundle\Entity\SIPProveedores\Proveedor;
use Cpdg\AdministradorBundle\Entity\CargosProveedor;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Proveedores controller.
 *
 */
class ProveedoresController extends Controller
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
        $admin = $session->get('admin');

        $em = $this->getDoctrine()->getManager();
        $parametros = array();
        $data = $request->request->all();
        if(isset($data['find'])){
            $consulta = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Proveedores')->createQueryBuilder('e');

            if ($data['proveedores']['nit'] != "") {

                $consulta->where('e.nit LIKE :nit')->setParameter('nit', '%'.$data['proveedores']['nit'].'%');

                $parametros['e.nit']['variable'] = 'nit';
                $parametros['e.nit']['filtro'] = 'LIKE';
                $parametros['e.nit']['valor'] = '%'.$data['proveedores']['nit'].'%'; 

            }//end if

            if ($data['proveedores']['nombre'] != "") {

                $consulta->orWhere('e.nombre LIKE :nombre')->setParameter('nombre', '%'.$data['proveedores']['nombre'].'%');

                $parametros['e.nombre']['variable'] = 'nombre';
                $parametros['e.nombre']['filtro'] = 'LIKE';
                $parametros['e.nombre']['valor'] = '%'.$data['proveedores']['nombre'].'%'; 

            }//end if

            if ($data['proveedores']['codigo'] != "") {

                $consulta->orWhere('e.codigo LIKE :codigo')->setParameter('codigo', '%'.$data['proveedores']['codigo'].'%');

                $parametros['e.codigo']['variable'] = 'codigo';
                $parametros['e.codigo']['filtro'] = 'LIKE';
                $parametros['e.codigo']['valor'] = '%'.$data['proveedores']['codigo'].'%'; 

            }//end if

            if ($data['proveedores']['representanteLegal'] != "") {

                $consulta->orWhere('e.representanteLegal LIKE :representanteLegal')->setParameter('representanteLegal', '%'.$data['proveedores']['representanteLegal'].'%');

                $parametros['e.representanteLegal']['variable'] = 'representanteLegal';
                $parametros['e.representanteLegal']['filtro'] = 'LIKE';
                $parametros['e.representanteLegal']['valor'] = '%'.$data['proveedores']['representanteLegal'].'%'; 

            }//end if

            if ($data['proveedores']['emailRepresentanteLegal'] != "") {

                $consulta->orWhere('e.emailRepresentanteLegal LIKE :emailRepresentanteLegal')->setParameter('emailRepresentanteLegal', '%'.$data['proveedores']['emailRepresentanteLegal'].'%');

                $parametros['e.emailRepresentanteLegal']['variable'] = 'emailRepresentanteLegal';
                $parametros['e.emailRepresentanteLegal']['filtro'] = 'LIKE';
                $parametros['e.emailRepresentanteLegal']['valor'] = '%'.$data['proveedores']['emailRepresentanteLegal'].'%';    

            }//end if

            if ($data['proveedores']['telefonoRepresentanteLegal'] != "") {

                $consulta->orWhere('e.telefonoRepresentanteLegal LIKE :telefonoRepresentanteLegal')->setParameter('telefonoRepresentanteLegal', '%'.$data['proveedores']['telefonoRepresentanteLegal'].'%');

                $parametros['e.telefonoRepresentanteLegal']['variable'] = 'telefonoRepresentanteLegal';
                $parametros['e.telefonoRepresentanteLegal']['filtro'] = 'LIKE';
                $parametros['e.telefonoRepresentanteLegal']['valor'] = '%'.$data['proveedores']['telefonoRepresentanteLegal'].'%';  

            }//end if

            if ($data['convenioSeleccionado'] != "sinSeleccionar") {

                $consulta->andWhere('e.convenio = :convenio')->setParameter('convenio', $data['convenioSeleccionado']);
                $parametros['e.convenio']['variable'] = 'convenio';
                $parametros['e.convenio']['filtro'] = '\=';
                $parametros['e.convenio']['valor'] = $data['convenioSeleccionado'];

            }//end if

            if ($data['activacionConvenio'] != "") {

                $consulta->andWhere('e.fechaInicio '.$data['filtroFecha'].' :fechaInicio')->setParameter('fechaInicio', $data['activacionConvenio']);
                $parametros['e.fechaInicio']['variable'] = 'convenio';
                $parametros['e.fechaInicio']['filtro'] = $data['filtroFecha'];
                $parametros['e.fechaInicio']['valor'] = $data['activacionConvenio'];

            }//end if 

        } else {

            $consulta = $em->getRepository('CpdgAdministradorBundle:Proveedores')->findAll();

        }//end if

        /* Modal crear nuevo */
        $proveedore = new Proveedores();
        $formnew = $this->createForm(new ProveedoresType(), $proveedore);
        
        $archiv = new Archivos();
        $formArchivo = $this->createForm(ArchivosType::class, $archiv);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $consulta,
            $this->get('request')->query->get('page', $page),$this->cantidadPorPagina
        );

        /*** Busqueda de Cargos ***/

            $cargos = $em->getRepository('CpdgAdministradorBundle:Cargos')->findAll();

        /***/

        return $this->render('CpdgAdministradorBundle:proveedores:index.html.twig', array(
            'resultset' => $pagination, 
            'page' => ($page - 1), 
            'cantidadPorPagina'=>$this->cantidadPorPagina,
            'formNew' => $formnew->createView(),
            'formArchivo' => $formArchivo->createView(),
            'admin'=>$admin,
            'parametros' => $parametros,
            'cargos' => $cargos
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

                $session = $request->getSession();
                $admin = $session->get('admin');

                if( $admin->getConvenio() != 1 )
                    $insert->setConvenio(0);
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($insert);
                $em->flush();


                if($request->get('cargo')){

                    $cargos = $request->get('cargo');
                    foreach($cargos as $cargo){

                        $cargoProveedor = new CargosProveedor();

                        $cargoProveedor->setIdProveedor($em->getReference('CpdgAdministradorBundle:Proveedores', $insert->getId() ) );
                        $cargoProveedor->setIdCargo($em->getReference('CpdgAdministradorBundle:Cargos', $cargo));

                        $em->persist($cargoProveedor);
                        $em->flush();

                    }

                }
                $this->addFlash('success', "Creado correctamente");
           } catch(\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('danger', "Error: No se puede crear el registro, NIT ya ingresado en el sistema");
            }

            return $this->redirectToRoute('proveedores_index', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
        }
        return $this->redirectToRoute('proveedores_index', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
    }

    /**
     * Finds and displays a Proveedores entity.
     *
     */
    public function showAction(Proveedores $proveedore)
    {
        $deleteForm = $this->createDeleteForm($proveedore);

        return $this->render('CpdgAdministradorBundle:proveedores:show.html.twig', array(
            'proveedore' => $proveedore,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Proveedores entity.
     *
     */
    public function editAction(Request $request, Proveedores $proveedore)
    {   
        $session = $request->getSession();
        $admin = $session->get('admin');
        $editForm = $this->createForm(new ProveedoresType(), $proveedore);
        $editForm->handleRequest($request);

        $divisiones = $proveedore->getDivisiones();
        $em = $this->getDoctrine()->getManager();
        /*** Busqueda de Cargos ***/

            //$cargos = $em->getRepository('CpdgAdministradorBundle:Cargos')->findAll();
            $cargos = $em->createQuery("SELECT c.id, c.nombre, cp.id AS asignado
                FROM CpdgAdministradorBundle:Cargos c 
                LEFT JOIN CpdgAdministradorBundle:CargosProveedor cp WITH c.id=cp.idCargo AND cp.idProveedor=".$proveedore->getId()."  ORDER BY c.id ASC ")->getResult();

        /***/

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            //$em = $this->getDoctrine()->getManager();

            $strResultado = ' No se ha realizado ningún cambio en la base de datos SIPProveedores ya que el Administrador no tiene permiso de edición de convenio. ';

            if($admin->getConvenio() == 1)
                $strResultado = $this->setProveedor($proveedore);
            
            $em->persist($proveedore);
            $em->flush();

            $this->addFlash('success', " Proveedor editado correctamente. Adicional: ".$strResultado);
            return $this->redirectToRoute('proveedores_edit', array('id' => $proveedore->getId()));
        }

        return $this->render('CpdgAdministradorBundle:proveedores:edit.html.twig', array(
            'proveedor' => $proveedore,
            'edit_form' => $editForm->createView(),
            'admin' => $admin,
            'divisiones' => $divisiones,
            'cargos' => $cargos
        ));
    }

    private function setProveedor($proveedor) {

        $mensaje = '';

        try{

            $codigoCopi = $proveedor->getCodigo();
            $divisiones = $proveedor->getDivisiones();
            $estadoConvenio = $proveedor->getConvenio();
            $strConvenio = $strDesde = $strHasta = '';

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
                $persist->setParameter(':id', $proveedorSP->getId());
                $persist->getQuery()->execute();

                $conexion->commit();

                $complemento = $strConvenio.$strDesde.$strHasta;
                $mensaje = ' Proveedor ['.$proveedor->getNombre().'] editado en BD SIPProveedores correctamente.[ '.$complemento.' ]';

            } else {

                $mensaje = 'El Proveedor ['.$proveedor->getNombre().' - código: '.$proveedor->getCodigo().'] no exite en la base de datos de SIPProveedores.';

            }

          
               

        }catch( \Doctrine\DBAL\DBALException $e ){

            $mensaje = $e->getMessage();
            $conexion->rollBack();

        }catch( \Exception $e){

            $mensaje = $e->getMessage();

        }

        $conexion->close();
        return $mensaje;


    }//end function


    /**
     * Deletes a Proveedores entity.
     *
     */
    public function deleteAction(Request $request, Proveedores $proveedore)
    {
        $data = $request->request->all();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();        
        $contactos = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Contactos')->createQueryBuilder('e');
        $contactos->where('e.idProveedor = :idProveedor')->setParameter('idProveedor', $proveedore->getId());
        $runcontactos = $contactos->getQuery()->execute();
        $counter = 0;
        $contactosNombres = "";
        foreach($runcontactos as $runcontactos_){ $counter ++; 
            $contactosNombres .= "- ".$runcontactos_->getNombreContacto()." (".$runcontactos_->getEmail().")"."<br>";
        }
        if($counter == 0){

            $this->processLogAction(0,$userid, "Elimina Proveedor id: ".$proveedore->getId()." Nombre: ".$proveedore->getNombre());
            $em = $this->getDoctrine()->getManager();
            $em->remove($proveedore);
            $em->flush();
            $this->addFlash('success', 'Eliminado correctamente');
        }else{
            $this->addFlash('danger', "No se puede eliminar debido a que se encuentran contactos asociados al proveedor.<br><br>Debe eliminar los siguientes contactos antes de eliminar el proveedor: <br>".$contactosNombres."<br><br>Para realizar esta acción puede ingresar por la opción del menú Contactos");
        }
       

        /*
            try{

            } catch(\Doctrine\DBAL\DBALException $e) {
                $retVarError .= ' Registro: '.$dataInsert[1].' No se pudo crear.';
            }
        */
        //$this->addFlash('success', 'Eliminado correctamente');
       // $em = $this->getDoctrine()->getManager();
        //$em->remove($proveedore);
       // $em->flush();


        
        return $this->redirectToRoute('proveedores_index', array('page' => '1' , 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
    }

    /**
     * @Route("/exportar/excel", name="exportar_excel")
     */
    public function exportarExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $useridObj = $this->get('security.context')->getToken()->getUser('Article', 1);
        $userid=$useridObj->getId();
 
        // recuperamos los elementos de base de datos que queremos exportar
        // $query = $em->getRepository('CpdgAdministradorBundle:Proveedores')
        //     ->createQueryBuilder('e')
        //     ->getQuery();

        // $result = $query->getResult();

        $parametros = $request->get('parametros');

        $query = $em->getRepository('CpdgAdministradorBundle:Proveedores')
            ->createQueryBuilder('e');

        if($parametros){
            foreach($parametros as $key => $parametro){
                $query->andWhere($key.' '.str_replace("\\", "", $parametro['filtro']).' :'.$parametro['variable'])->setParameter($parametro['variable'], $parametro['valor']);
            }
        }

        $result = $query->getQuery()->getResult();



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


        // recorremos los registros obtenidos de la consulta a base de datos escribiéndolos en las celdas correspondientes
        $row = 3;
        foreach ($result as $item) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$row, $item->getNombre())
                ->setCellValue('B'.$row, $item->getNit())
                ->setCellValue('C'.$row, $item->getCodigo())
                ->setCellValue('D'.$row, $item->getRepresentanteLegal())
                ->setCellValue('E'.$row, $item->getEmailRepresentanteLegal())
                ->setCellValue('F'.$row, $item->getTelefonoRepresentanteLegal())
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

    /**
     * @Route("/exportar/excel", name="exportar_excel")
     */
    public function downloadExcelAction(Request $request)
    {
        
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

        // se crea el writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // se crea el response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // y por último se añaden las cabeceras
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'FormatoCargaProveedores.xls'
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

        if ($form->isValid()) {
            // $file stores the uploaded xls file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */

            $em = $this->getDoctrine()->getManager();
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
            $retVar = "";
            $retVarError = "";
            $dataInsert = array();
            $boolAlguno = false;
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    foreach ($worksheet->getRowIterator() as $row) {
                        if($row->getRowIndex() != 1){
                            $cellIterator = $row->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(true);
                            unset($dataInsert);
                            $dataInsert = array();
                            $boolInsert = false;
                            foreach ($cellIterator as $cell) {
                               $dataInsert[] = $cell->getCalculatedValue();
                               if (!is_null($cell)) {
                                    if($cell->getCoordinate() == "B".$row->getRowIndex()){
                                       $proveedor = $this->getDoctrine()->getRepository('CpdgAdministradorBundle:Proveedores')->createQueryBuilder('e');
                                       $proveedor->where('e.nit = :nit')->setParameter('nit', $cell->getCalculatedValue()); 
                                       $inicount = $proveedor->getQuery()->execute();
                                       $total = -1;      
                                       foreach($inicount as $repoc){ $total ++; } 
                                            if($total == 0){
                                               $boolInsert = false;
                                            }else{
                                               $boolInsert = true;
                                            }
                                    }
                                }
                            }
                            if($boolInsert == true){
                                $retVar .= ' Insertado NIT: '.$dataInsert[1];
                                $insert = new Proveedores();
                                if($dataInsert[0] == NULL) $dataInsert[0] = "-"; else $dataInsert[0];
                                if($dataInsert[2] == NULL) $dataInsert[2] = "-"; else $dataInsert[2];
                                if($dataInsert[3] == NULL) $dataInsert[3] = "-"; else $dataInsert[3];
                                if($dataInsert[4] == NULL) $dataInsert[4] = "-"; else $dataInsert[4];
                                if($dataInsert[5] == NULL) $dataInsert[5] = "-"; else $dataInsert[5];

                                try{
                                    $em = $this->getDoctrine()->getManager();
                                    $insert->setNit($dataInsert[1]);
                                    $insert->setNombre($dataInsert[0]);
                                    $insert->setCodigo($dataInsert[2]);
                                    $insert->setRepresentanteLegal($dataInsert[3]);
                                    $insert->setEmailRepresentanteLegal($dataInsert[4]);
                                    $insert->setTelefonoRepresentanteLegal($dataInsert[5]);
                                    $em->persist($insert);
                                    $em->flush();
                                    $boolAlguno = true;
                                } catch(\Doctrine\DBAL\DBALException $e) {
                                    $retVarError .= ' Registro: '.$dataInsert[1].' No se pudo crear.';
                                }
                            }
                        }
                    }
            }
            if($boolAlguno == true){
                $this->addFlash('success', 'Solo se ingresan los proveedores nuevos debido a que se ya encuentran contactos registrados. '.$retVar);
            }else{
               $this->addFlash('success', 'Todos los usuarios que intenta ingresar ya se encuentran registrados en el sistema'); 
            }
                        
            return $this->redirectToRoute('proveedores_index', array('page' => '1', 'ordervar'=> 'p.nombre',  'ordervaro'=> 'asc'));
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


    public function asignarCargoAction(Request $request){

        $session=$request->getSession();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $json=array();
        
        $em = $this->getDoctrine()->getManager();

        
        $cargoProveedor = $em->getRepository('CpdgAdministradorBundle:CargosProveedor')->findOneBy(array('idProveedor' => $request->get('proveedor'), 'idCargo' => $request->get('cargo') ));



        if($request->get('accion') == 1){ //asignar cargo

            

            if($cargoProveedor){

                $json['status'] = 0;
                $json['mensaje'] = "El Proveedor ya tiene asignado el cargo seleccionado";

            }else{
                $cargoProveedor = new CargosProveedor();

                $cargoProveedor->setIdProveedor($em->getReference('CpdgAdministradorBundle:Proveedores', $request->get('proveedor') ) );
                $cargoProveedor->setIdCargo($em->getReference('CpdgAdministradorBundle:Cargos', $request->get('cargo') ));

                $em->persist($cargoProveedor);
                $em->flush();

                $json['status'] = 1;
                $json['mensaje'] = "Cargo Asignado Correctamente";
            }


        }else{ //quitar cargo

            if($cargoProveedor){

                $contactos = $em->getRepository('CpdgAdministradorBundle:Contactos')->findOneBy(array('idProveedor' => $request->get('proveedor'), 'idCargo' => $request->get('cargo')  ));

                if($contactos){

                    $json['status'] = 0;
                    $json['mensaje'] = "Se encontraron contactos con el cargo seleccionado. El cargo no puede ser eliminado.";

                }else{

                    $em->remove($cargoProveedor);
                    $em->flush();

                    $json['status'] = 1;
                    $json['mensaje'] = "Cargo Removido Correctamente";


                }


            }else{

                $json['status'] = 0;
                $json['mensaje'] = "El cargo seleccionado no se encuentra asginado al proveedor";

            }


        }

        $response->setContent(json_encode($json));
        
        return $response;
    }


    public function cargarCargoAction(Request $request){


        $em = $this->getDoctrine()->getManager();

        $cargos = $em->getRepository('CpdgAdministradorBundle:Cargos')->findAll();

        return $this->render('CpdgAdministradorBundle:proveedores:importar.html.twig', array(
            'cargos' => $cargos
            )); 


    }



    public function asignarCargoCsvAction(Request $request){
        $session=$request->getSession();

        set_time_limit ( 0 );
        ini_set('memory_limit', '1024M');
        //MOVER EL ARCHIVO  
        $filesystem = new Filesystem();
        $nombre=$_FILES['file']['name'];
        $filesystem->copy($_FILES['file']['tmp_name'], './'.$nombre, $override = false);
        //conexion bd antigua
        $em = $this->getDoctrine()->getManager();
        $conexion = $em->getConnection();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $json=array();

        $actualizado=' <h2>Actualizado</h2>';
        $noActualizado='<h2>No actualizados</h2>';
        $excepcion='<h2>Excepciones</h2>';

        $cargos = $em->getRepository('CpdgAdministradorBundle:Cargos')->findAll();
        $arrayCargos = array();
        foreach($cargos as $c){
            $arrayCargos[$c->getId()] = $c->getNombre();
        }


        if(filesize('./'.$nombre)>0){
            $fp=fopen('./'.$nombre,"r");
            $aux=0;
            if($fp){
                try{


                    while($data = fgetcsv($fp, 1000, ";")){

                        //data[0] = nit
                        //data[1] = codigo copidrogas
                        //data[2] = codigo de cargo

                        $data[0] = trim($data[0]);
                        $data[1] = trim($data[1]);
                        $data[2] = trim($data[2]);

                        if($data[0] != "" && $data[2] != ""){

                            $proveedor = $em->getRepository('CpdgAdministradorBundle:Proveedores')->findOneBy(array('nit' => $data[0]));

                            if($proveedor){

                                if( isset( $arrayCargos[$data[2]] ) ){

                                    $cargoAsignado = $em->getRepository('CpdgAdministradorBundle:CargosProveedor')->findOneBy(array('idProveedor' => $proveedor->getId(), 'idCargo' => $data[2] ));

                                    if(!$cargoAsignado){

                                        $cargoAsignado = new CargosProveedor();

                                        $cargoAsignado->setIdProveedor($em->getReference('CpdgAdministradorBundle:Proveedores', $proveedor->getId() ) );
                                        $cargoAsignado->setIdCargo($em->getReference('CpdgAdministradorBundle:Cargos', $data[2] ));

                                        $em->persist($cargoAsignado);
                                        $em->flush();

                                        $actualizado.='<br>Cargo ['.$arrayCargos[$data[2]].'] asignado al proveedor ['.$proveedor->getNombre().']';

                                    }else{
                                        $actualizado.='<br>Cargo ['.$arrayCargos[$data[2]].'] ya estaba asignado al proveedor ['.$proveedor->getNombre().']';
                                    }

                                }else{

                                    $excepcion.='<br>No se enontro el cargo con el Código ['.$data[2].']';

                                }

                                
                            }else{
                                $excepcion.='<br>No se enontro el proveedor con el NIT ['.$data[0].']';
                            }

                        }else{
                            $excepcion.='<br>La columna NIT o CÓDIGO DE CARGO se encuentra vacia';
                        }

                        
                    }//fin while
                    
                    
                    
                    $json['respuesta']=1;
                    $json['actualizado']=$actualizado;
                    $json['noActualizado']=$noActualizado;
                    $json['excepciones']=$excepcion;
                        
                }catch(Exception $e){
                    $response->setStatusCode(500);
                    $json['respuesta']=2;
                }          
            }
        }else{
            $response->setStatusCode(500);
        }

        unset($fp);
        unset($data);
        $filesystem->remove(array('','./web/',$nombre));
        $response->setContent(json_encode($json));
        return $response;
        
    }
}
