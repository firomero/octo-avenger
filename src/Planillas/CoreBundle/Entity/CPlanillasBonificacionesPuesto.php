<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto;
use Planillas\CoreBundle\Entity\CEmpleado;

/**
 * CPlanillasBonificacionesPuesto
 *
 * @ORM\Table(name="c_planillas_bonificaciones_puesto")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CPlanillasBonificacionesPuestoRepository")
 */
class CPlanillasBonificacionesPuesto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var $bonificacionPuesto \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto")
     */
    private $bonificacionPuesto;

    /**
     * @var $planilla \Planillas\CoreBundle\Entity\CPlanillasEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillasEmpleado", inversedBy="bonificacionesPuesto")
     */
    private $planillaEmpleado;

    /**
     * @var $empleado \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float")
     */
    private $monto;

    /**
     * Constructor
     */
    public function __construct(BonificacionesEnPuesto $bonificacionesEnPuesto, CEmpleado $empleado)
    {
        $this->bonificacionPuesto   = $bonificacionesEnPuesto;
        $this->empleado             = $empleado;
        $this->monto                = $bonificacionesEnPuesto->getMonto();
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
     * Set monto
     *
     * @param float $monto
     * @return CPlanillasBonificacionesPuesto
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    
        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set bonificacionPuesto
     *
     * @param \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto $bonificacionPuesto
     * @return CPlanillasBonificacionesPuesto
     */
    public function setBonificacionPuesto(\Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto $bonificacionPuesto = null)
    {
        $this->bonificacionPuesto = $bonificacionPuesto;
    
        return $this;
    }

    /**
     * Get bonificacionPuesto
     *
     * @return \Planillas\EstructuraBundle\Entity\BonificacionesEnPuesto 
     */
    public function getBonificacionPuesto()
    {
        return $this->bonificacionPuesto;
    }

    /**
     * Set planillaEmpleado
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     * @return CPlanillasBonificacionesPuesto
     */
    public function setPlanillaEmpleado(\Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado = null)
    {
        $this->planillaEmpleado = $planillaEmpleado;
    
        return $this;
    }

    /**
     * Get planillaEmpleado
     *
     * @return \Planillas\CoreBundle\Entity\CPlanillasEmpleado 
     */
    public function getPlanillaEmpleado()
    {
        return $this->planillaEmpleado;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CPlanillasBonificacionesPuesto
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