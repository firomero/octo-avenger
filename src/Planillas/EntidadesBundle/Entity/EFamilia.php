<?php

namespace Planillas\EntidadesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EFamilia
 *
 * @ORM\Table(name="e_familia")
 * @ORM\Entity
 */
class EFamilia
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
     * @var $empleado Planillas/CoreBundle/Entity/CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CEmpleado", inversedBy="familiares")
     */
    private $empleado;

    /**
     * @var $parentesco Planillas/NomencladorBundle/Entity/NParentesco
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NParentesco")
     */
    private $parentesco;

    /**
     * @var $ocupacion Planillas/NomencladorBundle/Entity/NOcupacion
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NOcupacion")
     */
    private $ocupacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="edad", type="integer", nullable=true)
     */
    private $edad;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;


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
     * Set edad
     *
     * @param integer $edad
     * @return EFamilia
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;
    
        return $this;
    }

    /**
     * Get edad
     *
     * @return integer 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return EFamilia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set empleado
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $empleado
     * @return EFamilia
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
     * Set parentesco
     *
     * @param \Planillas\NomencladorBundle\Entity\NParentesco $parentesco
     * @return EFamilia
     */
    public function setParentesco(\Planillas\NomencladorBundle\Entity\NParentesco $parentesco = null)
    {
        $this->parentesco = $parentesco;
    
        return $this;
    }

    /**
     * Get parentesco
     *
     * @return \Planillas\NomencladorBundle\Entity\NParentesco 
     */
    public function getParentesco()
    {
        return $this->parentesco;
    }

    /**
     * Set ocupacion
     *
     * @param \Planillas\NomencladorBundle\Entity\NOcupacion $ocupacion
     * @return EFamilia
     */
    public function setOcupacion(\Planillas\NomencladorBundle\Entity\NOcupacion $ocupacion = null)
    {
        $this->ocupacion = $ocupacion;
    
        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return \Planillas\NomencladorBundle\Entity\NOcupacion 
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }
    public function getEmpleadoId()
    {
        return  $this->empleado->id;
    }
}