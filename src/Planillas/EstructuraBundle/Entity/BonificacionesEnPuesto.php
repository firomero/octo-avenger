<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BonificacionesEnPuesto
 *
 * @ORM\Table(name="e_bonificaciones_en_puesto")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\BonificacionesEnPuestoRepository")
 */
class BonificacionesEnPuesto
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
     * @var  \Planillas\EstructuraBundle\Entity\Puesto $puesto
     *
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Puesto", inversedBy="bonificaciones")
     */
    private $puesto;

    /**
     * @var  \Planillas\NomencladorBundle\Entity\NBonificacionPuesto $bonificacion
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NBonificacionPuesto")
     */
    private $bonificacion;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="float")
     */
    private $monto;

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
     * @return BonificacionesEnPuesto
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
     * Set puesto
     *
     * @param \Planillas\EstructuraBundle\Entity\Puesto $puesto
     * @return BonificacionesEnPuesto
     */
    public function setPuesto(\Planillas\EstructuraBundle\Entity\Puesto $puesto = null)
    {
        $this->puesto = $puesto;
    
        return $this;
    }

    /**
     * Get puesto
     *
     * @return \Planillas\EstructuraBundle\Entity\Puesto 
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * Set bonificacion
     *
     * @param \Planillas\NomencladorBundle\Entity\NBonificacionPuesto $bonificacion
     * @return BonificacionesEnPuesto
     */
    public function setBonificacion(\Planillas\NomencladorBundle\Entity\NBonificacionPuesto $bonificacion = null)
    {
        $this->bonificacion = $bonificacion;
    
        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return \Planillas\NomencladorBundle\Entity\NBonificacionPuesto 
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }
}