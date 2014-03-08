<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * CSolicitudEmpleo
 *
 * @ORM\Table(name="c_solicitud_empleo")
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CSolicitudEmpleoRepository")
 */
class CSolicitudEmpleo
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
     * @var decimal
     *
     * @ORM\Column(name="salario", type="decimal", nullable=true)
     */
    private $salario;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(([a-zA-ZñÑáéíóúÁÉÍÓÚ])([ ])*)+$/", message="El nombre no es correcto")
     * @ORM\Column(name="nombre", type="string", length=32, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(([a-zA-ZñÑáéíóúÁÉÍÓÚ])([ ])*)+$/", message="Los apellidos no son correcto")
     * @ORM\Column(name="apellidos", type="string", length=64, nullable=false)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=15, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=64, nullable=true)
     */
    private $correo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var $vacante CVacante
     *
     * @ORM\ManyToOne(targetEntity="CVacante")
     */
    private $vacante;


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
     * Set salario
     *
     * @param float $salario
     * @return CSolicitudEmpleo
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
     * Set nombre
     *
     * @param string $nombre
     * @return CSolicitudEmpleo
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return CSolicitudEmpleo
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    
        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return CSolicitudEmpleo
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return CSolicitudEmpleo
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    
        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return CSolicitudEmpleo
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set vacante
     *
     * @param \Planillas\CoreBundle\Entity\CVacante $vacante
     * @return CSolicitudEmpleo
     */
    public function setVacante(\Planillas\CoreBundle\Entity\CVacante $vacante = null)
    {
        $this->vacante = $vacante;
    
        return $this;
    }

    /**
     * Get vacante
     *
     * @return \Planillas\CoreBundle\Entity\CVacante 
     */
    public function getVacante()
    {
        return $this->vacante;
    }
}