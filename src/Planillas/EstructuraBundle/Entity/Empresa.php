<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Empresa
 *
 * @ORM\Table(name="e_estructura_empresa")
 * @ORM\Entity(repositoryClass="Planillas\EstructuraBundle\Entity\Repository\EmpresaRepository")
 */
class Empresa implements EntityEstructuraInterface
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cliente", mappedBy="empresa", cascade={"persist","remove"})
     */
    private $clientes;

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
     * @return Empresa
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
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add clientes
     *
     * @param \Planillas\EstructuraBundle\Entity\Cliente $clientes
     * @return Empresa
     */
    public function addCliente(\Planillas\EstructuraBundle\Entity\Cliente $clientes)
    {
        $this->clientes[] = $clientes;
    
        return $this;
    }

    /**
     * Remove clientes
     *
     * @param \Planillas\EstructuraBundle\Entity\Cliente $clientes
     */
    public function removeCliente(\Planillas\EstructuraBundle\Entity\Cliente $clientes)
    {
        $this->clientes->removeElement($clientes);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClientes()
    {
        return $this->clientes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }
}