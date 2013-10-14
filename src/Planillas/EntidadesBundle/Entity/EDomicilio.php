<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EDomicilio
 *
 * @ORM\Table(name="e_domicilio")
 * @ORM\Entity
 */
class EDomicilio
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
     * @var $distrito Planillas/NomencladorBundle/Entity/NDistrito
     *
     *  @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NDistrito")
     */
    private $distrito;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion_exacta", type="string", length=255, nullable=true)
     */
    private $direccionExacta;

    /**
     * @var string
     *
     * @ORM\Column(name="canton", type="string", length=64, nullable=true)
     */
    private $canton;

    /**
     * @var integer
     *
     * @ORM\Column(name="tiempo_residencia", type="integer", nullable=true)
     */
    private $tiempoResidencia;

    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="domicilios")
     */
    private $empleado;



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
     * Set direccionExacta
     *
     * @param string $direccionExacta
     * @return EDomicilio
     */
    public function setDireccionExacta($direccionExacta)
    {
        $this->direccionExacta = $direccionExacta;
    
        return $this;
    }

    /**
     * Get direccionExacta
     *
     * @return string 
     */
    public function getDireccionExacta()
    {
        return $this->direccionExacta;
    }

    /**
     * Set canton
     *
     * @param string $canton
     * @return EDomicilio
     */
    public function setCanton($canton)
    {
        $this->canton = $canton;
    
        return $this;
    }

    /**
     * Get canton
     *
     * @return string 
     */
    public function getCanton()
    {
        return $this->canton;
    }

    /**
     * Set tiempoResidencia
     *
     * @param integer $tiempoResidencia
     * @return EDomicilio
     */
    public function setTiempoResidencia($tiempoResidencia)
    {
        $this->tiempoResidencia = $tiempoResidencia;
    
        return $this;
    }

    /**
     * Get tiempoResidencia
     *
     * @return integer 
     */
    public function getTiempoResidencia()
    {
        return $this->tiempoResidencia;
    }

    /**
     * Set distrito
     *
     * @param \Planillas\NomencladorBundle\Entity\NDistrito $distrito
     * @return EDomicilio
     */
    public function setDistrito(\Planillas\NomencladorBundle\Entity\NDistrito $distrito = null)
    {
        $this->distrito = $distrito;
    
        return $this;
    }

    /**
     * Get distrito
     *
     * @return \Planillas\NomencladorBundle\Entity\NDistrito 
     */
    public function getDistrito()
    {
        return $this->distrito;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EDomicilio
     */
    public function setEmpleado(\Planillas\CoreBundle\Entity\CEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return \Planillas\CoreBundle\Entity\CEmpleado 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }
}