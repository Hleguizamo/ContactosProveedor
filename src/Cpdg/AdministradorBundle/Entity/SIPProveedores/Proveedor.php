<?php

namespace Cpdg\AdministradorBundle\Entity\SIPProveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor", indexes={@ORM\Index(name="codigo_copidrogas", columns={"codigo_copidrogas"}), @ORM\Index(name="razon_social", columns={"razon_social"})})
 * @ORM\Entity
 */
class Proveedor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="razon_social", type="string", length=150, nullable=false)
     */
    private $razonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=30, nullable=false)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_copidrogas", type="string", length=3, nullable=false)
     */
    private $codigoCopidrogas;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=150, nullable=false)
     */
    private $responsable;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=30, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=20, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=100, nullable=false)
     */
    private $clave;

    /**
     * @var string
     *
     * @ORM\Column(name="clave1", type="string", length=100, nullable=true)
     */
    private $clave1;

    /**
     * @var string
     *
     * @ORM\Column(name="clave2", type="string", length=100, nullable=true)
     */
    private $clave2;

    /**
     * @var string
     *
     * @ORM\Column(name="clave3", type="string", length=100, nullable=true)
     */
    private $clave3;

    /**
     * @var string
     *
     * @ORM\Column(name="clave_activa", type="string", length=6, nullable=false)
     */
    private $claveActiva;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cambio_clave", type="date", nullable=false)
     */
    private $cambioClave;

    /**
     * @var string
     *
     * @ORM\Column(name="ultima_ip", type="string", length=30, nullable=false)
     */
    private $ultimaIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimo_acceso", type="date", nullable=false)
     */
    private $ultimoAcceso;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_convenio_comercial", type="integer", nullable=true)
     */
    private $idConvenioComercial;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=45, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=45, nullable=true)
     */
    private $ciudad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modificacion", type="datetime", nullable=true)
     */
    private $fechaModificacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_orden_actualizacion", type="datetime", nullable=true)
     */
    private $fechaOrdenActualizacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_desbloqueo", type="datetime", nullable=true)
     */
    private $fechaDesbloqueo;

    /**
     * @var string
     *
     * @ORM\Column(name="representante_legal", type="string", length=80, nullable=true)
     */
    private $representanteLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="email_representante_legal", type="string", length=120, nullable=true)
     */
    private $emailRepresentanteLegal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actualiza_contactos", type="boolean", nullable=true)
     */
    private $actualizaContactos = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_formularios", type="integer", nullable=true)
     */
    private $numeroFormularios = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actualiza_contactos_date", type="datetime", nullable=true)
     */
    private $actualizaContactosDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actualiza_contactos_bloqueo", type="datetime", nullable=true)
     */
    private $actualizaContactosBloqueo;

        /**
     * @var integer
     *
     * @ORM\Column(name="convenio", type="integer", length=30, nullable=false)
     */
    private $convenio;

    /**
     * @var date $fechaInicio
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @var date $fechaFin
     *
     * @ORM\Column(name="fecha_fin", type="date", nullable=true)
     */
    private $fechaFin;

        /**
     * @var string
     *
     * @ORM\Column(name="divisiones", type="string", length=255, nullable=true)
     */
    private $divisiones;

    /**
     * Set divisiones
     *
     * @param string $divisiones
     *
     * @return Proveedores
     */
    public function setDivisiones($divisiones)
    {
        $this->divisiones = $divisiones;

        return $this;
    }

    /**
     * Get divisiones
     *
     * @return string
     */
    public function getDivisiones()
    {
        return $this->divisiones;
    }


    /**
     * Set convenio
     *
     * @param integer $convenio
     *
     * @return Administrador
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;

        return $this;
    }

    /**
     * Get convenio
     *
     * @return integer
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * Set fechaInicio
     *
     * @param date $fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Get fechaInicio
     *
     * @return date 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param date $fechaFin
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Get fechaFin
     *
     * @return date 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set razonSocial
     *
     * @param string $razonSocial
     *
     * @return Proveedor
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return string
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return Proveedor
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set codigoCopidrogas
     *
     * @param string $codigoCopidrogas
     *
     * @return Proveedor
     */
    public function setCodigoCopidrogas($codigoCopidrogas)
    {
        $this->codigoCopidrogas = $codigoCopidrogas;

        return $this;
    }

    /**
     * Get codigoCopidrogas
     *
     * @return string
     */
    public function getCodigoCopidrogas()
    {
        return $this->codigoCopidrogas;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     *
     * @return Proveedor
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Proveedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Proveedor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Proveedor
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set clave
     *
     * @param string $clave
     *
     * @return Proveedor
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set clave1
     *
     * @param string $clave1
     *
     * @return Proveedor
     */
    public function setClave1($clave1)
    {
        $this->clave1 = $clave1;

        return $this;
    }

    /**
     * Get clave1
     *
     * @return string
     */
    public function getClave1()
    {
        return $this->clave1;
    }

    /**
     * Set clave2
     *
     * @param string $clave2
     *
     * @return Proveedor
     */
    public function setClave2($clave2)
    {
        $this->clave2 = $clave2;

        return $this;
    }

    /**
     * Get clave2
     *
     * @return string
     */
    public function getClave2()
    {
        return $this->clave2;
    }

    /**
     * Set clave3
     *
     * @param string $clave3
     *
     * @return Proveedor
     */
    public function setClave3($clave3)
    {
        $this->clave3 = $clave3;

        return $this;
    }

    /**
     * Get clave3
     *
     * @return string
     */
    public function getClave3()
    {
        return $this->clave3;
    }

    /**
     * Set claveActiva
     *
     * @param string $claveActiva
     *
     * @return Proveedor
     */
    public function setClaveActiva($claveActiva)
    {
        $this->claveActiva = $claveActiva;

        return $this;
    }

    /**
     * Get claveActiva
     *
     * @return string
     */
    public function getClaveActiva()
    {
        return $this->claveActiva;
    }

    /**
     * Set cambioClave
     *
     * @param \DateTime $cambioClave
     *
     * @return Proveedor
     */
    public function setCambioClave($cambioClave)
    {
        $this->cambioClave = $cambioClave;

        return $this;
    }

    /**
     * Get cambioClave
     *
     * @return \DateTime
     */
    public function getCambioClave()
    {
        return $this->cambioClave;
    }

    /**
     * Set ultimaIp
     *
     * @param string $ultimaIp
     *
     * @return Proveedor
     */
    public function setUltimaIp($ultimaIp)
    {
        $this->ultimaIp = $ultimaIp;

        return $this;
    }

    /**
     * Get ultimaIp
     *
     * @return string
     */
    public function getUltimaIp()
    {
        return $this->ultimaIp;
    }

    /**
     * Set ultimoAcceso
     *
     * @param \DateTime $ultimoAcceso
     *
     * @return Proveedor
     */
    public function setUltimoAcceso($ultimoAcceso)
    {
        $this->ultimoAcceso = $ultimoAcceso;

        return $this;
    }

    /**
     * Get ultimoAcceso
     *
     * @return \DateTime
     */
    public function getUltimoAcceso()
    {
        return $this->ultimoAcceso;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return Proveedor
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set idConvenioComercial
     *
     * @param integer $idConvenioComercial
     *
     * @return Proveedor
     */
    public function setIdConvenioComercial($idConvenioComercial)
    {
        $this->idConvenioComercial = $idConvenioComercial;

        return $this;
    }

    /**
     * Get idConvenioComercial
     *
     * @return integer
     */
    public function getIdConvenioComercial()
    {
        return $this->idConvenioComercial;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Proveedor
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Proveedor
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set fechaModificacion
     *
     * @param \DateTime $fechaModificacion
     *
     * @return Proveedor
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return \DateTime
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set fechaOrdenActualizacion
     *
     * @param \DateTime $fechaOrdenActualizacion
     *
     * @return Proveedor
     */
    public function setFechaOrdenActualizacion($fechaOrdenActualizacion)
    {
        $this->fechaOrdenActualizacion = $fechaOrdenActualizacion;

        return $this;
    }

    /**
     * Get fechaOrdenActualizacion
     *
     * @return \DateTime
     */
    public function getFechaOrdenActualizacion()
    {
        return $this->fechaOrdenActualizacion;
    }

    /**
     * Set fechaDesbloqueo
     *
     * @param \DateTime $fechaDesbloqueo
     *
     * @return Proveedor
     */
    public function setFechaDesbloqueo($fechaDesbloqueo)
    {
        $this->fechaDesbloqueo = $fechaDesbloqueo;

        return $this;
    }

    /**
     * Get fechaDesbloqueo
     *
     * @return \DateTime
     */
    public function getFechaDesbloqueo()
    {
        return $this->fechaDesbloqueo;
    }

    /**
     * Set representanteLegal
     *
     * @param string $representanteLegal
     *
     * @return Proveedor
     */
    public function setRepresentanteLegal($representanteLegal)
    {
        $this->representanteLegal = $representanteLegal;

        return $this;
    }

    /**
     * Get representanteLegal
     *
     * @return string
     */
    public function getRepresentanteLegal()
    {
        return $this->representanteLegal;
    }

    /**
     * Set emailRepresentanteLegal
     *
     * @param string $emailRepresentanteLegal
     *
     * @return Proveedor
     */
    public function setEmailRepresentanteLegal($emailRepresentanteLegal)
    {
        $this->emailRepresentanteLegal = $emailRepresentanteLegal;

        return $this;
    }

    /**
     * Get emailRepresentanteLegal
     *
     * @return string
     */
    public function getEmailRepresentanteLegal()
    {
        return $this->emailRepresentanteLegal;
    }

    /**
     * Set actualizaContactos
     *
     * @param boolean $actualizaContactos
     *
     * @return Proveedor
     */
    public function setActualizaContactos($actualizaContactos)
    {
        $this->actualizaContactos = $actualizaContactos;

        return $this;
    }

    /**
     * Get actualizaContactos
     *
     * @return boolean
     */
    public function getActualizaContactos()
    {
        return $this->actualizaContactos;
    }

    /**
     * Set numeroFormularios
     *
     * @param integer $numeroFormularios
     *
     * @return Proveedor
     */
    public function setNumeroFormularios($numeroFormularios)
    {
        $this->numeroFormularios = $numeroFormularios;

        return $this;
    }

    /**
     * Get numeroFormularios
     *
     * @return integer
     */
    public function getNumeroFormularios()
    {
        return $this->numeroFormularios;
    }

    /**
     * Set actualizaContactosDate
     *
     * @param \DateTime $actualizaContactosDate
     *
     * @return Proveedor
     */
    public function setActualizaContactosDate($actualizaContactosDate)
    {
        $this->actualizaContactosDate = $actualizaContactosDate;

        return $this;
    }

    /**
     * Get actualizaContactosDate
     *
     * @return \DateTime
     */
    public function getActualizaContactosDate()
    {
        return $this->actualizaContactosDate;
    }

    /**
     * Set actualizaContactosBloqueo
     *
     * @param \DateTime $actualizaContactosBloqueo
     *
     * @return Proveedor
     */
    public function setActualizaContactosBloqueo($actualizaContactosBloqueo)
    {
        $this->actualizaContactosBloqueo = $actualizaContactosBloqueo;

        return $this;
    }

    /**
     * Get actualizaContactosBloqueo
     *
     * @return \DateTime
     */
    public function getActualizaContactosBloqueo()
    {
        return $this->actualizaContactosBloqueo;
    }
}
