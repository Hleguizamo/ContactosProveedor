<?php

namespace Cpdg\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CargosProveedor
 *
 * @ORM\Table(name="cargos_proveedor", indexes={@ORM\Index(name="id_proveedor", columns={"id_proveedor"}), @ORM\Index(name="id_cargo", columns={"id_cargo"}) })
 * @ORM\Entity
 */
class CargosProveedor
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
     * @var \Proveedores
     *
     * @ORM\ManyToOne(targetEntity="Proveedores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_proveedor", referencedColumnName="id")
     * })
     */
    private $idProveedor;

    /**
     * @var \Cargos
     *
     * @ORM\ManyToOne(targetEntity="Cargos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cargo", referencedColumnName="id")
     * })
     */
    private $idCargo;

    



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
     * Set idProveedor
     *
     * @param \Cpdg\AdministradorBundle\Entity\Proveedores $idProveedor
     *
     * @return CargosProveedor
     */
    public function setIdProveedor(\Cpdg\AdministradorBundle\Entity\Proveedores $idProveedor = null)
    {
        $this->idProveedor = $idProveedor;

        return $this;
    }

    /**
     * Get idProveedor
     *
     * @return \Cpdg\AdministradorBundle\Entity\Proveedores
     */
    public function getIdProveedor()
    {
        return $this->idProveedor;
    }

    /**
     * Set idCargo
     *
     * @param \Cpdg\AdministradorBundle\Entity\Cargos $idCargo
     *
     * @return CargosProveedor
     */
    public function setIdCargo(\Cpdg\AdministradorBundle\Entity\Cargos $idCargo = null)
    {
        $this->idCargo = $idCargo;

        return $this;
    }

    /**
     * Get idCargo
     *
     * @return \Cpdg\AdministradorBundle\Entity\Cargos
     */
    public function getIdCargo()
    {
        return $this->idCargo;
    }

}
