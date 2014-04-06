<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cliente
 *
 * @ORM\Table(name="e_estructura_cliente")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\ClienteRepository")
 */
class Cliente implements EntityEstructuraInterface
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var Empresa
     *
     * @ORM\ManyToOne(targetEntity="Empresa", inversedBy="clientes")
     * @Assert\NotBlank()
     */
    private $empresa;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Sucursal", mappedBy="cliente", cascade={"persist","remove"})
     */
    private $sucursales;

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
     * Set nombre
     *
     * @param  string  $nombre
     * @return Cliente
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
     * Constructor
     */
    public function __construct()
    {
        $this->sucursales = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set empresa
     *
     * @param \Planillas\EstructuraBundle\Entity\Empresa $empresa
     * @return Cliente
     */
    public function setEmpresa(\Planillas\EstructuraBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \Planillas\EstructuraBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Add sucursales
     *
     * @param \Planillas\EstructuraBundle\Entity\Sucursal $sucursales
     * @return Cliente
     */
    public function addSucursale(\Planillas\EstructuraBundle\Entity\Sucursal $sucursales)
    {
        $this->sucursales[] = $sucursales;
    
        return $this;
    }

    /**
     * Remove sucursales
     *
     * @param \Planillas\EstructuraBundle\Entity\Sucursal $sucursales
     */
    public function removeSucursale(\Planillas\EstructuraBundle\Entity\Sucursal $sucursales)
    {
        $this->sucursales->removeElement($sucursales);
    }

    /**
     * Get sucursales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSucursales()
    {
        return $this->sucursales;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}