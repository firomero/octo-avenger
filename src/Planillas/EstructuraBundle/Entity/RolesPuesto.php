<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RolesPuesto
 *
 * @ORM\Table(name="e_estructura_roles_puesto")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\RolesPuestoRepository")
 */
class RolesPuesto
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
     * @ORM\ManyToOne(targetEntity="Planillas\EstructuraBundle\Entity\Puesto", inversedBy="roles")
     */
    private $puesto;

    /**
     * @var  \Planillas\CoreBundle\Entity\CHorario $rol
     *
     * @ORM\ManyToOne(targetEntity="Planillas\CoreBundle\Entity\CHorario")
     */
    private $rol;


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
     * Set puesto
     *
     * @param \Planillas\EstructuraBundle\Entity\Puesto $puesto
     * @return RolesPuesto
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
     * Set rol
     *
     * @param \Planillas\CoreBundle\Entity\CHorario $rol
     * @return RolesPuesto
     */
    public function setRol(\Planillas\CoreBundle\Entity\CHorario $rol = null)
    {
        $this->rol = $rol;
    
        return $this;
    }

    /**
     * Get rol
     *
     * @return \Planillas\CoreBundle\Entity\CHorario 
     */
    public function getRol()
    {
        return $this->rol;
    }
}