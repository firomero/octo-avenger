<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * CPlanillasEmpleado
 *
 * @ORM\Table(name="c_planillas_componentes")
 * @ORM\Entity
 */
class CPlanillasComponentesPermanentes
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
     * @var $componentePermanente Planillas/EntidadesBundle/Entity/EComponentesSalariales
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EntidadesBundle\Entity\EComponentesSalariales")
     */
    private $componentePermanente;

    /**
     * @var $planilla \Planillas\CoreBundle\Entity\CPlanillasEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillasEmpleado", inversedBy="componentesPermanentes")
     */
   private $planillaEmpleado;

   /**
     * @var $empleado Planillas/CoreBundle/Entity/CPlanillas
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
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
     * Set componentePermanente
     *
     * @param \Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentePermanente
     * @return CPlanillasComponentesPermanentes
     */
    public function setComponentePermanente(\Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentePermanente = null)
    {
        $this->componentePermanente = $componentePermanente;
    
        return $this;
    }

    /**
     * Get componentePermanente
     *
     * @return \Planillas\EntidadesBundle\Entity\EComponentesSalariales 
     */
    public function getComponentePermanente()
    {
        return $this->componentePermanente;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CPlanillasComponentesPermanentes
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

    /**
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     */
    public function setPlanillaEmpleado(CPlanillasEmpleado $planillaEmpleado)
    {
        $this->planillaEmpleado = $planillaEmpleado;
    }

    /**
     * @return \Planillas\CoreBundle\Entity\CPlanillasEmpleado
     */
    public function getPlanillaEmpleado()
    {
        return $this->planillaEmpleado;
    }


}