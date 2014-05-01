<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Planillas\EntidadesBundle\Entity\EComponentesSalariales;

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
     * @var $planilla \Planillas\CoreBundle\Entity\CPlanillas
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CPlanillas", inversedBy="planillasEmpleados")
     */
    private $planilla;

    /**
     * @var $empleado \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

     /**
     * @var float
     *
     * @ORM\Column(name="salario_periodo", type="decimal", nullable=false, scale=2)
     */
    private $salario_periodo;

     /**
     * @var float
     *
     * @ORM\Column(name="salario_total", type="decimal", nullable=false, scale=2)
     */
    private $salario_total;

     /**
     * @var float
     *
     * @ORM\Column(name="salario_seguro", type="decimal", nullable=true, scale=2)
     */
    private $salario_seguro;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $horasExtras
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CHorasExtras", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $horasExtras;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $diasExtras
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CDiasExtra", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $diasExtras;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $ausencias
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CAusencias", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $ausencias;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $incapacidades
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CIncapacidades", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $incapacidades;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $deudas

     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CDeudas", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $deudas;

    /**
     * @var  \Doctrine\Common\Collections\ArrayCollection $componentesPermanentes
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $componentesPermanentes;

    /**
     * @var  \Doctrine\Common\Collections\ArrayCollection $componentesSalariales
     *
     * @ORM\OneToMany(targetEntity="Planillas\EntidadesBundle\Entity\EComponentesSalariales", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $componentesSalariales;

    /**
     * @var  \Doctrine\Common\Collections\ArrayCollection $bonificacionesPuesto
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto", mappedBy="planillaEmpleado", cascade={"persist","remove"})
     */
    private $bonificacionesPuesto;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horasExtras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->diasExtras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ausencias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->deudas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->componentesPermanentes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->componentesSalariales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bonificacionesPuesto = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set salario_periodo
     *
     * @param  float              $salarioPeriodo
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
     * @param  float              $salarioTotal
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
     * @param  float              $salarioSeguro
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
     * @param  \Planillas\CoreBundle\Entity\CPlanillas $planilla
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
     * @param  \Planillas\CoreBundle\Entity\CEmpleado $empleado
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

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $horasExtras
     */
    public function setHorasExtras($horasExtras)
    {
        $this->horasExtras = $horasExtras;
    }

    /**
     * Adiciona una nueva hora extra en la planilla
     *
     * @param CHorasExtras $horasExtra
     * @return $this
     */
    public function addHorasExtra(CHorasExtras $horasExtra)
    {
        $horasExtra->setPlanillaEmpleado($this);

        $this->horasExtras[] = $horasExtra;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getHorasExtras()
    {
        return $this->horasExtras;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $diasExtras
     */
    public function setDiasExtras($diasExtras)
    {
        $this->diasExtras = $diasExtras;
    }

    /**
     * @param CDiasExtra $diasExtra
     * @return $this
     */
    public function addDiasExtra(CDiasExtra $diasExtra)
    {
        $diasExtra->setPlanillaEmpleado($this);

        $this->diasExtras[] = $diasExtra;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDiasExtras()
    {
        return $this->diasExtras;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $ausencias
     */
    public function setAusencias($ausencias)
    {
        $this->ausencias = $ausencias;
    }

    /**
     * @param CAusencias $ausencias
     * @return $this
     */
    public function addAusencia(CAusencias $ausencias)
    {
        $ausencias->setPlanillaEmpleado($this);

        $this->ausencias[] = $ausencias;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAusencias()
    {
        return $this->ausencias;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $incapacidades
     */
    public function setIncapacidades($incapacidades)
    {
        $this->incapacidades = $incapacidades;
    }

    /**
     * @param CIncapacidades $incapacidades
     * @return $this
     */
    public function addIncapacidad(CIncapacidades $incapacidades)
    {
        $incapacidades->setPlanillaEmpleado($this);

        $this->incapacidades[] = $incapacidades;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getIncapacidades()
    {
        return $this->incapacidades;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $deudas
     */
    public function setDeudas($deudas)
    {
        $this->deudas = $deudas;
    }

    /**
     * @param CDeudas $deudas
     * @return $this
     */
    public function addDeuda(CDeudas $deudas)
    {
        $deudas->setPlanillaEmpleado($this);

        $this->deudas[] = $deudas;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDeudas()
    {
        return $this->deudas;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $componentesPermanentes
     */
    public function setComponentesPermanentes($componentesPermanentes)
    {
        $this->componentesPermanentes = $componentesPermanentes;
    }

    /**
     * @param CPlanillasComponentesPermanentes $componentesPermanentes
     * @return $this
     */
    public function addComponentePermanente(CPlanillasComponentesPermanentes $componentesPermanentes)
    {
        $componentesPermanentes->setPlanillaEmpleado($this);

        $this->componentesPermanentes[] = $componentesPermanentes;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getComponentesPermanentes()
    {
        return $this->componentesPermanentes;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $componentesSalariales
     */
    public function setComponentesSalariales($componentesSalariales)
    {
        $this->componentesSalariales = $componentesSalariales;
    }

    /**
     * @param EComponentesSalariales $componentesSalariales
     * @return $this
     */
    public function addComponentesSalarial(EComponentesSalariales $componentesSalariales)
    {
        $componentesSalariales->setPlanillaEmpleado($this);

        $this->componentesSalariales[] = $componentesSalariales;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getComponentesSalariales()
    {
        return $this->componentesSalariales;
    }

    /**
     * Remove horasExtras
     *
     * @param \Planillas\CoreBundle\Entity\CHorasExtras $horasExtras
     */
    public function removeHorasExtra(\Planillas\CoreBundle\Entity\CHorasExtras $horasExtras)
    {
        $this->horasExtras->removeElement($horasExtras);
    }

    /**
     * Remove diasExtras
     *
     * @param \Planillas\CoreBundle\Entity\CDiasExtra $diasExtras
     */
    public function removeDiasExtra(\Planillas\CoreBundle\Entity\CDiasExtra $diasExtras)
    {
        $this->diasExtras->removeElement($diasExtras);
    }

    /**
     * Remove ausencias
     *
     * @param \Planillas\CoreBundle\Entity\CAusencias $ausencias
     */
    public function removeAusencia(\Planillas\CoreBundle\Entity\CAusencias $ausencias)
    {
        $this->ausencias->removeElement($ausencias);
    }

    /**
     * Add incapacidades
     *
     * @param \Planillas\CoreBundle\Entity\CIncapacidades $incapacidades
     * @return CPlanillasEmpleado
     */
    public function addIncapacidade(\Planillas\CoreBundle\Entity\CIncapacidades $incapacidades)
    {
        $incapacidades->setPlanillaEmpleado($this);

        $this->incapacidades[] = $incapacidades;
    
        return $this;
    }

    /**
     * Remove incapacidades
     *
     * @param \Planillas\CoreBundle\Entity\CIncapacidades $incapacidades
     */
    public function removeIncapacidade(\Planillas\CoreBundle\Entity\CIncapacidades $incapacidades)
    {
        $this->incapacidades->removeElement($incapacidades);
    }

    /**
     * Remove deudas
     *
     * @param \Planillas\CoreBundle\Entity\CDeudas $deudas
     */
    public function removeDeuda(\Planillas\CoreBundle\Entity\CDeudas $deudas)
    {
        $this->deudas->removeElement($deudas);
    }

    /**
     * Add componentesPermanentes
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes $componentesPermanentes
     * @return CPlanillasEmpleado
     */
    public function addComponentesPermanente(\Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes $componentesPermanentes)
    {
        $componentesPermanentes->setPlanillaEmpleado($this);

        $this->componentesPermanentes[] = $componentesPermanentes;
    
        return $this;
    }

    /**
     * Remove componentesPermanentes
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes $componentesPermanentes
     */
    public function removeComponentesPermanente(\Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes $componentesPermanentes)
    {
        $this->componentesPermanentes->removeElement($componentesPermanentes);
    }

    /**
     * Add componentesSalariales
     *
     * @param \Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentesSalariales
     * @return CPlanillasEmpleado
     */
    public function addComponentesSalariale(\Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentesSalariales)
    {
        $componentesSalariales->setPlanillaEmpleado($this);

        $this->componentesSalariales[] = $componentesSalariales;
    
        return $this;
    }

    /**
     * Remove componentesSalariales
     *
     * @param \Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentesSalariales
     */
    public function removeComponentesSalariale(\Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentesSalariales)
    {
        $this->componentesSalariales->removeElement($componentesSalariales);
    }

    /**
     * Add bonificacionesPuesto
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto $bonificacionesPuesto
     * @return CPlanillasEmpleado
     */
    public function addBonificacionesPuesto(\Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto $bonificacionesPuesto)
    {
        $bonificacionesPuesto->setPlanillaEmpleado($this);

        $this->bonificacionesPuesto[] = $bonificacionesPuesto;
    
        return $this;
    }

    /**
     * Remove bonificacionesPuesto
     *
     * @param \Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto $bonificacionesPuesto
     */
    public function removeBonificacionesPuesto(\Planillas\CoreBundle\Entity\CPlanillasBonificacionesPuesto $bonificacionesPuesto)
    {
        $this->bonificacionesPuesto->removeElement($bonificacionesPuesto);
    }

    /**
     * Get bonificacionesPuesto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBonificacionesPuesto()
    {
        return $this->bonificacionesPuesto;
    }
}