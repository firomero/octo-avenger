<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EInformacionEducacional
 *
 * @ORM\Table(name="e_informacion_educacional")
 * @ORM\Entity
 */
class EInformacionEducacional
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
     * @ORM\Column(name="anno_inicio", type="integer", nullable=true)
     */
    private $annoInicio;

    /**
     * @var integer
     *
     * @ORM\Column(name="anno_fin", type="integer", nullable=true)
     */
    private $annoFin;

    /**
     * @var string
     *
     * @ORM\Column(name="centro_estudios", type="string", length=64, nullable=true)
     */
    private $centroEstudios;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=64, nullable=true)
     */
    private $titulo;

    /**
     * @var $educacion EEducacion
     *
     * @ORM\ManyToOne(targetEntity="EEducacion", inversedBy="informacionEducacional")
     */
    private $educacion;

    /**
     * @var $nivelEducacional Planillas/NomencladorBundle/Entity/NNivelEducacional
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NNivelEducacional")
     */
    private $nivelEducacional;


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
     * Set annoInicio
     *
     * @param integer $annoInicio
     * @return EInformacionEducacional
     */
    public function setAnnoInicio($annoInicio)
    {
        $this->annoInicio = $annoInicio;
    
        return $this;
    }

    /**
     * Get annoInicio
     *
     * @return integer 
     */
    public function getAnnoInicio()
    {
        return $this->annoInicio;
    }

    /**
     * Set annoFin
     *
     * @param integer $annoFin
     * @return EInformacionEducacional
     */
    public function setAnnoFin($annoFin)
    {
        $this->annoFin = $annoFin;
    
        return $this;
    }

    /**
     * Get annoFin
     *
     * @return integer 
     */
    public function getAnnoFin()
    {
        return $this->annoFin;
    }

    /**
     * Set centroEstudios
     *
     * @param string $centroEstudios
     * @return EInformacionEducacional
     */
    public function setCentroEstudios($centroEstudios)
    {
        $this->centroEstudios = $centroEstudios;
    
        return $this;
    }

    /**
     * Get centroEstudios
     *
     * @return string 
     */
    public function getCentroEstudios()
    {
        return $this->centroEstudios;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return EInformacionEducacional
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set educacion
     *
     * @param \Planillas\EntidadesBundle\Entity\EEducacion $educacion
     * @return EInformacionEducacional
     */
    public function setEducacion(\Planillas\EntidadesBundle\Entity\EEducacion $educacion = null)
    {
        $this->educacion = $educacion;
    
        return $this;
    }

    /**
     * Get educacion
     *
     * @return \Planillas\EntidadesBundle\Entity\EEducacion 
     */
    public function getEducacion()
    {
        return $this->educacion;
    }

    /**
     * Set nivelEducacional
     *
     * @param \Planillas\NomencladorBundle\Entity\NNivelEducacional $nivelEducacional
     * @return EInformacionEducacional
     */
    public function setNivelEducacional(\Planillas\NomencladorBundle\Entity\NNivelEducacional $nivelEducacional = null)
    {
        $this->nivelEducacional = $nivelEducacional;
    
        return $this;
    }

    /**
     * Get nivelEducacional
     *
     * @return \Planillas\NomencladorBundle\Entity\NNivelEducacional 
     */
    public function getNivelEducacional()
    {
        return $this->nivelEducacional;
    }
}