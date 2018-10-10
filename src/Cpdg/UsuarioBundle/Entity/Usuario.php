<?php

namespace Cpdg\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", indexes={@ORM\Index(name="id_area", columns={"id_area"})})
 * @ORM\Entity
 */
class Usuario implements UserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=25, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=255, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=250, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasena", type="string", length=30, nullable=false)
     */
    private $contrasena;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean", nullable=false)
     */
    private $public = '1';

    /**
     * @var \Areas
     *
     * @ORM\ManyToOne(targetEntity="Areas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_area", referencedColumnName="id")
     * })
     */
    private $idArea;

    /**
     * @var integer
     *
     * @ORM\Column(name="convenio", type="integer", length=3, nullable=false)
     */
    private $convenio;

    /**
     * @var integer
     *
     * @ORM\Column(name="editar_informacion", type="integer", length=3, nullable=false)
     */
    private $editarInformacion;

    /**
     * Set editarInformacion
     *
     * @param integer $editarInformacion
     *
     * @return Administrador
     */
    public function setEditarInformacion($editarInformacion)
    {
        $this->editarInformacion = $editarInformacion;

        return $this;
    }

    /**
     * Get editarInformacion
     *
     * @return integer
     */
    public function getEditarInformacion()
    {
        return $this->editarInformacion;
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
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Usuario
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
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
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
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
     * Set contrasena
     *
     * @param string $contrasena
     *
     * @return Usuario
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Usuario
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set idArea
     *
     * @param \Cpdg\UsuarioBundle\Entity\Areas $idArea
     *
     * @return Usuario
     */
    public function setIdArea(\Cpdg\UsuarioBundle\Entity\Areas $idArea = null)
    {
        $this->idArea = $idArea;

        return $this;
    }

    /**
     * Get idArea
     *
     * @return \Cpdg\UsuarioBundle\Entity\Areas
     */
    public function getIdArea()
    {
        return $this->idArea;
    }
	
	public function eraseCredentials() {
      $this->password = null;
    }

    public function getPassword() {
        return $this->getContrasena();
    }

    public function getRoles() {
        return array('ROLE_USER');
    }

    public function getSalt() {
        
    }

    public function getUsername() {
        return $this->getUsuario();
    }
}
