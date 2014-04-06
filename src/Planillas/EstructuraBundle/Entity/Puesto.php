<?php

namespace Planillas\EstructuraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puesto
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Puesto
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
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", length=255)
     */
    private $rol;

    /**
     * @var float
     *
     * @ORM\Column(name="salario", type="float")
     */
    private $salario;

    /**
     * @var string
     *
     * @ORM\Column(name="bonificacion", type="string", length=255)
     */
    private $bonificacion;


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
     * @param string $nombre
     * @return Puesto
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
     * Set rol
     *
     * @param string $rol
     * @return Puesto
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    
        return $this;
    }

    /**
     * Get rol
     *
     * @return string 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set salario
     *
     * @param float $salario
     * @return Puesto
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;
    
        return $this;
    }

    /**
     * Get salario
     *
     * @return float 
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set bonificacion
     *
     * @param string $bonificacion
     * @return Puesto
     */
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;
    
        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return string 
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }
}
