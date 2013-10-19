<?php

namespace AT\votacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblUsuarios
 *
 * @ORM\Table(name="tbl_usuarios")
 * @ORM\Entity
 */
class TblUsuarios
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
     * @ORM\Column(name="usuario_email", type="string", length=60, nullable=false)
     */
    private $usuarioEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_nombre", type="string", length=60, nullable=false)
     */
    private $usuarioNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_apellido", type="string", length=60, nullable=false)
     */
    private $usuarioApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_tipo_documento", type="string", length=60, nullable=true)
     */
    private $usuarioTipoDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_documento", type="string", length=45, nullable=false)
     */
    private $usuarioDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_rol", type="string", length=45, nullable=false)
     */
    private $usuarioRol;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_profesion", type="string", length=60, nullable=true)
     */
    private $usuarioProfesion;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_telefono", type="string", length=45, nullable=true)
     */
    private $usuarioTelefono;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_celular", type="string", length=45, nullable=true)
     */
    private $usuarioCelular;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usuario_activado", type="boolean", nullable=true)
     */
    private $usuarioActivado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="permiso_nuevos_usuarios", type="boolean", nullable=true)
     */
    private $permisoNuevosUsuarios;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usuario_fecha_nacimiento", type="date", nullable=true)
     */
    private $usuarioFechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_password", type="string", length=45, nullable=true)
     */
    private $usuarioPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario_hash", type="string", length=100, nullable=true)
     */
    private $usuarioHash;



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
     * Set usuarioEmail
     *
     * @param string $usuarioEmail
     * @return TblUsuarios
     */
    public function setUsuarioEmail($usuarioEmail)
    {
        $this->usuarioEmail = $usuarioEmail;
    
        return $this;
    }

    /**
     * Get usuarioEmail
     *
     * @return string 
     */
    public function getUsuarioEmail()
    {
        return $this->usuarioEmail;
    }

    /**
     * Set usuarioNombre
     *
     * @param string $usuarioNombre
     * @return TblUsuarios
     */
    public function setUsuarioNombre($usuarioNombre)
    {
        $this->usuarioNombre = $usuarioNombre;
    
        return $this;
    }

    /**
     * Get usuarioNombre
     *
     * @return string 
     */
    public function getUsuarioNombre()
    {
        return $this->usuarioNombre;
    }

    /**
     * Set usuarioApellido
     *
     * @param string $usuarioApellido
     * @return TblUsuarios
     */
    public function setUsuarioApellido($usuarioApellido)
    {
        $this->usuarioApellido = $usuarioApellido;
    
        return $this;
    }

    /**
     * Get usuarioApellido
     *
     * @return string 
     */
    public function getUsuarioApellido()
    {
        return $this->usuarioApellido;
    }

    /**
     * Set usuarioTipoDocumento
     *
     * @param string $usuarioTipoDocumento
     * @return TblUsuarios
     */
    public function setUsuarioTipoDocumento($usuarioTipoDocumento)
    {
        $this->usuarioTipoDocumento = $usuarioTipoDocumento;
    
        return $this;
    }

    /**
     * Get usuarioTipoDocumento
     *
     * @return string 
     */
    public function getUsuarioTipoDocumento()
    {
        return $this->usuarioTipoDocumento;
    }

    /**
     * Set usuarioDocumento
     *
     * @param string $usuarioDocumento
     * @return TblUsuarios
     */
    public function setUsuarioDocumento($usuarioDocumento)
    {
        $this->usuarioDocumento = $usuarioDocumento;
    
        return $this;
    }

    /**
     * Get usuarioDocumento
     *
     * @return string 
     */
    public function getUsuarioDocumento()
    {
        return $this->usuarioDocumento;
    }

    /**
     * Set usuarioRol
     *
     * @param string $usuarioRol
     * @return TblUsuarios
     */
    public function setUsuarioRol($usuarioRol)
    {
        $this->usuarioRol = $usuarioRol;
    
        return $this;
    }

    /**
     * Get usuarioRol
     *
     * @return string 
     */
    public function getUsuarioRol()
    {
        return $this->usuarioRol;
    }

    /**
     * Set usuarioProfesion
     *
     * @param string $usuarioProfesion
     * @return TblUsuarios
     */
    public function setUsuarioProfesion($usuarioProfesion)
    {
        $this->usuarioProfesion = $usuarioProfesion;
    
        return $this;
    }

    /**
     * Get usuarioProfesion
     *
     * @return string 
     */
    public function getUsuarioProfesion()
    {
        return $this->usuarioProfesion;
    }

    /**
     * Set usuarioTelefono
     *
     * @param string $usuarioTelefono
     * @return TblUsuarios
     */
    public function setUsuarioTelefono($usuarioTelefono)
    {
        $this->usuarioTelefono = $usuarioTelefono;
    
        return $this;
    }

    /**
     * Get usuarioTelefono
     *
     * @return string 
     */
    public function getUsuarioTelefono()
    {
        return $this->usuarioTelefono;
    }

    /**
     * Set usuarioCelular
     *
     * @param string $usuarioCelular
     * @return TblUsuarios
     */
    public function setUsuarioCelular($usuarioCelular)
    {
        $this->usuarioCelular = $usuarioCelular;
    
        return $this;
    }

    /**
     * Get usuarioCelular
     *
     * @return string 
     */
    public function getUsuarioCelular()
    {
        return $this->usuarioCelular;
    }

    /**
     * Set usuarioActivado
     *
     * @param boolean $usuarioActivado
     * @return TblUsuarios
     */
    public function setUsuarioActivado($usuarioActivado)
    {
        $this->usuarioActivado = $usuarioActivado;
    
        return $this;
    }

    /**
     * Get usuarioActivado
     *
     * @return boolean 
     */
    public function getUsuarioActivado()
    {
        return $this->usuarioActivado;
    }

    /**
     * Set permisoNuevosUsuarios
     *
     * @param boolean $permisoNuevosUsuarios
     * @return TblUsuarios
     */
    public function setPermisoNuevosUsuarios($permisoNuevosUsuarios)
    {
        $this->permisoNuevosUsuarios = $permisoNuevosUsuarios;
    
        return $this;
    }

    /**
     * Get permisoNuevosUsuarios
     *
     * @return boolean 
     */
    public function getPermisoNuevosUsuarios()
    {
        return $this->permisoNuevosUsuarios;
    }

    /**
     * Set usuarioFechaNacimiento
     *
     * @param \DateTime $usuarioFechaNacimiento
     * @return TblUsuarios
     */
    public function setUsuarioFechaNacimiento($usuarioFechaNacimiento)
    {
        $this->usuarioFechaNacimiento = $usuarioFechaNacimiento;
    
        return $this;
    }

    /**
     * Get usuarioFechaNacimiento
     *
     * @return \DateTime 
     */
    public function getUsuarioFechaNacimiento()
    {
        return $this->usuarioFechaNacimiento;
    }

    /**
     * Set usuarioPassword
     *
     * @param string $usuarioPassword
     * @return TblUsuarios
     */
    public function setUsuarioPassword($usuarioPassword)
    {
        $this->usuarioPassword = $usuarioPassword;
    
        return $this;
    }

    /**
     * Get usuarioPassword
     *
     * @return string 
     */
    public function getUsuarioPassword()
    {
        return $this->usuarioPassword;
    }

    /**
     * Set usuarioHash
     *
     * @param string $usuarioHash
     * @return TblUsuarios
     */
    public function setUsuarioHash($usuarioHash)
    {
        $this->usuarioHash = $usuarioHash;
    
        return $this;
    }

    /**
     * Get usuarioHash
     *
     * @return string 
     */
    public function getUsuarioHash()
    {
        return $this->usuarioHash;
    }
}