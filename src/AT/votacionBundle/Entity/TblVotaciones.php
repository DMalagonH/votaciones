<?php

namespace AT\votacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblVotaciones
 *
 * @ORM\Table(name="tbl_votaciones")
 * @ORM\Entity
 */
class TblVotaciones
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=45, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="voto_validado", type="boolean", nullable=false)
     */
    private $votoValidado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="voto_fecha", type="datetime", nullable=false)
     */
    private $votoFecha;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usuario_id_validador", type="boolean", nullable=true)
     */
    private $usuarioIdValidador;

    /**
     * @var \TblUsuarios
     *
     * @ORM\ManyToOne(targetEntity="TblUsuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

    /**
     * @var \TblCandidatos
     *
     * @ORM\ManyToOne(targetEntity="TblCandidatos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="candidato_id", referencedColumnName="id")
     * })
     */
    private $candidato;



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
     * Set votoValidado
     *
     * @param boolean $votoValidado
     * @return TblVotaciones
     */
    public function setVotoValidado($votoValidado)
    {
        $this->votoValidado = $votoValidado;
    
        return $this;
    }

    /**
     * Get votoValidado
     *
     * @return boolean 
     */
    public function getVotoValidado()
    {
        return $this->votoValidado;
    }

    /**
     * Set votoFecha
     *
     * @param \DateTime $votoFecha
     * @return TblVotaciones
     */
    public function setVotoFecha($votoFecha)
    {
        $this->votoFecha = $votoFecha;
    
        return $this;
    }

    /**
     * Get votoFecha
     *
     * @return \DateTime 
     */
    public function getVotoFecha()
    {
        return $this->votoFecha;
    }

    /**
     * Set usuarioIdValidador
     *
     * @param boolean $usuarioIdValidador
     * @return TblVotaciones
     */
    public function setUsuarioIdValidador($usuarioIdValidador)
    {
        $this->usuarioIdValidador = $usuarioIdValidador;
    
        return $this;
    }

    /**
     * Get usuarioIdValidador
     *
     * @return boolean 
     */
    public function getUsuarioIdValidador()
    {
        return $this->usuarioIdValidador;
    }

    /**
     * Set usuario
     *
     * @param \AT\votacionBundle\Entity\TblUsuarios $usuario
     * @return TblVotaciones
     */
    public function setUsuario(\AT\votacionBundle\Entity\TblUsuarios $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AT\votacionBundle\Entity\TblUsuarios 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set candidato
     *
     * @param \AT\votacionBundle\Entity\TblCandidatos $candidato
     * @return TblVotaciones
     */
    public function setCandidato(\AT\votacionBundle\Entity\TblCandidatos $candidato = null)
    {
        $this->candidato = $candidato;
    
        return $this;
    }

    /**
     * Get candidato
     *
     * @return \AT\votacionBundle\Entity\TblCandidatos 
     */
    public function getCandidato()
    {
        return $this->candidato;
    }
}