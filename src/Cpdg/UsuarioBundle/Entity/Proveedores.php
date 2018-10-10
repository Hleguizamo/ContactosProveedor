<?php

namespace Cpdg\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedores
 *
 * @ORM\Table(name="proveedores", uniqueConstraints={@ORM\UniqueConstraint(name="nit", columns={"nit"})})
 * @ORM\Entity
 */
class Proveedores
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nit", type="integer", nullable=false)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=256, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=32, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="representante_legal", type="string", length=255, nullable=false)
     */
    private $representanteLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="email_representante_legal", type="string", length=255, nullable=false)
     */
    private $emailRepresentanteLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_representante_legal", type="string", length=255, nullable=false)
     */
    private $telefonoRepresentanteLegal;

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
     * @ORM\Column(name="divisiones", type="string", length=255, nullable=false)
     */
    private $divisiones;

    /**
     * @var datetime $fechaUltimaModificacion
     *
     * @ORM\Column(name="fecha_ultima_modificacion", type="datetime", nullable=true)
     */
    private $fechaUltimaModificacion;

    /**
     * Set fechaUltimaModificacion
     *
     * @param datetime $fechaUltimaModificacion
     */
    public function setFechaUltimaModificacion($fechaUltimaModificacion)
    {
        $this->fechaUltimaModificacion = $fechaUltimaModificacion;
    }

    /**
     * Get fechaUltimaModificacion
     *
     * @return datetime 
     */
    public function getFechaUltimaModificacion()
    {
        return $this->fechaUltimaModificacion;
    }


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
     * Set nit
     *
     * @param integer $nit
     *
     * @return Proveedores
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return integer
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Proveedores
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Proveedores
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set representanteLegal
     *
     * @param string $representanteLegal
     *
     * @return Proveedores
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
     * @return Proveedores
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
     * Set telefonoRepresentanteLegal
     *
     * @param string $telefonoRepresentanteLegal
     *
     * @return Proveedores
     */
    public function setTelefonoRepresentanteLegal($telefonoRepresentanteLegal)
    {
        $this->telefonoRepresentanteLegal = $telefonoRepresentanteLegal;

        return $this;
    }

    /**
     * Get telefonoRepresentanteLegal
     *
     * @return string
     */
    public function getTelefonoRepresentanteLegal()
    {
        return $this->telefonoRepresentanteLegal;
    }
}
