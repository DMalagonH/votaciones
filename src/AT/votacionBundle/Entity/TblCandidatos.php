<?php

namespace AT\votacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TblCandidatos
 *
 * @ORM\Table(name="tbl_candidatos")
 * @ORM\Entity
 */
class TblCandidatos
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
     * @ORM\Column(name="candidato_nombre", type="string", length=60, nullable=false)
     */
    private $candidatoNombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="candidato_no_tarjeton", type="integer", nullable=false)
     */
    private $candidatoNoTarjeton;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_imagen", type="string", length=60, nullable=true)
     */
    private $candidatoImagen;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_partido", type="string", length=60, nullable=true)
     */
    private $candidatoPartido;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_descripcion_corta", type="text", nullable=true)
     */
    private $candidatoDescripcionCorta;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_descripcion", type="text", nullable=true)
     */
    private $candidatoDescripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_link_facebook", type="string", length=100, nullable=true)
     */
    private $candidatoLinkFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_link_twiter", type="string", length=100, nullable=true)
     */
    private $candidatoLinkTwiter;

    /**
     * @var string
     *
     * @ORM\Column(name="candidato_link_linkedin", type="string", length=100, nullable=true)
     */
    private $candidatoLinkLinkedin;



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
     * Set candidatoNombre
     *
     * @param string $candidatoNombre
     * @return TblCandidatos
     */
    public function setCandidatoNombre($candidatoNombre)
    {
        $this->candidatoNombre = $candidatoNombre;
    
        return $this;
    }

    /**
     * Get candidatoNombre
     *
     * @return string 
     */
    public function getCandidatoNombre()
    {
        return $this->candidatoNombre;
    }

    /**
     * Set candidatoNoTarjeton
     *
     * @param integer $candidatoNoTarjeton
     * @return TblCandidatos
     */
    public function setCandidatoNoTarjeton($candidatoNoTarjeton)
    {
        $this->candidatoNoTarjeton = $candidatoNoTarjeton;
    
        return $this;
    }

    /**
     * Get candidatoNoTarjeton
     *
     * @return integer 
     */
    public function getCandidatoNoTarjeton()
    {
        return $this->candidatoNoTarjeton;
    }

    /**
     * Set candidatoImagen
     *
     * @param string $candidatoImagen
     * @return TblCandidatos
     */
    public function setCandidatoImagen($candidatoImagen)
    {
        $this->candidatoImagen = $candidatoImagen;
    
        return $this;
    }

    /**
     * Get candidatoImagen
     *
     * @return string 
     */
    public function getCandidatoImagen()
    {
        return $this->candidatoImagen;
    }

    /**
     * Set candidatoPartido
     *
     * @param string $candidatoPartido
     * @return TblCandidatos
     */
    public function setCandidatoPartido($candidatoPartido)
    {
        $this->candidatoPartido = $candidatoPartido;
    
        return $this;
    }

    /**
     * Get candidatoPartido
     *
     * @return string 
     */
    public function getCandidatoPartido()
    {
        return $this->candidatoPartido;
    }

    /**
     * Set candidatoDescripcionCorta
     *
     * @param string $candidatoDescripcionCorta
     * @return TblCandidatos
     */
    public function setCandidatoDescripcionCorta($candidatoDescripcionCorta)
    {
        $this->candidatoDescripcionCorta = $candidatoDescripcionCorta;
    
        return $this;
    }

    /**
     * Get candidatoDescripcionCorta
     *
     * @return string 
     */
    public function getCandidatoDescripcionCorta()
    {
        return $this->candidatoDescripcionCorta;
    }

    /**
     * Set candidatoDescripcion
     *
     * @param string $candidatoDescripcion
     * @return TblCandidatos
     */
    public function setCandidatoDescripcion($candidatoDescripcion)
    {
        $this->candidatoDescripcion = $candidatoDescripcion;
    
        return $this;
    }

    /**
     * Get candidatoDescripcion
     *
     * @return string 
     */
    public function getCandidatoDescripcion()
    {
        return $this->candidatoDescripcion;
    }

    /**
     * Set candidatoLinkFacebook
     *
     * @param string $candidatoLinkFacebook
     * @return TblCandidatos
     */
    public function setCandidatoLinkFacebook($candidatoLinkFacebook)
    {
        $this->candidatoLinkFacebook = $candidatoLinkFacebook;
    
        return $this;
    }

    /**
     * Get candidatoLinkFacebook
     *
     * @return string 
     */
    public function getCandidatoLinkFacebook()
    {
        return $this->candidatoLinkFacebook;
    }

    /**
     * Set candidatoLinkTwiter
     *
     * @param string $candidatoLinkTwiter
     * @return TblCandidatos
     */
    public function setCandidatoLinkTwiter($candidatoLinkTwiter)
    {
        $this->candidatoLinkTwiter = $candidatoLinkTwiter;
    
        return $this;
    }

    /**
     * Get candidatoLinkTwiter
     *
     * @return string 
     */
    public function getCandidatoLinkTwiter()
    {
        return $this->candidatoLinkTwiter;
    }

    /**
     * Set candidatoLinkLinkedin
     *
     * @param string $candidatoLinkLinkedin
     * @return TblCandidatos
     */
    public function setCandidatoLinkLinkedin($candidatoLinkLinkedin)
    {
        $this->candidatoLinkLinkedin = $candidatoLinkLinkedin;
    
        return $this;
    }

    /**
     * Get candidatoLinkLinkedin
     *
     * @return string 
     */
    public function getCandidatoLinkLinkedin()
    {
        return $this->candidatoLinkLinkedin;
    }
}