<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CPlanillasEmpleado
 *
 * @ORM\Table(name="c_planillas_empleado")
 * @ORM\Entity
 */
class CPlanillasEmpleado
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
     * @var $planilla Planillas/CoreBundle/Entity/CPlanillas
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillas")
     */
    private $planilla;
    /**
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;
     /**
     * @var decimal
     *
     * @ORM\Column(name="salario_periodo", type="decimal", nullable=false)
     */
    private $salario_periodo;
     /**
     * @var decimal
     *
     * @ORM\Column(name="salario_total", type="decimal", nullable=false)
     */
    private $salario_total;
     /**
     * @var decimal
     *
     * @ORM\Column(name="salario_seguro", type="decimal", nullable=true)
     */
    private $salario_seguro;
    



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
     * Set salario_periodo
     *
     * @param float $salarioPeriodo
     * @return CPlanillasEmpleado
     */
    public function setSalarioPeriodo($salarioPeriodo)
    {
        $this->salario_periodo = $salarioPeriodo;
    
        return $this;
    }

    /**
     * Get salario_periodo
     *
     * @return float 
     */
    public function getSalarioPeriodo()
    {
        return $this->salario_periodo;
    }

    /**
     * Set salario_total
     *
     * @param float $salarioTotal
     * @return CPlanillasEmpleado
     */
    public function setSalarioTotal($salarioTotal)
    {
        $this->salario_total = $salarioTotal;
    
        return $this;
    }

    /**
     * Get salario_total
     *
     * @return float 
     */
    public function getSalarioTotal()
    {
        return $this->salario_total;
    }

    /**
     * Set salario_seguro
     *
     * @param float $salarioSeguro
     * @return CPlanillasEmpleado
     */
    public function setSalarioSeguro($salarioSeguro)
    {
        $this->salario_seguro = $salarioSeguro;
    
        return $this;
    }

    /**
     * Get salario_seguro
     *
     * @return float 
     */
    public function getSalarioSeguro()
    {
        return $this->salario_seguro;
    }

    /**
     * Set planilla
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillas $planilla
     * @return CPlanillasEmpleado
     */
    public function setPlanilla(\Planillas\CoreBundle\Entity\CPlanillas $planilla = null)
    {
        $this->planilla = $planilla;
    
        return $this;
    }

    /**
     * Get planilla
     *
     * @return \Planillas\CoreBundle\Entity\CPlanillas 
     */
    public function getPlanilla()
    {
        return $this->planilla;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CPlanillasEmpleado
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