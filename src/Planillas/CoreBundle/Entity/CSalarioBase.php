<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CSalarioBase
 *
 * @ORM\Table(name="c_salario_base")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CSalarioBaseRepository")
 */
class CSalarioBase
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
     * @var $empleado CEmpleado
     *
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="salarioBase")
     */
    private $empleado;

    /**
     * @var string
     *
     * @ORM\Column(name="salario_base", type="decimal", nullable=false)
     */
    private $salarioBase;

    /**
     * @var boolean
     *
     * @ORM\Column(name="seguro", type="boolean", nullable=true)
     */
    private $seguro;

    /**
     * @var integer
     *
     * @ORM\Column(name="periodo_pago", type="integer", nullable=true)
     */
    private $periodoPago;

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
     * Set salarioBase
     *
     * @param  float        $salarioBase
     * @return CSalarioBase
     */
    public function setSalarioBase($salarioBase)
    {
        $this->salarioBase = $salarioBase;

        return $this;
    }

    /**
     * Get salarioBase
     *
     * @return float
     */
    public function getSalarioBase()
    {
        return $this->salarioBase;
    }

    /**
     * Set seguro
     *
     * @param  boolean      $seguro
     * @return CSalarioBase
     */
    public function setSeguro($seguro)
    {
        $this->seguro = $seguro;

        return $this;
    }

    /**
     * Get seguro
     *
     * @return boolean
     */
    public function getSeguro()
    {
        return $this->seguro;
    }

    /**
     * Set periodoPago
     *
     * @param  integer      $periodoPago
     * @return CSalarioBase
     */
    public function setPeriodoPago($periodoPago)
    {
        $this->periodoPago = $periodoPago;

        return $this;
    }

    /**
     * Get periodoPago
     *
     * @return integer
     */
    public function getPeriodoPago()
    {
        return $this->periodoPago;
    }

    /**
     * Set empleado
     *
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CSalarioBase
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
