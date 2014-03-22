<?php

namespace Planillas\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CEmpleado
 * @ORM\Entity(repositoryClass="Planillas\CoreBundle\Entity\Repository\CEmpleadoRepository")
 * @ORM\Table(name="c_empleado")
 */
class CEmpleado {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=32, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(([a-zA-ZñÑáéíóúÁÉÍÓÚ])([ ])*)+$/", message="El nombre no es correcto")
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^([a-zA-ZñÑáéíóúÁÉÍÓÚ])+$/", message="El segundo apellido no es correcto")
     * @ORM\Column(name="segundo_apellido", type="string", length=32, nullable=true)
     */
    private $segundoApellido;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^([a-zA-ZñÑáéíóúÁÉÍÓÚ])+$/", message="El primer apellido no es correcto")     
     * @ORM\Column(name="primer_apellido", type="string", length=32, nullable=true)
     */
    private $primerApellido;

    /**
     * @var string
     * @ORM\Column(name="cedula", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=15)
     */
    private $cedula;

    /**
     * @var boolean
     * @ORM\Column(name="activo", type="boolean", nullable=true)
     */
    private $activo;

    /**
     * @var $sexo Planillas/NomencladorBundle/Entity/NSexo
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NSexo")
     */
    private $sexo;

    /**
     * @var string
     * @ORM\Column(name="foto", type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @var $supervisor CEmpleado
     *
     * @ORM\ManyToOne(targetEntity="CEmpleado")
     */
    private $supervisor;

    /**
     * @var $trabajo CTrabajo
     *
     * @ORM\OneToOne(targetEntity="CTrabajo", mappedBy="empleado")
     */
    private $trabajo;

    /**
     * @var $horario CHorario
     *
     * @ORM\ManyToOne(targetEntity="CHorario", inversedBy="empleado")
     */
    private $horario;

    /**
     * @var $fechaexcepcional CFechaExcepcional
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CFechaExcepcional", mappedBy="empleado")
     */
    private $fechaexcepcional;

    /**
     * @var decimal

     * @ORM\Column(name="salario", type="decimal", nullable=true)
     */
    private $salario;

    /**
     * @var $tipoPagoCasa Planillas/NomencladorBundle/Entity/NTipoPagoCasa
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NTipoPagoCasa")
     */
    private $tipoPagoCasa;

    /**
     * @var $domicilios Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\EntidadesBundle\Entity\EDomicilio", mappedBy="empleado")
     */
    private $domicilios;

    /**
     * @var $domicilios Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CAusencias", mappedBy="empleado")
     */
    private $ausencias;

    /**
     * @var $domicilios Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CIncapacidades", mappedBy="empleado")
     */
    private $incapacidades;

    /**
     * @var $domicilios Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CDiasExtra", mappedBy="empleado")
     */
    private $diasextra;

    /**
     * @var $domicilios Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CHorasExtras", mappedBy="empleado")
     */
    private $horasextras;

    /**
     * @var $domicilios Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\CoreBundle\Entity\CDeudas", mappedBy="empleado")
     */
    private $deudas;

    /**
     * @var string
     *
     * @ORM\Column(name="talla_calzado", type="string", length=10, nullable=true)
     */
    private $tallaCalzado;

    /**
     * @var string
     *
     * @ORM\Column(name="talla_pantalon", type="string", length=10, nullable=true)
     */
    private $tallaPantalon;

    /**
     * @var string
     *
     * @ORM\Column(name="talla_camisa", type="string", length=10, nullable=true)
     */
    private $tallaCamisa;

    /**
     * @var integer
     *
     * @ORM\Column(name="peso", type="integer", nullable=true)
     */
    private $peso;

    /**
     * @var integer
     *
     * @ORM\Column(name="estatura", type="integer", nullable=true)
     */
    private $estatura;

    /**
     * @var string
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=52, nullable=true)
     */
    private $email;

    /**
     * @var decimal
     *
     * @ORM\Column(name="cantidad_deuda", type="decimal", nullable=true)
     */
    private $cantidadDeuda;

    /**
     * @var $cuentasBancos Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Planillas\NomencladorBundle\Entity\NBanco", inversedBy="empleados")
     * @ORM\JoinTable(name="c_empleado_cuenta_banco")
     */
    private $cuentasBancos;

    /**
     * @var $estadoCivil Planillas/NomencladorBundle/Entity/NEstadoCivil
     *
     * @ORM\ManyToOne(targetEntity="Planillas\NomencladorBundle\Entity\NEstadoCivil")
     */
    private $estadoCivil;

    /**
     * @var $gastosPrincipales Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Planillas\EntidadesBundle\Entity\EGastoPrincipal")
     * @ORM\JoinTable(name="c_empleado_gasto_principal")
     */
    private $gastosPrincipales;

    /**
     * @var string
     *
     * @ORM\Column(name="otro_ingreso", type="string", length=52, nullable=true)
     */
    private $otroIngreso;

    /**
     * @var $historiasTrabajos Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\EntidadesBundle\Entity\EHistoriaTrabajo", mappedBy="empleado")
     */
    private $historiasTrabajos;

    /**
     * @var $personasDepend Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\EntidadesBundle\Entity\EPersonaDependen", mappedBy="empleado")
     */
    private $personasDependen;

    /**
     * @var $familiares Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\EntidadesBundle\Entity\EFamilia", mappedBy="empleado")
     */
    private $familiares;

    /**
     * @var $salariobase CSalarioBase
     *
     * @ORM\OneToOne(targetEntity="Planillas\CoreBundle\Entity\CSalarioBase", mappedBy="empleado")
     */
    private $salarioBase;

    /**
     * @var $componentesSalariales Doctrine/Common/Collections/ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Planillas\EntidadesBundle\Entity\EComponentesSalariales", mappedBy="empleado")
     */
    private $componentesSalariales;

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fechaexcepcional = new \Doctrine\Common\Collections\ArrayCollection();
        $this->domicilios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ausencias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->diasextra = new \Doctrine\Common\Collections\ArrayCollection();
        $this->horasextras = new \Doctrine\Common\Collections\ArrayCollection();
        $this->deudas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasBancos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gastosPrincipales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->historiasTrabajos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personasDependen = new \Doctrine\Common\Collections\ArrayCollection();
        $this->familiares = new \Doctrine\Common\Collections\ArrayCollection();
        $this->componentesSalariales = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return CEmpleado
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
     * Set segundoApellido
     *
     * @param string $segundoApellido
     * @return CEmpleado
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;
    
        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string 
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     * @return CEmpleado
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;
    
        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string 
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     * @return CEmpleado
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    
        return $this;
    }

    /**
     * Get cedula
     *
     * @return string 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return CEmpleado
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return CEmpleado
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set salario
     *
     * @param float $salario
     * @return CEmpleado
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
     * Set tallaCalzado
     *
     * @param string $tallaCalzado
     * @return CEmpleado
     */
    public function setTallaCalzado($tallaCalzado)
    {
        $this->tallaCalzado = $tallaCalzado;
    
        return $this;
    }

    /**
     * Get tallaCalzado
     *
     * @return string 
     */
    public function getTallaCalzado()
    {
        return $this->tallaCalzado;
    }

    /**
     * Set tallaPantalon
     *
     * @param string $tallaPantalon
     * @return CEmpleado
     */
    public function setTallaPantalon($tallaPantalon)
    {
        $this->tallaPantalon = $tallaPantalon;
    
        return $this;
    }

    /**
     * Get tallaPantalon
     *
     * @return string 
     */
    public function getTallaPantalon()
    {
        return $this->tallaPantalon;
    }

    /**
     * Set tallaCamisa
     *
     * @param string $tallaCamisa
     * @return CEmpleado
     */
    public function setTallaCamisa($tallaCamisa)
    {
        $this->tallaCamisa = $tallaCamisa;
    
        return $this;
    }

    /**
     * Get tallaCamisa
     *
     * @return string 
     */
    public function getTallaCamisa()
    {
        return $this->tallaCamisa;
    }

    /**
     * Set peso
     *
     * @param integer $peso
     * @return CEmpleado
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
    
        return $this;
    }

    /**
     * Get peso
     *
     * @return integer 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set estatura
     *
     * @param integer $estatura
     * @return CEmpleado
     */
    public function setEstatura($estatura)
    {
        $this->estatura = $estatura;
    
        return $this;
    }

    /**
     * Get estatura
     *
     * @return integer 
     */
    public function getEstatura()
    {
        return $this->estatura;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return CEmpleado
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set cantidadDeuda
     *
     * @param float $cantidadDeuda
     * @return CEmpleado
     */
    public function setCantidadDeuda($cantidadDeuda)
    {
        $this->cantidadDeuda = $cantidadDeuda;
    
        return $this;
    }

    /**
     * Get cantidadDeuda
     *
     * @return float 
     */
    public function getCantidadDeuda()
    {
        return $this->cantidadDeuda;
    }

    /**
     * Set otroIngreso
     *
     * @param string $otroIngreso
     * @return CEmpleado
     */
    public function setOtroIngreso($otroIngreso)
    {
        $this->otroIngreso = $otroIngreso;
    
        return $this;
    }

    /**
     * Get otroIngreso
     *
     * @return string 
     */
    public function getOtroIngreso()
    {
        return $this->otroIngreso;
    }

    /**
     * Set sexo
     *
     * @param \Planillas\NomencladorBundle\Entity\NSexo $sexo
     * @return CEmpleado
     */
    public function setSexo(\Planillas\NomencladorBundle\Entity\NSexo $sexo = null)
    {
        $this->sexo = $sexo;
    
        return $this;
    }

    /**
     * Get sexo
     *
     * @return \Planillas\NomencladorBundle\Entity\NSexo 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set supervisor
     *
     * @param \Planillas\CoreBundle\Entity\CEmpleado $supervisor
     * @return CEmpleado
     */
    public function setSupervisor(\Planillas\CoreBundle\Entity\CEmpleado $supervisor = null)
    {
        $this->supervisor = $supervisor;
    
        return $this;
    }

    /**
     * Get supervisor
     *
     * @return \Planillas\CoreBundle\Entity\CEmpleado 
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set trabajo
     *
     * @param \Planillas\CoreBundle\Entity\CTrabajo $trabajo
     * @return CEmpleado
     */
    public function setTrabajo(\Planillas\CoreBundle\Entity\CTrabajo $trabajo = null)
    {
        $this->trabajo = $trabajo;
    
        return $this;
    }

    /**
     * Get trabajo
     *
     * @return \Planillas\CoreBundle\Entity\CTrabajo 
     */
    public function getTrabajo()
    {
        return $this->trabajo;
    }

    /**
     * Set horario
     *
     * @param \Planillas\CoreBundle\Entity\CHorario $horario
     * @return CEmpleado
     */
    public function setHorario(\Planillas\CoreBundle\Entity\CHorario $horario = null)
    {
        $this->horario = $horario;
    
        return $this;
    }

    /**
     * Get horario
     *
     * @return \Planillas\CoreBundle\Entity\CHorario 
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Add fechaexcepcional
     *
     * @param \Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional
     * @return CEmpleado
     */
    public function addFechaexcepcional(\Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional)
    {
        $this->fechaexcepcional[] = $fechaexcepcional;
    
        return $this;
    }

    /**
     * Remove fechaexcepcional
     *
     * @param \Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional
     */
    public function removeFechaexcepcional(\Planillas\CoreBundle\Entity\CFechaExcepcional $fechaexcepcional)
    {
        $this->fechaexcepcional->removeElement($fechaexcepcional);
    }

    /**
     * Get fechaexcepcional
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFechaexcepcional()
    {
        return $this->fechaexcepcional;
    }

    /**
     * Set tipoPagoCasa
     *
     * @param \Planillas\NomencladorBundle\Entity\NTipoPagoCasa $tipoPagoCasa
     * @return CEmpleado
     */
    public function setTipoPagoCasa(\Planillas\NomencladorBundle\Entity\NTipoPagoCasa $tipoPagoCasa = null)
    {
        $this->tipoPagoCasa = $tipoPagoCasa;
    
        return $this;
    }

    /**
     * Get tipoPagoCasa
     *
     * @return \Planillas\NomencladorBundle\Entity\NTipoPagoCasa 
     */
    public function getTipoPagoCasa()
    {
        return $this->tipoPagoCasa;
    }

    /**
     * Add domicilios
     *
     * @param \Planillas\EntidadesBundle\Entity\EDomicilio $domicilios
     * @return CEmpleado
     */
    public function addDomicilio(\Planillas\EntidadesBundle\Entity\EDomicilio $domicilios)
    {
        $this->domicilios[] = $domicilios;
    
        return $this;
    }

    /**
     * Remove domicilios
     *
     * @param \Planillas\EntidadesBundle\Entity\EDomicilio $domicilios
     */
    public function removeDomicilio(\Planillas\EntidadesBundle\Entity\EDomicilio $domicilios)
    {
        $this->domicilios->removeElement($domicilios);
    }

    /**
     * Get domicilios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDomicilios()
    {
        return $this->domicilios;
    }

    /**
     * Add ausencias
     *
     * @param \Planillas\CoreBundle\Entity\CAusencias $ausencias
     * @return CEmpleado
     */
    public function addAusencia(\Planillas\CoreBundle\Entity\CAusencias $ausencias)
    {
        $this->ausencias[] = $ausencias;
    
        return $this;
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
     * Get ausencias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAusencias()
    {
        return $this->ausencias;
    }

    /**
     * Add incapacidades
     *
     * @param \Planillas\CoreBundle\Entity\CIncapacidades $incapacidades
     * @return CEmpleado
     */
    public function addIncapacidade(\Planillas\CoreBundle\Entity\CIncapacidades $incapacidades)
    {
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
     * Get incapacidades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncapacidades()
    {
        return $this->incapacidades;
    }

    /**
     * Add diasextra
     *
     * @param \Planillas\CoreBundle\Entity\CDiasExtra $diasextra
     * @return CEmpleado
     */
    public function addDiasextra(\Planillas\CoreBundle\Entity\CDiasExtra $diasextra)
    {
        $this->diasextra[] = $diasextra;
    
        return $this;
    }

    /**
     * Remove diasextra
     *
     * @param \Planillas\CoreBundle\Entity\CDiasExtra $diasextra
     */
    public function removeDiasextra(\Planillas\CoreBundle\Entity\CDiasExtra $diasextra)
    {
        $this->diasextra->removeElement($diasextra);
    }

    /**
     * Get diasextra
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDiasextra()
    {
        return $this->diasextra;
    }

    /**
     * Add horasextras
     *
     * @param \Planillas\CoreBundle\Entity\CHorasExtras $horasextras
     * @return CEmpleado
     */
    public function addHorasextra(\Planillas\CoreBundle\Entity\CHorasExtras $horasextras)
    {
        $this->horasextras[] = $horasextras;
    
        return $this;
    }

    /**
     * Remove horasextras
     *
     * @param \Planillas\CoreBundle\Entity\CHorasExtras $horasextras
     */
    public function removeHorasextra(\Planillas\CoreBundle\Entity\CHorasExtras $horasextras)
    {
        $this->horasextras->removeElement($horasextras);
    }

    /**
     * Get horasextras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHorasextras()
    {
        return $this->horasextras;
    }

    /**
     * Add deudas
     *
     * @param \Planillas\CoreBundle\Entity\CDeudas $deudas
     * @return CEmpleado
     */
    public function addDeuda(\Planillas\CoreBundle\Entity\CDeudas $deudas)
    {
        $this->deudas[] = $deudas;
    
        return $this;
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
     * Get deudas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDeudas()
    {
        return $this->deudas;
    }

    /**
     * Add cuentasBancos
     *
     * @param \Planillas\NomencladorBundle\Entity\NBanco $cuentasBancos
     * @return CEmpleado
     */
    public function addCuentasBanco(\Planillas\NomencladorBundle\Entity\NBanco $cuentasBancos)
    {
        $this->cuentasBancos[] = $cuentasBancos;
    
        return $this;
    }

    /**
     * Remove cuentasBancos
     *
     * @param \Planillas\NomencladorBundle\Entity\NBanco $cuentasBancos
     */
    public function removeCuentasBanco(\Planillas\NomencladorBundle\Entity\NBanco $cuentasBancos)
    {
        $this->cuentasBancos->removeElement($cuentasBancos);
    }

    /**
     * Get cuentasBancos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCuentasBancos()
    {
        return $this->cuentasBancos;
    }

    /**
     * Set estadoCivil
     *
     * @param \Planillas\NomencladorBundle\Entity\NEstadoCivil $estadoCivil
     * @return CEmpleado
     */
    public function setEstadoCivil(\Planillas\NomencladorBundle\Entity\NEstadoCivil $estadoCivil = null)
    {
        $this->estadoCivil = $estadoCivil;
    
        return $this;
    }

    /**
     * Get estadoCivil
     *
     * @return \Planillas\NomencladorBundle\Entity\NEstadoCivil 
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Add gastosPrincipales
     *
     * @param \Planillas\EntidadesBundle\Entity\EGastoPrincipal $gastosPrincipales
     * @return CEmpleado
     */
    public function addGastosPrincipale(\Planillas\EntidadesBundle\Entity\EGastoPrincipal $gastosPrincipales)
    {
        $this->gastosPrincipales[] = $gastosPrincipales;
    
        return $this;
    }

    /**
     * Remove gastosPrincipales
     *
     * @param \Planillas\EntidadesBundle\Entity\EGastoPrincipal $gastosPrincipales
     */
    public function removeGastosPrincipale(\Planillas\EntidadesBundle\Entity\EGastoPrincipal $gastosPrincipales)
    {
        $this->gastosPrincipales->removeElement($gastosPrincipales);
    }

    /**
     * Get gastosPrincipales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGastosPrincipales()
    {
        return $this->gastosPrincipales;
    }

    /**
     * Add historiasTrabajos
     *
     * @param \Planillas\EntidadesBundle\Entity\EHistoriaTrabajo $historiasTrabajos
     * @return CEmpleado
     */
    public function addHistoriasTrabajo(\Planillas\EntidadesBundle\Entity\EHistoriaTrabajo $historiasTrabajos)
    {
        $this->historiasTrabajos[] = $historiasTrabajos;
    
        return $this;
    }

    /**
     * Remove historiasTrabajos
     *
     * @param \Planillas\EntidadesBundle\Entity\EHistoriaTrabajo $historiasTrabajos
     */
    public function removeHistoriasTrabajo(\Planillas\EntidadesBundle\Entity\EHistoriaTrabajo $historiasTrabajos)
    {
        $this->historiasTrabajos->removeElement($historiasTrabajos);
    }

    /**
     * Get historiasTrabajos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHistoriasTrabajos()
    {
        return $this->historiasTrabajos;
    }

    /**
     * Add personasDependen
     *
     * @param \Planillas\EntidadesBundle\Entity\EPersonaDependen $personasDependen
     * @return CEmpleado
     */
    public function addPersonasDependen(\Planillas\EntidadesBundle\Entity\EPersonaDependen $personasDependen)
    {
        $this->personasDependen[] = $personasDependen;
    
        return $this;
    }

    /**
     * Remove personasDependen
     *
     * @param \Planillas\EntidadesBundle\Entity\EPersonaDependen $personasDependen
     */
    public function removePersonasDependen(\Planillas\EntidadesBundle\Entity\EPersonaDependen $personasDependen)
    {
        $this->personasDependen->removeElement($personasDependen);
    }

    /**
     * Get personasDependen
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonasDependen()
    {
        return $this->personasDependen;
    }

    /**
     * Add familiares
     *
     * @param \Planillas\EntidadesBundle\Entity\EFamilia $familiares
     * @return CEmpleado
     */
    public function addFamiliare(\Planillas\EntidadesBundle\Entity\EFamilia $familiares)
    {
        $this->familiares[] = $familiares;
    
        return $this;
    }

    /**
     * Remove familiares
     *
     * @param \Planillas\EntidadesBundle\Entity\EFamilia $familiares
     */
    public function removeFamiliare(\Planillas\EntidadesBundle\Entity\EFamilia $familiares)
    {
        $this->familiares->removeElement($familiares);
    }

    /**
     * Get familiares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFamiliares()
    {
        return $this->familiares;
    }

    /**
     * Set salarioBase
     *
     * @param \Planillas\CoreBundle\Entity\CSalarioBase $salarioBase
     * @return CEmpleado
     */
    public function setSalarioBase(\Planillas\CoreBundle\Entity\CSalarioBase $salarioBase = null)
    {
        $this->salarioBase = $salarioBase;
    
        return $this;
    }

    /**
     * Get salarioBase
     *
     * @return \Planillas\CoreBundle\Entity\CSalarioBase 
     */
    public function getSalarioBase()
    {
        return $this->salarioBase;
    }

    /**
     * Add componentesSalariales
     *
     * @param \Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentesSalariales
     * @return CEmpleado
     */
    public function addComponentesSalariale(\Planillas\EntidadesBundle\Entity\EComponentesSalariales $componentesSalariales)
    {
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
     * Get componentesSalariales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComponentesSalariales()
    {
        return $this->componentesSalariales;
    }
     public function __toString() {
        return $this->nombre . ' ' . $this->primerApellido;
    }

}