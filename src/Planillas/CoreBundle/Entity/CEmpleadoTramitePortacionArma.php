<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CEmpleadoTramitePortacionArma
 *
 * @ORM\Table(name="c_empleado_tramite_portacion_arma")
 * @ORM\Entity
 */
class CEmpleadoTramitePortacionArma
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
     * @var  \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CTramitePortacionArma", mappedBy="empleadoTramitePortacionArma", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $tramites;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var  \Planillas\CoreBundle\Entity\CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado")
     */
    private $empleado;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tramites = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return CEmpleadoTramitePortacionArma
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Add tramites
     *
     * @param \Planillas\CoreBundle\Entity\CTramitePortacionArma $tramites
     * @return CEmpleadoTramitePortacionArma
     */
    public function addTramite(\Planillas\CoreBundle\Entity\CTramitePortacionArma $tramites)
    {
        $tramites->setEmpleadoTramitePortacionArma($this);

        $this->tramites[] = $tramites;
    
        return $this;
    }

    /**
     * Remove tramites
     *
     * @param \Planillas\CoreBundle\Entity\CTramitePortacionArma $tramites
     */
    public function removeTramite(\Planillas\CoreBundle\Entity\CTramitePortacionArma $tramites)
    {
        $this->tramites->removeElement($tramites);
    }

    /**
     * Get tramites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTramites()
    {
        return $this->tramites;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return CEmpleadoTramitePortacionArma
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