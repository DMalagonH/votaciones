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
}