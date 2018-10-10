<?php

namespace Cpdg\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Administrador
 *
 * @ORM\Table(name="administrador", uniqueConstraints={@ORM\UniqueConstraint(name="usuario", columns={"usuario"})})
 * @ORM\Entity
 */
class Administrador implements UserInterface
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
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasena", type="string", length=30, nullable=false)
     */
    private $contrasena;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="superadmin", type="boolean", nullable=false)
     */
    private $superadmin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_administradores", type="boolean", nullable=false)
     */
    private $menuAdministradores;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_usuarios", type="boolean", nullable=false)
     */
    private $menuUsuarios;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_contactos", type="boolean", nullable=false)
     */
    private $menuContactos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_proveedores", type="boolean", nullable=false)
     */
    private $menuProveedores;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_areas", type="boolean", nullable=false)
     */
    private $menuAreas;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_cargos", type="boolean", nullable=false)
     */
    private $menuCargos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menu_logs", type="boolean", nullable=false)
     */
    private $menuLogs;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean", nullable=false)
     */
    private $public = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="convenio", type="integer", length=3, nullable=false)
     */
    private $convenio;

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
     * @return integer
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
     * @return Administrador
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
     * Set contrasena
     *
     * @param string $contrasena
     *
     * @return Administrador
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Administrador
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
     * @return Administrador
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
     * Set superadmin
     *
     * @param boolean $superadmin
     *
     * @return Administrador
     */
    public function setSuperadmin($superadmin)
    {
        $this->superadmin = $superadmin;

        return $this;
    }

    /**
     * Get superadmin
     *
     * @return boolean
     */
    public function getSuperadmin()
    {
        return $this->superadmin;
    }

    /**
     * Set menuAdministradores
     *
     * @param boolean $menuAdministradores
     *
     * @return Administrador
     */
    public function setMenuAdministradores($menuAdministradores)
    {
        $this->menuAdministradores = $menuAdministradores;

        return $this;
    }

    /**
     * Get menuAdministradores
     *
     * @return boolean
     */
    public function getMenuAdministradores()
    {
        return $this->menuAdministradores;
    }

    /**
     * Set menuUsuarios
     *
     * @param boolean $menuUsuarios
     *
     * @return Administrador
     */
    public function setMenuUsuarios($menuUsuarios)
    {
        $this->menuUsuarios = $menuUsuarios;

        return $this;
    }

    /**
     * Get menuUsuarios
     *
     * @return boolean
     */
    public function getMenuUsuarios()
    {
        return $this->menuUsuarios;
    }

    /**
     * Set menuContactos
     *
     * @param boolean $menuContactos
     *
     * @return Administrador
     */
    public function setMenuContactos($menuContactos)
    {
        $this->menuContactos = $menuContactos;

        return $this;
    }

    /**
     * Get menuContactos
     *
     * @return boolean
     */
    public function getMenuContactos()
    {
        return $this->menuContactos;
    }

    /**
     * Set menuProveedores
     *
     * @param boolean $menuProveedores
     *
     * @return Administrador
     */
    public function setMenuProveedores($menuProveedores)
    {
        $this->menuProveedores = $menuProveedores;

        return $this;
    }

    /**
     * Get menuProveedores
     *
     * @return boolean
     */
    public function getMenuProveedores()
    {
        return $this->menuProveedores;
    }

    /**
     * Set menuAreas
     *
     * @param boolean $menuAreas
     *
     * @return Administrador
     */
    public function setMenuAreas($menuAreas)
    {
        $this->menuAreas = $menuAreas;

        return $this;
    }

    /**
     * Get menuAreas
     *
     * @return boolean
     */
    public function getMenuAreas()
    {
        return $this->menuAreas;
    }

    /**
     * Set menuCargos
     *
     * @param boolean $menuCargos
     *
     * @return Administrador
     */
    public function setMenuCargos($menuCargos)
    {
        $this->menuCargos = $menuCargos;

        return $this;
    }

    /**
     * Get menuCargos
     *
     * @return boolean
     */
    public function getMenuCargos()
    {
        return $this->menuCargos;
    }

    /**
     * Set menuLogs
     *
     * @param boolean $menuLogs
     *
     * @return Administrador
     */
    public function setMenuLogs($menuLogs)
    {
        $this->menuLogs = $menuLogs;

        return $this;
    }

    /**
     * Get menuLogs
     *
     * @return boolean
     */
    public function getMenuLogs()
    {
        return $this->menuLogs;
    }

    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Administrador
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
    public function eraseCredentials() {
      $this->password = null;
    }

    public function getPassword() {
        return $this->getContrasena();
    }

    public function getRoles() {
        return array('ROLE_ADMINISTRATOR');
    }

    public function getSalt() {
        
    }

    public function getUsername() {
        return $this->getUsuario();
    }
}
