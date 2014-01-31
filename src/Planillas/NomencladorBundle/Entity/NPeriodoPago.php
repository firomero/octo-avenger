<?php

namespace Planillas\NomencladorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NPeriodoPago
 *
 * @ORM\Table(name="n_periodopago")
 * @ORM\Entity
 */
class NPeriodoPago
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
     * @ORM\Column(name="periodo", type="string", length=40, nullable=false)
     */
    private $periodo;

    /**
     * @var boolean
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;


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
     * Set periodo
     *
     * @param string $periodo
     * @return NPeriodoPago
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    
        return $this;
    }

    /**
     * Get periodo
     *
     * @return string 
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return NPeriodoPago
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }
    public function __toString() {
        return $this->periodo;
    }
}
