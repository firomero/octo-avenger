<?php

namespace Planillas\CoreBundle\Managers;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CEmpleado;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;
use Planillas\CoreBundle\Entity\CPlanillasComponentesPermanentes;
use Planillas\EntidadesBundle\Entity\EComponentesSalariales;
use Planillas\PaymentsBundle\Managers\ComponenteBonificacionesManager;
use Planillas\PaymentsBundle\Managers\SalarioManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Planillas\CoreBundle\Util\PdfObject;

define('cantDiasHabiles', 30);
define('cantHorasPorMes', 240);
define('cantHorasDiarias', 8);

class CPlanillasManagers
{
    private $em;
    private $prequest;
    private $aEmpleadosSalario;
    private $aDatosSalario;
    private $dTotal;
    private $dBonificacionesTotal;
    private $dRebajosTotal;

    /**
     * @var  \DateTime $fechaInicio
     */
    private $fechaInicio;

    /**
     * @var  \DateTime $fechaFin
     */
    private $fechaFin;
    private $idplanilla;

    /**
     * @var $salarioManager \Planillas\PaymentsBundle\Managers\SalarioManager
     */
    private $salarioManager;

    /**
     * @var $bonificacionesManager \Planillas\PaymentsBundle\Managers\ComponenteBonificacionesManager
     */
    private $bonificacionesManager;

    /**
     * @var $logger \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    private $container;

    public function __construct(Container $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->prequest = $container->get('request');
        $this->planilla = null;
        //$this->idplanilla = $id;
        $this->salarioManager = $container->get('payments.salario.manager');
        $this->bonificacionesManager = $container->get('payments.componente_bonificacion.manager');
        $this->logger = $container->get('logger');
        $this->container = $container;

        $this->initialize();
    }

    public function initialize()
    {
        /**
         * guarda los datos economicos de los empleados
         */
        $this->aDatosSalario = array();
        /**
         * guarda  el array de empleados con sus respectivos datos economicos $aDatosSalario array
         */
        $this->aEmpleadosSalario = array();
        $this->aEmpleadosSalario['empleados'] = array();
        $this->aEmpleadosSalario['total'] = 0;
        $this->aEmpleadosSalario['id_planilla'] = 0;
        /**
         * total en terminos de salario de todos los empleados
         */
        $this->dTotal = 0.0;
        $this->dBonificacionesTotal = 0.0;
        $this->dRebajosTotal = 0.0;

        /**
         * Obtener las fechas
         */
        $parameters = $this->prequest->request->get('planillas');
        if (isset($parameters['fechaInicio']) && $parameters['fechaInicio'] != "") {
            $this->fechaInicio = new \DateTime($parameters['fechaInicio']); //date('Y-m-d',strtotime($parameters['fechaInicio']));
            $this->aEmpleadosSalario['periodo']['inicio'] = $this->fechaInicio->format('Y-m-d');
        } else {
            $this->fechaInicio = null;
        }
        if (isset($parameters['fechaFin']) && $parameters['fechaFin'] != "") {
            $this->fechaFin = new \DateTime($parameters['fechaFin']); ///date('Y-m-d',strtotime($parameters['fechaFin']));
            $this->aEmpleadosSalario['periodo']['fin'] = $this->fechaFin->format('Y-m-d');
        } else {
            $this->fechaFin = null;
        }
    }

    /**
     * funcion que salva una planilla en la base de datos
     */
    public function savePlanilla()
    {
        $periodoPago = $this->getPeriodoPagoActivo();

        if ($periodoPago === false) {
            return false;
        }

        $planilla = new CPlanillas();
        $planilla->setFechaInicio($this->fechaInicio);
        $planilla->setFechaFin($this->fechaFin);
        $planilla->setPeriodo($periodoPago);
        $planilla->setCreatedAt(new \DateTime('now'));
        try {
            $this->em->beginTransaction();
            $this->savePlanillasIntoDependencias($planilla);

            $this->em->persist($planilla);
            $this->em->flush();

            $this->em->commit();

            return true;
        } catch (\Exception $e) {
            $this->logger->addCritical(sprintf('Ha ocurrido un error persistiendo la planilla. Detalles: ',
                $e->getMessage()));

            $this->em->rollback();

            //$this->em->remove($planilla);
            //$this->em->flush();

            return false;
        }
    }

    public function savePlanillasIntoDependencias(CPlanillas $planilla) {
        if ($planilla instanceof CPlanillas) {
            $this->planilla = $planilla;
            $oEmpleados = $this->findAllEmployee();
            if ($oEmpleados != null) {
                foreach ($oEmpleados as $oEmpleado) {
                    $planillaEmpleado = new CPlanillasEmpleado();

                    $planillaEmpleado->setSalarioPeriodo($this->getSalarioPeriodoByEmpleado($oEmpleado->getId()));
                    $planillaEmpleado->setSalarioTotal($this->getSalarioBaseByEmpleado($oEmpleado->getId())); //por ahora no funciona
                    //$empleadoplanillas->setPlanilla($planilla);
                    $planillaEmpleado->setEmpleado($oEmpleado);
                    //$this->em->persist($empleadoplanillas);
                    //$this->em->flush();


                    //$this->findBonificacionesByEmpleado($oEmpleado, true, null, $planillaEmpleado); //ok
                    $this->bonificacionesManager
                        ->persistBonificacionesToPlanillaEmpleado($oEmpleado, $this->fechaInicio, $this->fechaFin,
                            $planillaEmpleado);
                    $this->findDeudasByEmpleado($oEmpleado, true, null, $planillaEmpleado); //ok
                    $this->findDiasExtrasByEmpleado($oEmpleado, true, null, $planillaEmpleado); //ok
                    $this->findHorasExtrasByEmpleado($oEmpleado, true, null, $planillaEmpleado); //ok
                    $this->findDiasMenosByEmpleado($oEmpleado, true, null, $planillaEmpleado); //ok
                    $this->findIncapacidadesByEmpleado($oEmpleado, true, null, $planillaEmpleado); //ok

                    $planilla->addPlanillasEmpleado($planillaEmpleado);
                }
            }
        }
    }

    public function createPlanillaComponentePermanente(EComponentesSalariales $oComponente, CEmpleado $empleado) {
        /*         * $oPlanilla = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')->find($planilla);
          if (!$oPlanilla) {
          throw new Exception("No existe la planilla");
          } */
        if (!$empleado) {
            return false;
            //throw new Exception("No existe el empleado");
        }

        $oPermanente = new CPlanillasComponentesPermanentes();
        $oPermanente->setComponentePermanente($oComponente);
        $oPermanente->setEmpleado($empleado);

        return $oPermanente;
    }

    public function getPlanillas()
    {
        //$sql = 'SELECT c  FROM PlanillasEntidadesBundle:CPlanillas c';
        $sql = 'SELECT c  FROM PlanillasCoreBundle:CPlanillas c';
        if ($this->getFechaInicio() != null && $this->getFechaFin() != null) {

            $sql .= ' where c.fechaInicio >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
            $sql .= ' and c.fechaFin <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
        }
        $sql.=' order by c.id desc';

        $query = $this->em->createQuery($sql);
        $oPlanillas = $query->getResult();

        return $oPlanillas;
    }

    /*     * Bloque de funciones find */

    /**
     *  funcion que imprime el html
     *  esta funcion primero debe buscar si el periodo de pago existe para si existe entonces
     *  cargarlo y no buscar todo de nuevo
     */
    public function resultHtmlPlanillas()
    {
        /**
         * vamos a completar los datos del period de pago
         * si las fechas tienen problemas entonces retornamos un array vacio ya que no vamos a buscar
         */
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->aEmpleadosSalario['periodo']['inicio'] = $this->fechaInicio->format('Y-m-d');
            $this->aEmpleadosSalario['periodo']['fin'] = $this->fechaFin->format('Y-m-d');
        } else {
            $this->aEmpleadosSalario['periodo']['inicio'] = "";
            $this->aEmpleadosSalario['periodo']['fin'] = "";

            return $this->aEmpleadosSalario;
        }

        /**
         * buscamos todos los empleados activos
         */
        $oEmpleados = $this->findAllEmployee();
        /**
         * primero vamos a buscar si ya esta creada para asi hacer busquedas nada mas por la llave de la planilla
         */
        $oPlanilla = $this->findPeriodoPagoByFecha();
        /**
         * Estas comparaciones de abajo simplemente son para saber si esta buscando un periodo nuevo o uno ya creado
         * si te fijas esta preguntando por el id de la planilla
         */
        if ($this->idplanilla != null)
            $idPlanilla = $this->idplanilla;
        elseif ($oPlanilla == null)
            $idPlanilla = null;
        else
            $idPlanilla = $oPlanilla->getId();

        $this->aEmpleadosSalario['id_planilla'] = ($idPlanilla == null) ? 0 : $idPlanilla;
        if ($oEmpleados != null) {

            $i = 0;
            /**
             * Vamos a recorrer todos los empleados activos para buscarle sus componentes y demas
             */
            foreach ($oEmpleados as $oEmpleado) {
                /**
                 * por cada uno vamos a buscar  todos sus datos economicos
                 */
                $aTemp['salario_total_empleado'] = 0.0;

                /**
                 * vamos a ver si la planilla  esta creada entonces buscamos los datos de la planilla
                 */
                if ($idPlanilla != null) {
                    $salarioPlanillaEmpleado = $this->em->getRepository('PlanillasCoreBundle:CPlanillasEmpleado')->findOneBy(array('empleado' => $oEmpleado->getId(), 'planilla' => $idPlanilla));

                    if (!$salarioPlanillaEmpleado) {
                        continue; //el tipo no salio en la planilla de pago que se esta buscando
                    } else {
                        /**
                         * buscamos en la tabla el salario
                         * esto se hace asi porque puede ser que ahora el empleado tenga un salario pero para otro
                         * periodo tenga otro entonces para arreglar yo lo que hago es guardar el salario que tuvo en una
                         * determinada planilla generdada para que despues no tenga conflictos
                         */
                        $salarioBaseTemp = $salarioPlanillaEmpleado->getSalarioPeriodo();
                    }
                } else {
                    /**
                     * como no esta buscando un planilla en base de datos
                     * entonces buscamos su salario en la tabla de salario de cada empleado
                     */
                    $salarioBaseTemp = $this->getSalarioPeriodoByEmpleado($oEmpleado->getId());
                }

                /**
                 * Buscamos sus bonificaciones
                 */
                //$aBonificionesTotalTemp = $this->findBonificacionesByEmpleado($oEmpleado, false, $oPlanilla);
                $aBonificionesTotalTemp = $this->bonificacionesManager
                    ->getBonificacionesInDataArray($oEmpleado, $this->fechaInicio, $this->fechaFin, $oPlanilla);
                /**
                 * Buscamos sus deudas
                 */
                $aDeudasTotalTemp = $this->findDeudasByEmpleado($oEmpleado, false, $oPlanilla);
                /**
                 * Buscamos sus Dias extras
                 */
                $aDiasExtraTemp = $this->findDiasExtrasByEmpleado($oEmpleado, false, $oPlanilla);
                /**
                 * Buscamos sus horas extras
                 */
                $aHorasExtrasTemp = $this->findHorasExtrasByEmpleado($oEmpleado, false, $oPlanilla);
                /**
                 * buscamos sus dias menos esto no son mas que las ausencias
                 */
                $aDiasMenosTemp = $this->findDiasMenosByEmpleado($oEmpleado, false, $oPlanilla);
                /**
                 * buscamos sus incapacidades
                 */
                $aIncapacidadesTemp = $this->findIncapacidadesByEmpleado($oEmpleado, false, $oPlanilla);
                /**
                 * buscamos las duedas permanentes
                 */
                /* $aDeudasPermanentesTemp=$this->findDeudasPermanentesByEmpleado($oEmpleado->getId(), false, $idPlanilla);
                  if (count($aDeudasPermanentesTemp)>0) {
                  foreach($aDeudasPermanentesTemp)
                  } */
                /* Asignando salario y demas componentes que afectan  el salario */
                /**
                 * Aqui estamos armando el arreglo con  todos los datos
                 */
                $aTemp['salario_total_empleado'] = $salarioBaseTemp;
                //$aTemp['salario_periodo_pago']=$salarioBaseTemp
                $aTemp['salario_base'] = $salarioBaseTemp;
                $aTemp['bonificaciones'] = $aBonificionesTotalTemp;
                $aTemp['deudas'] = $aDeudasTotalTemp;
                $aTemp['dias_extra'] = $aDiasExtraTemp;
                $aTemp['horas_extras'] = $aHorasExtrasTemp;
                $aTemp['dias_menos'] = $aDiasMenosTemp;
                $aTemp['incapacidades'] = $aIncapacidadesTemp;

                /* Sumando y restando  totales de bonificaciones y deudas */
                $aTemp['salario_total_empleado'] += $aBonificionesTotalTemp['total'];
                $aTemp['salario_total_empleado'] -= $aDeudasTotalTemp['total'];
                $aTemp['salario_total_empleado'] += $aDiasExtraTemp['total'];
                $aTemp['salario_total_empleado'] += $aHorasExtrasTemp['total'];
                $aTemp['salario_total_empleado'] -= $aDiasMenosTemp['total'];
                $aTemp['salario_total_empleado'] -= $aIncapacidadesTemp['total'];

                /**
                 * Asignando al array final los datos del empleado en cuestion
                 */
                $this->aEmpleadosSalario['empleados'] [$i]['datos_personales'] = array(
                    'nombre' => $oEmpleado->getNombre(),
                    'apellidos' => $oEmpleado->getPrimerApellido() . ' ' . $oEmpleado->getSegundoApellido(),
                    'cedula' => $oEmpleado->getCedula(),
                    'id' => $oEmpleado->getId()
                );
                /**
                 * Asignando sus datos enconomicos
                 */
                $this->aEmpleadosSalario['empleados'][$i]['datos_economicos'] = $aTemp;

                /**
                 * Buscando la empresa y sucursal para el puesto asignado
                 */
                $puestoEmpleado = $this->em->getRepository('PlanillasCoreBundle:CPuestoEmpleado')
                    ->getEmpresaByEmpleadoId($oEmpleado->getId());
                if($puestoEmpleado) {
                    $nombreEmpresa = $puestoEmpleado->getEmpresa()->getNombre();
                    $nombreSucursal = $puestoEmpleado->getSucursal()->getNombre();
                    $this->aEmpleadosSalario['empleados'] [$i]['datos_personales']['empresa'] = $nombreEmpresa;
                    $this->aEmpleadosSalario['empleados'] [$i]['datos_personales']['sucursal'] = $nombreSucursal;
                }

                /**
                 * Buscando tipo de pago asignado al empleado
                 */
                $tipoPago = $this->em->getRepository('PlanillasEntidadesBundle:ECuentaBanco')
                    ->findOneByEmpleado($oEmpleado);
                if ($tipoPago) {
                    $tipo = $tipoPago->getTipo();
                    $this->aEmpleadosSalario['empleados'] [$i]['datos_personales']['tipo_pago'] = $tipo;
                }

                $i++;
            }
        }

        return $this->aEmpleadosSalario;
    }

    /*
     * Consultas sql para obtener componentes salariales y empleados
     */

    public function findAllEmployee()
    {
        $oEmpleados = $this->em->getRepository('PlanillasCoreBundle:CEmpleado')->findBy(array('activo' => true));
        if (count($oEmpleados) > 0) {
            return $oEmpleados;
        } else

            return null;
    }

    public function findBonificacionesByEmpleado(CEmpleado $empleado, $update = false, $oPlanilla = null,
                                                 CPlanillasEmpleado $planillaEmpleado = null)
    {
        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            //esto hay que cambiarlo para que busque en planillasComponentes
            //$oBonificaciones = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')
            //    ->findBy(array('componente' => 1, 'planilla' => $oPlanilla, 'empleado' => $empleado->getId()));
            $query = $this->em->createQueryBuilder()
                ->select('c')
                ->from('PlanillasEntidadesBundle:EComponentesSalariales','c')
                ->innerJoin('c.planillaEmpleado', 'pe')
                ->innerJoin('pe.planilla', 'p')
                ->where('p = :planilla AND c.empleado = :empleado AND c.componente=1')
                ->setParameters(array(
                    'planilla' => $oPlanilla,
                    'empleado' => $empleado->getId(),
                ))
                ->getQuery();

            $oBonificaciones = $query->getResult();
        } else {
            $oBonificaciones = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')
                ->findBy(array('componente' => 1, 'empleado' => $empleado->getId()));
        }

        if (count($oBonificaciones) > 0) {
            foreach ($oBonificaciones as $oBonificacion) {

                //chequeamos la variable indicador que dice si esta iterando sobre bonificaciones
                //de un planilla creada

                if ($bIndicador === true) { //estamos obteniendo las que ya tienen planillas
                    if (!$oBonificacion->getPermanente()) {
                        $aSalida['bonificaciones'][] = array(
                            'id' => $oBonificacion->getId(),
                            'descripcion' => $oBonificacion->getDescripcion(),
                            'fecha_inicio' => $oBonificacion->getFecha()->format('d/m/Y'),
                            'monto_total' => number_format($oBonificacion->getMontoTotal(), 2, '.', ''));

                        $aSalida['total'] += $oBonificacion->getMontoTotal();
                    }
                    continue;
                }

                if ($oBonificacion->getFecha() < $this->fechaInicio && $oBonificacion->getPermanente() == 0) {
                    continue; //ya se vencio la bonificacion
                }
                if ($update === true) {
                    /**
                     * vamos a preguntar si la bonificacion es no esta eliminada
                     */
                    if (/* $oBonificacion->getPermanente() && */ $oBonificacion->getDeletedAt() == null) {

                        //esto cambio el dia 18-02-2014 porque tenia un pequeno bug
                        //a la hora de asociar la planilla a la componente
                        $componentePermanente = $this->createPlanillaComponentePermanente($oBonificacion, $empleado);
                        $planillaEmpleado->addComponentePermanente($componentePermanente);
                    }


                    //if ($oBonificacion->getPermanente() && $oBonificacion->getDeletedAt() == null) {
                    //    $this->savePlanillaComponentePermanente($this->planilla, $oBonificacion, $idEMpleado);
                    //} else { //como no es permanente entoces procedemos a poner el id de la planilla
                    //    $oBonificacion->setPlanilla($this->planilla);
                    //    $this->em->persist($oBonificacion);
                    //    $this->em->flush();
                    //}
                } else {//obteniendo todas la bonificaciones sin 
                    if ($oBonificacion->getDeletedAt() == null) {
                        $aSalida['bonificaciones'][] = array(
                            'id' => $oBonificacion->getId(),
                            'descripcion' => $oBonificacion->getDescripcion(),
                            'fecha_inicio' => $oBonificacion->getFecha()->format('d/m/Y'),
                            'monto_total' => number_format($oBonificacion->getMontoTotal(), 2, '.', ''));

                        $aSalida['total'] += $oBonificacion->getMontoTotal();
                    }
                }
            }
        }
        //esto debe cambiar

        if ($bIndicador && $update == false) { //esta buscando planillas ya creadas
            //ebteniendo las bonificaciones ya pagadas
            $query = $this->em->createQueryBuilder()
                ->select('c,e')
                ->from('PlanillasCoreBundle:CPlanillasComponentesPermanentes', 'c')
                ->innerJoin('c.componentePermanente','e')
                ->innerJoin('c.planillaEmpleado', 'pe')
                ->innerJoin('pe.planilla','p')
                ->where('c.empleado = :empleado AND p = :planilla')
                ->setParameters(array(
                    'empleado' => $empleado->getId(),
                    'planilla' => $oPlanilla,
                ))
                ->getQuery();
            //$sql = 'SELECT c,e  FROM PlanillasCoreBundle:CPlanillasComponentesPermanentes c '.
            //    'INNER Join c.componentePermanente e '.
            //    'WHERE c.empleado=' . $empleado->getId();
            //$sql .= ' and c.planilla=' . $oPlanilla; //componente ==0 para que solo verifique las deudas
            //$query = $this->em->createQuery($sql);
            //$oBonificaciones = $query->getResult();//getResult();
            $bonificacionesPagadas = $query->getArrayResult();
            //$deudasPermanentes = $this->em->//$this->findDeudasPermanentesByEmpleado($idEmpleado, $update, $oPlanilla);
            if (count($bonificacionesPagadas) > 0) {
                foreach ($bonificacionesPagadas as $permanente) {
                    /* $oCPlanillasComponentesPermanentes = $this->em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')->findOneBy(array('planilla' => $oPlanilla, 'componentePermanente' => $permanente->getId(), 'empleado' => $idEMpleado));
                      if (!$oCPlanillasComponentesPermanentes) {
                      continue;
                      } */

                    $aSalida['bonificaciones'][] = array(
                        'id' => $permanente['componentePermanente']['id'],
                        'fecha_inicio' => ($permanente['componentePermanente']['permanente'] == true) ?
                                "Permanente" : $permanente['componentePermanente']['fechaVencimiento']->format('Y-m-d'),
                        'descripcion' => $permanente['componentePermanente']['descripcion'],
                        'monto_total' => number_format($permanente['componentePermanente']['cantidad'], 2, '.', ''));

                    $aSalida['total'] += $permanente['componentePermanente']['cantidad'];
                }
            }
        }

        //$bonificacionesPermanentes = $this->findBonificacionesPermanentesByEmpleado($idEMpleado, $update, $oPlanilla, &$aSalida);
        return $aSalida;
    }

    /**
     * funcion que busca las deudas de un empleado(Sanciones Uniformes y Prestamos)
     * @param CEmpleado $empleado
     * @param bool|\Planillas\CoreBundle\Managers\type $update
     * @param type $oPlanilla
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     * @return type
     */
    public function findDeudasByEmpleado(CEmpleado $empleado, $update = false, $oPlanilla = null,
                                         CPlanillasEmpleado $planillaEmpleado = null)
    {
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            //$aDeudasTemp = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')
            //    ->findBy(array(
            //        'componente' => 0,
            //        'planilla' => $oPlanilla,
            //        'empleado' => $empleado->getId(),
            //    ));

            $query = $this->em->createQueryBuilder()
                ->select('c')
                ->from('PlanillasEntidadesBundle:EComponentesSalariales','c')
                ->innerJoin('c.planillaEmpleado', 'pe')
                ->innerJoin('pe.planilla', 'p')
                ->where('p = :planilla AND c.empleado = :empleado AND c.componente=0')
                ->setParameters(array(
                    'planilla' => $oPlanilla,
                    'empleado' => $empleado->getId(),
                ))
                ->getQuery();

            $aDeudasTemp = $query->getResult();
        } else {
            $sql = 'SELECT c  FROM PlanillasEntidadesBundle:EComponentesSalariales c INNER Join c.empleado e WHERE e.activo=1 and e.id=' . $empleado->getId();
            $sql .= ' and c.componente=0'; //componente ==0 para que solo verifique las deudas

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and (c.fecha >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fecha <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }
            $sql .= ' or c.permanente=1)';
            $query = $this->em->createQuery($sql);
            $aDeudasTemp = $query->getResult();
        }

        $aSalida = array();
        $aSalida['total'] = 0;
        if (count($aDeudasTemp) > 0) {
            foreach ($aDeudasTemp as $sDeuda) {

                if ($bIndicador) {
                    $aSalida['deudas'][] = array(
                        'id' => $sDeuda->getId(),
                        'fecha_inicio' => $sDeuda->getFecha()->format('d/m/Y'),
                        'componente' => $sDeuda->getComponente(),
                        'monto_total' => number_format($sDeuda->getMontoTotal(), 2, '.', ''),
                        'permanente' => $sDeuda->getPermanente(),
                    );

                    $aSalida['total'] += $sDeuda->getMontoTotal();
                    continue;
                }
                if ($sDeuda->getPlanillaEmpleado() != null) {
                    continue;
                }
                if ($update === true) {
                    if ($sDeuda->getPermanente() && $sDeuda->getDeletedAt() == null) {
                        $componentePermanente = $this->createPlanillaComponentePermanente($sDeuda, $empleado);
                        $planillaEmpleado->addComponentePermanente($componentePermanente);
                    } else {
                        $planillaEmpleado->addComponentesSalarial($sDeuda);
                        //$sDeuda->setPlanilla($this->planilla);
                        //$this->em->persist($sDeuda);
                        //$this->em->flush();
                    }
                } else {

                    if ($sDeuda->getDeletedAt() == null) {
                        $aSalida['deudas'][] = array(
                            'id' => $sDeuda->getId(),
                            'fecha_inicio' => $sDeuda->getFecha()->format('d/m/Y'),
                            'componente' => $sDeuda->getComponente(),
                            'monto_total' => number_format($sDeuda->getMontoTotal(), 2, '.', ''),
                            'permanente' => $sDeuda->getPermanente(),
                        );

                        $aSalida['total'] += $sDeuda->getMontoTotal();
                    }
                }
            }
        }

        if ($bIndicador && $update == false) {

            $sql = 'SELECT c  FROM PlanillasEntidadesBundle:EComponentesSalariales c INNER Join c.empleado e WHERE e.activo=1 and e.id=' . $empleado->getId();
            $sql .= ' and c.componente=0 and c.permanente=1'; //componente ==0 para que solo verifique las deudas
            $query = $this->em->createQuery($sql);
            $deudasPermanentes = $query->getResult();

            //$deudasPermanentes = $this->em->//$this->findDeudasPermanentesByEmpleado($idEmpleado, $update, $oPlanilla);
            if (count($deudasPermanentes) > 0) {
                foreach ($deudasPermanentes as $permanente) {
                    $oCPlanillasComponentesPermanentes = $this->em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')->findOneBy(array('planilla' => $oPlanilla, 'componentePermanente' => $permanente->getId(), 'empleado' => $empleado));

                    if (!$oCPlanillasComponentesPermanentes) {
                        //print_r("hello world");exit;
                        continue;
                    }
                    $aSalida['deudas'][] = array(
                        'id' => $permanente->getId(),
                        'fecha_inicio' => null, //$sDeuda->getFechaInicio()->format('Y-m-d') . '/' . $sDeuda->getFechaVencimiento()->format('Y-m-d'),
                        'componente' => $permanente->getComponente(),
                        'monto_total' => number_format($permanente->getMontoTotal(), 2, '.', ''),
                        'permanente' => $permanente->getPermanente(),
                    );

                    $aSalida['total'] += $permanente->getMontoTotal();
                }
            }
        }

        return $aSalida;
    }

    /**
     * funcion que busca las deudas permanentes
     * @param type $idEmpleado empleado en cuestion
     * @param bool|\Planillas\CoreBundle\Managers\type $update si esta actualizando la componente
     * @param type $oPlanilla la planilla
     * @return type
     *
     * @deprecated
     */
    public function findDeudasPermanentesByEmpleado($idEmpleado, $update = false, $oPlanilla = null)
    {
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            $aDeudasTemp = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('componente' => 0, 'planilla' => $oPlanilla, 'empleado' => $idEmpleado));
        } else {
            $sql = 'SELECT c  FROM PlanillasEntidadesBundle:EComponentesSalariales c INNER Join c.empleado e WHERE e.activo=1 and e.id=' . $idEmpleado;
            $sql .= ' and c.componente=0 and c.permanente=1'; //componente ==0 para que solo verifique las deudas
            $query = $this->em->createQuery($sql);
            $aDeudasTemp = $query->getResult();
        }

        return $aDeudasTemp;
    }

    /**
     * @param $idEmpleado
     * @param bool $update
     * @param null $oPlanilla
     * @param array $aSalida
     * @return array
     *
     * @deprecated
     */
    public function findBonificacionesPermanentesByEmpleado($idEmpleado, $update = false, $oPlanilla = null, $aSalida = array()) {

        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            $aComponentePermanentes = $this->em->getRepository('PlanillasCoreBundle:CPlanillasComponentesPermanentes')->findBy(array('empleado' => $idEmpleado, 'planilla' => $oPlanilla));
            if (count($aComponentePermanentes) > 0) {
                foreach ($aComponentePermanentes as $oComponente) {
                    $componenteSalarialTemp = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->find($oComponente->getComponente());
                    if ($componenteSalarialTemp) {

                    }
                }
            }
        } else {
            $sql = 'SELECT c  FROM PlanillasEntidadesBundle:EComponentesSalariales c INNER Join c.empleado e WHERE e.activo=1 and e.id=' . $idEmpleado;
            $sql .= ' and c.componente=1 and c.permanente=1'; //componente ==1 para que solo verifique las bonificaciones
            $query = $this->em->createQuery($sql);
            $aBonificacionesTemp = $query->getResult();
            if (count($aBonificacionesTemp) > 0) {
                foreach ($aBonificacionesTemp as $permanente) {

                    if ($update == true) {

                    }
                    $aSalida['bonificaciones'][] = array(
                        'id' => $permanente->getId(),
                        'fecha_inicio' => null, //$sDeuda->getFechaInicio()->format('Y-m-d') . '/' . $sDeuda->getFechaVencimiento()->format('Y-m-d'),
                        'componente' => $permanente->getComponente(),
                        'descripcion' => $permanente->getDescripcion(),
                        'monto_total' => number_format($permanente->getCantidad(), 2, '.', ''));

                    $aSalida['total'] += $permanente->getCantidad();
                }
            }
        }

        return $aBonificacionesTemp;
    }

    /**
     * funcion que calcula el importe de los dias extras trabajados dado un determinado empleado
     * @param CEmpleado $empleado
     * @param bool $update
     * @param null $oPlanilla
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     * @return type array de tuplas
     *
     */
    public function findDiasExtrasByEmpleado(CEmpleado $empleado, $update = false, $oPlanilla = null,
                                             CPlanillasEmpleado $planillaEmpleado = null)
    {
        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            //$oDiasExtra = $this->em->getRepository('PlanillasCoreBundle:CDiasExtra')
            //    ->findBy(array(
            //        'empleado' => $empleado->getId(),
            //        'planilla' => $oPlanilla
            //    ));
            $query = $this->em->createQueryBuilder()
                ->select('d')
                ->from('PlanillasCoreBundle:CDiasExtra','d')
                ->innerJoin('d.planillaEmpleado', 'pe')
                ->innerJoin('pe.planilla', 'p')
                ->where('p = :planilla AND d.empleado = :empleado')
                ->setParameters(array(
                    'planilla' => $oPlanilla,
                    'empleado' => $empleado->getId(),
                ))
                ->getQuery();

            $oDiasExtra = $query->getResult();
        } else {
            //$oDiasExtra = $this->em->getRepository('PlanillasCoreBundle:CDiasExtra')->findBy(array('empleado' => $idEMpleado));
            $sql = 'SELECT c  FROM PlanillasCoreBundle:CDiasExtra c INNER Join c.empleado e WHERE  e.id=' . $empleado->getId();

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fecha >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fecha <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }

            $query = $this->em->createQuery($sql);
            $oDiasExtra = $query->getResult();
        }

        if (count($oDiasExtra) > 0) {
            foreach ($oDiasExtra as $oDiaExtra) {
                if ($update === true && $bIndicador === false) {
                    //$oDiaExtra->setPlanilla($this->planilla);
                    //$this->em->persist($oDiaExtra);
                    //$this->em->flush();
                    $planillaEmpleado->addDiasExtra($oDiaExtra);
                } else {
                    $dImporte = $this->getSalarioDiarioByEmpleado($empleado);
                    $aSalida['dias_extras'][] = array(
                        'id' => $oDiaExtra->getId(),
                        'fecha' => $oDiaExtra->getFecha()->format('Y-m-d'),
                        'monto_total' => number_format($dImporte, 2, '.', ''));
                    $aSalida['total'] += $dImporte;
                }
            }
        }

        return $aSalida;
    }

    /**
     * funcion  que obtiene las ausencias de un empleado a su puesto de trabajo
     * @param \Planillas\CoreBundle\Entity\CEmpleado|\Planillas\CoreBundle\Managers\type $empleado
     * @param bool $update
     * @param null $oPlanilla
     * @param \Planillas\CoreBundle\Entity\CPlanillasEmpleado $planillaEmpleado
     * @return type
     */
    public function findDiasMenosByEmpleado(CEmpleado $empleado, $update = false, $oPlanilla = null,
                                            CPlanillasEmpleado $planillaEmpleado = null)
    {
        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            //$oDiasMenos = $this->em->getRepository('PlanillasCoreBundle:CAusencias')
            //    ->findBy(array(
            //        'empleado' => $empleado->getId(),
            //        'planilla' => $oPlanilla
            //    ));
            $query = $this->em->createQueryBuilder()
                ->select('a')
                ->from('PlanillasCoreBundle:CAusencias','a')
                ->innerJoin('a.planillaEmpleado', 'pe')
                ->innerJoin('pe.planilla', 'p')
                ->where('p = :planilla AND a.empleado = :empleado')
                ->setParameters(array(
                    'planilla' => $oPlanilla,
                    'empleado' => $empleado->getId(),
                ))
                ->getQuery();

            $oDiasMenos = $query->getResult();
        } else {
            $sql = 'SELECT c  FROM PlanillasCoreBundle:CAusencias c INNER Join c.empleado e WHERE  e.id=' . $empleado->getId();

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fechaInicio >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fechaFin <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }
            $query = $this->em->createQuery($sql);
            $oDiasMenos = $query->getResult();
        }

        if (count($oDiasMenos) > 0) {
            $dImporte = $this->getSalarioDiarioByEmpleado($empleado);
            foreach ($oDiasMenos as $oDiasMeno) {
                if ($update === true && $bIndicador === false) {
                    $planillaEmpleado->addAusencia($oDiasMeno);
                    //$oDiasMeno->setPlanilla($this->planilla);
                    //$this->em->persist($oDiasMeno);
                    //$this->em->flush();
                } else {
                    if ($oDiasMeno->getTipoAusencia() == 2) {
                        continue;
                    }
                    $fechaInicio = $oDiasMeno->getFechaInicio();
                    $fechaFin = $oDiasMeno->getFechaFin();
                    $diff = date_diff($fechaFin, $fechaInicio);

                    if ($diff->days > 0) { //solo en caso de que la cantidad de dias de diferencia sea
                        //mayor que cero entonces multiplicamos por la cantidad de dias
                        $dImporte *= $diff->days;
                    }

                    $aSalida['dias_menos'][] = array(
                        'id' => $oDiasMeno->getId(),
                        'fecha' => $oDiasMeno->getFechaInicio()->format('Y-m-d') . '/' . $oDiasMeno->getFechaFin()->format('Y-m-d'),
                        'monto_total' => number_format($dImporte, 2, '.', ''));

                    $aSalida['total'] += number_format($dImporte, 2, '.', '');
                }
            }
        }

        return $aSalida;
    }

    /**
     * obtiene las horas extras para un empleado
     *
     * @param $empleado
     * @param bool $update
     * @param null $oPlanilla
     * @return array
     *
     * @deprecated
     */
    public function findHorasExtrasByEmpleado(CEmpleado $empleado, $update = false, $oPlanilla = null,
                                              CPlanillasEmpleado $planillaEmpleado = null)
    {
        //$aSalida = array();
        //$aSalida['total'] = 0;
        if ($oPlanilla != null) {
            //$oHorasExtras = $this->em->getRepository('PlanillasCoreBundle:CHorasExtras')->findBy(array('empleado' => $idEmpleado, 'planilla' => $oPlanilla));
            $oHorasExtras = $this->salarioManager->findHorasExtrasByEmpleado($empleado, null, null, $oPlanilla);
        } else {
            /*$sql = 'SELECT c  FROM PlanillasCoreBundle:CHorasExtras c INNER Join c.empleado e WHERE  e.id=' . $idEmpleado;

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fechaHorasExtras >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fechaHorasExtras <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }
            $query = $this->em->createQuery($sql);
            $oHorasExtras = $query->getResult();*/
            $oHorasExtras = $this->salarioManager
                ->findHorasExtrasByEmpleado($empleado, $this->fechaInicio, $this->fechaFin, null, true, $planillaEmpleado);
        }
        /*if (count($oHorasExtras) > 0) {
            foreach ($oHorasExtras as $oHoraExtra) {
                if ($update === true && $oPlanilla == null) {
                    $oHoraExtra->setPlanilla($this->planilla);
                    $this->em->persist($oHoraExtra);
                    $this->em->flush();
                } else {
                    $dImporte = $this->getSalarioPorHorasByEmpleado($idEmpleado);
                    $aSalida['dias_extras'][] = array(
                        'id' => $oHoraExtra->getId(),
                        'fecha' => $oHoraExtra->getFechaHorasExtras()->format('Y-m-d'),
                        // Ojo tipo ausencia es en realidad cantidad de horas me di cuenta hace poco
                        'monto_total' => number_format($dImporte * $oHoraExtra->getcantidadHoras(), 2, '.', ''));

                    $aSalida['total'] += $dImporte * $oHoraExtra->getCantidadHoras();
                }
            }
        }*/
        return $oHorasExtras;
    }

    /* public function findIncapacidadesByEmpleado($idEmpleado, $update = false, $oPlanilla = null) {
      $aSalida = array();
      $aSalida['total'] = 0;
      $bIndicador = false;
      if ($oPlanilla != null) {
      $bIndicador = true;
      //print_r($oPlanilla);exit;
      $oIncapacidades = $this->em->getRepository('PlanillasCoreBundle:CIncapacidades')->findBy(array('empleado' => $idEmpleado, 'planilla' => $oPlanilla));
      } else {
      $sql = 'SELECT c  FROM PlanillasCoreBundle:CIncapacidades c INNER Join c.empleado e WHERE  e.id=' . $idEmpleado;

      $query = $this->em->createQuery($sql);
      $oIncapacidades = $query->getResult();
      }

      $iCount = 0;
      $iIndicador = 0;
      $resto=0;
      if (count($oIncapacidades) > 0) {
      $dImporteT = $this->getSalarioDiarioByEmpleado($idEmpleado); //arriba para no estar haciendo una consulta
      //en cada ciclo
      foreach ($oIncapacidades as $oIncapacidad) {
      $dImporte = 0;
      if ($oIncapacidad->getPlanilla() != null) {
      continue;
      }
      //$dImporte = $this->getSalarioDiarioByEmpleado($idEmpleado);
      if ($update === true && $bIndicador === false) {
      $oIncapacidad->setPlanilla($this->planilla);
      $this->em->persist($oIncapacidad);
      $this->em->flush();
      } else {
      if ($oIncapacidad->getTipoIncapacidad() == 1) { //Inicapacidades INS
      $fechaInicio = $oIncapacidad->getFechaInicio();
      $fechaFin = $oIncapacidad->getFechaFin();
      $diff = date_diff($fechaFin, $fechaInicio);

      if ($diff->days > 0) {
      $dImporte +=$dImporteT*$diff->days;
      }
      $aSalida['incapacidades'][] = array(
      'id' => $oIncapacidad->getId(),
      'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
      'fecha' => $oIncapacidad->getFechaInicio()->format('Y-m-d') . '/' . $oIncapacidad->getFechaFin()->format('Y-m-d'),
      'monto_total' => number_format($dImporte, 2, '.', ''));

      $aSalida['total'] += number_format($dImporte, 2, '.', '');
      } else { //incapacidades CCSS
      $month = $this->getFechaInicio()->format('m');
      $monthTemp = $oIncapacidad->getFechaInicio()->format('m');
      if ($month == $monthTemp) { //estamos en el mismo mes
      //$iCount++;
      //le rebajamos el dia
      $fechaInicio = $oIncapacidad->getFechaInicio();
      $fechaFin = $oIncapacidad->getFechaFin();
      $diff = date_diff($fechaFin, $fechaInicio);
      if ($diff->days<0) {
      continue;
      }
      $iCount += ($diff->days==0)? 1 : $diff->days;
      //print_r($iCount);exit;

      if ($iCount > 3) {

      //print_r($iIndicador);exit;

      if ($iIndicador < 3) {//para saber cuantos medios dias quedan por pagar

      $dImporte += ($dImporteT / 2) * (3-$iIndicador); //siempre hay que restarle 3 que son los dias que puede ausentarse

      $iIndicador = 3;
      }
      //echo $iCount;exit;
      $bDisminuyo=false;
      if ($diff->days==0) {
      $bDisminuyo=true;
      $iCount--;
      }

      $temp=$iCount - 3;
      $resto=$temp-$resto;
      //if ($bDisminuyo) {$resto=1;$bDisminuyo=false;};
      if ($resto==0) {
      echo $iCount.' '.$temp;exit;
      echo "holaa";exit;
      }
      $dImporte += $dImporteT*($resto); //siempre hay que restarle 3 que son los dias que puede ausentarse

      $aSalida['incapacidades'][] = array(
      'id' => $oIncapacidad->getId(),
      'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
      'fecha' => $oIncapacidad->getFechaInicio()->format('Y-m-d') . '/' . $oIncapacidad->getFechaFin()->format('Y-m-d'),
      'monto_total' => number_format($dImporte, 2, '.', ''));

      $aSalida['total'] += number_format($dImporte, 2, '.', '');
      } else {
      $dTresMediosDias = ($dImporteT / 2) * ($iCount-$iIndicador);
      $iIndicador=$iCount;
      $aSalida['incapacidades'][] = array(
      'id' => $oIncapacidad->getId(),
      'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
      'fecha' => $oIncapacidad->getFechaInicio()->format('Y-m-d') . '/' . $oIncapacidad->getFechaFin()->format('Y-m-d'),
      'monto_total' => number_format($dTresMediosDias, 2, '.', ''));

      $aSalida['total'] += number_format($dTresMediosDias, 2, '.', '');
      }
      }
      }
      }
      //$dImporte=0;
      }
      //exit;
      print_r($aSalida);exit;

      }

      return $aSalida;
      } */

    /**
     * @param CEmpleado $empleado
     * @param bool $update
     * @param null $oPlanilla
     * @param CPlanillasEmpleado $planillaEmpleado
     * @return array
     */
    public function findIncapacidadesByEmpleado(CEmpleado $empleado, $update = false, $oPlanilla = null,
                                                CPlanillasEmpleado $planillaEmpleado = null)
    {
        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            //$oIncapacidades = $this->em->getRepository('PlanillasCoreBundle:CIncapacidades')
            //    ->findBy(array(
            //        'empleado' => $empleado->getId(),
            //        'planilla' => $oPlanilla
            //    ));
            $query = $this->em->createQueryBuilder()
                ->select('i')
                ->from('PlanillasCoreBundle:CIncapacidades','i')
                ->innerJoin('i.planillaEmpleado', 'pe')
                ->innerJoin('pe.planilla', 'p')
                ->where('p = :planilla AND i.empleado = :empleado')
                ->setParameters(array(
                    'planilla' => $oPlanilla,
                    'empleado' => $empleado->getId(),
                ))
                ->getQuery();

            $oIncapacidades = $query->getResult();
        } else {
            $query = $this->em->createQueryBuilder()
                ->select('i')
                ->from('PlanillasCoreBundle:CIncapacidades', 'i')
                ->innerJoin('i.empleado','e')
                ->where('e.id = :idempleado AND i.fecha >= :fechainicio AND i.fecha <= :fechafin')
                ->setParameters(array(
                    'idempleado'    => $empleado->getId(),
                    'fechainicio'   => $this->fechaInicio,
                    'fechafin'      => $this->fechaFin,
                ))
                ->getQuery();
            //$sql = 'SELECT c  FROM PlanillasCoreBundle:CIncapacidades c INNER Join c.empleado e WHERE  e.id=' . $empleado->getId();
            //if ($this->fechaInicio !== null && $this->fechaFin !== null) {
            //    $sql .= ' and c.fechaInicio >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
            //    $sql .= ' and c.fechaFin <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            //}
            //$query = $this->em->createQuery($sql);
            $oIncapacidades = $query->getResult();
        }
        $iIndicador = 0;
        /**
         * variable que se encarga de chequear la cantidad de medios dias que se han pagado
         */
        //$iPagados = 0;
        $iCount = $this->getTotalIncapacidadesAnteriores($empleado->getId());
        $iPagados = ($iCount >= 3) ? 3 : $iCount;
        if (count($oIncapacidades) > 0) {
            $salarioDiario = $this->getSalarioDiarioByEmpleado($empleado->getId()); //arriba para no estar haciendo una consulta
            //en cada ciclo
            foreach ($oIncapacidades as $oIncapacidad) {
                $dImporteT = 0;
                /** @var  \Planillas\CoreBundle\Entity\CIncapacidades $oIncapacidad */

                //if ($oIncapacidad->getPlanillaEmpleado() != null) {
                //    continue;
                //}
                if ($update === true && $bIndicador === false) {
                    $planillaEmpleado->addIncapacidad($oIncapacidad);
                    //$oIncapacidad->setPlanilla($this->planilla);
                    //$this->em->persist($oIncapacidad);
                    //$this->em->flush();
                } else {
                    if ($oIncapacidad->getTipoIncapacidad() === 'incapacidad_ins') { //Inicapacidades INS
                        //$fechaInicio = $oIncapacidad->getFechaInicio();
                        //$fechaFin = $oIncapacidad->getFechaFin();
                        //$diff = date_diff($fechaFin, $fechaInicio);
                        //if ($diff->days > 0) {
                        //    $dImporteT += $salarioDiario * $diff->days;
                        //}
                        $dImporteT = $salarioDiario;
                        $aSalida['incapacidades'][] = array(
                            'id' => $oIncapacidad->getId(),
                            'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
                            'descripcion' => $oIncapacidad->getMotivo(),
                            'fecha' => $oIncapacidad->getFecha()->format('Y-m-d'),
                            'monto_total' => number_format($dImporteT, 2, '.', ''));

                        $aSalida['total'] += number_format($dImporteT, 2, '.', '');
                    } else { //incapacidades CCSS
                        //$month = $this->getFechaInicio()->format('m');
                        //$monthTemp = $oIncapacidad->getFechaInicio()->format('m');
                        //if ($month == $monthTemp) { //estamos en el mismo mes

                        //le rebajamos el dia
                        //$fechaInicio = $oIncapacidad->getFechaInicio();
                        //$fechaFin = $oIncapacidad->getFechaFin();
                        //$diff = date_diff($fechaFin, $fechaInicio);
                        //if ($diff->days < 0) {
                        //    continue;
                        //}
                        //$iCount += ($diff->days == 0) ? 1 : $diff->days;
                        //$iCountInc = ($diff->days == 0) ? 1 : $diff->days;

                        /**
                         * Siempre que la cantidad de dias total del periodo analizado sea menor que 2
                         * debemos descontar medio dia
                         */

                        if($iPagados == 3){
                            $dImporteT = $salarioDiario;
                        } else {
                            $dImporteT = $salarioDiario / 2;
                            $iPagados++;
                        }

                        $aSalida['incapacidades'][] = array(
                            'id' => $oIncapacidad->getId(),
                            'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
                            'descripcion' => $oIncapacidad->getMotivo(),
                            'fecha' => $oIncapacidad->getFecha()->format('d/m/Y'),
                            'monto_total' => number_format($dImporteT, 2, '.', ''));

                        $aSalida['total'] += number_format($dImporteT, 2, '.', '');

                        /*if ($iCount <= 3) {//caso en que vamos a descontar medio dia
                            if ($iCountInc == 1) {
                                $iPagados +=1;
                                $dImporteT = ($salarioDiario / 2) * 1;
                            } else if ($iCountInc == 2) {
                                $iPagados += 2;
                                $dImporteT = ($salarioDiario / 2) * 2;
                            } else {
                                $iPagados += 3;
                                $dImporteT = ($salarioDiario / 2) * 3;
                            }



                        } else {
                            if ($iPagados < 3) {//caso en que no se han pagado todos los medios dias
                                if ($iPagados == 0) {
                                    $resto = 3; //$iCountInc - 3;
                                    $iPagados+=3;
                                } else if ($iPagados == 1) {
                                    $resto = 2; //$iCountInc - 2;
                                    $iPagados+=2;
                                } else {
                                    $resto = 1; //$iCountInc - 1;
                                    $iPagados+=1;
                                }
                                $dImporteT += ($salarioDiario / 2) * $resto;
                                $dImporteT +=$salarioDiario * ($iCountInc - $resto);
                            } else {
                                $dImporteT = $salarioDiario * $iCountInc;
                            }

                            $aSalida['incapacidades'][] = array(
                                'id' => $oIncapacidad->getId(),
                                'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
                                'descripcion' => $oIncapacidad->getMotivo(),
                                'fecha' => $oIncapacidad->getFechaInicio()->format('Y-m-d') . '/' . $oIncapacidad->getFechaFin()->format('Y-m-d'),
                                'monto_total' => number_format($dImporteT, 2, '.', ''));

                            $aSalida['total'] += number_format($dImporteT, 2, '.', '');
                        }*/
                       // }
                    }
                }
            }
        }

        return $aSalida;
    }

    /**
     * funcion que busca una planilla dada las fechas nota: falta la comparacion con la fecha final
     * @return null
     */
    public function findPeriodoPagoByFecha()
    {
        $entity = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')->findOneBy(array('fechaInicio' => $this->fechaInicio, 'fechaFin' => $this->fechaFin));
        if (!$entity) {
            return null;
        } else

            return $entity;
    }

    /**
     * funcion que obtiene el salario bruto de un empleado
     * @param  type $idEmpleado
     * @return int
     *
     * @deprecated
     */
    public function getSalarioBaseByEmpleado($idEmpleado)
    {
        /* Falta aplicar el rebajo de seguro */
        //$salarioBase = 0;
        /*$oSalarioBase = $this->em->getRepository('PlanillasCoreBundle:CSalarioBase')->findOneBy(array('empleado' => $idEmpleado));
        if ($oSalarioBase) {
            return $oSalarioBase->getSalarioBase();
        }

        return 0;*/
        return $this->salarioManager->getSalarioEmpleado($idEmpleado);
    }

    /**
     * funcion que calcula el salario por dia de un determinado empleado
     * @param  type      $idEmpleado
     * @return type
     * @throws Exception
     *
     * @deprecated
     */
    public function getSalarioPeriodoByEmpleado($idEmpleado)
    {
        //ojo que el horario no esta entrando en juego, ya veremos mas adelante
        $dSalariobase = $this->getSalarioBaseByEmpleado($idEmpleado);
        $oPeriodo = $this->getPeriodoPagoActivo();

        if ($oPeriodo === false) {
            throw new \Exception("No existe período de pago activo");
        }

        return round(($dSalariobase / 30) * $oPeriodo->getCantdias(), 2);
    }

    /**
     * @param $idEmpleado
     * @return float
     * @throws Exception
     *
     * @deprecated Use SalarioManager->getSalarioPorDia insteed
     */
    public function getSalarioDiarioByEmpleado($idEmpleado) {
        //$dSalariobase = $this->getSalarioBaseByEmpleado($idEmpleado);
        //$oPeriodo = $this->getPeriodoPagoActivo();
        //if ($oPeriodo === false) {
        //    throw new Exception("No existe período de pago activo");
        //}
        //return round(($dSalariobase / 30), 2);
        return $this->salarioManager->getSalarioPorDia($this->salarioManager->getSalarioEmpleado($idEmpleado));
    }

    /**
     * funcion que calcula el salario de una empleado por horas
     * @param  type $idEmpleado
     * @return type
     *
     * @deprecated Use SalarioManager->getSalarioPorHora insteed
     */
    public function getSalarioPorHorasByEmpleado($idEmpleado)
    {
        //$dSalarioDiario = $this->getSalarioDiarioByEmpleado($idEmpleado);
        //return $dSalarioDiario / cantHorasDiarias;
        return $this->salarioManager->getSalarioPorHora($this->salarioManager->getSalarioEmpleado($idEmpleado));
    }

    /**
     * funcion que valida si un periodo de pago es válido teniendo en cuenta solo los intervalos
     * de días entre ellos
     * @return array|bool
     *
     * @deprecated Use PlanillasManager->validarPeriodoPago(\DateTime, \DateTime) insteed
     */
    public function validarPeriodoPago()
    {
        if ($this->fechaInicio == null || $this->fechaFin == null) {
            return false;
        }
        $periodo_activo = $this->em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')->findOneBy(array('activo' => true));
        if (!$periodo_activo) {
            return array(false, "No hay definido ningún período de pago");
        }
        $iCantDias = $periodo_activo->getCantDias();
        $diff = date_diff($this->fechaFin, $this->fechaInicio);
        if ($diff->days < 0) {
            return false;
        }

        if ($iCantDias != ($diff->days + 1)) {
            return false;
        }
        //vamos validar que las fechas entradas no esten dentro de otro periodo pago
        return true;
    }

    /**
     * funcion que obtiene el periodo de pago activo
     * @return int
     *
     * @deprecated Use PlanillasManager->getPeriodoPagoActivo() insteed
     */
    public function getPeriodoPagoActivo()
    {
        $periodo_activo = $this->em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')->findOneBy(array('activo' => true));
        if (!$periodo_activo) {
            return false;
        } else {
            return $periodo_activo;
        }
    }

    /**
     * funcion que dice si existe al menos un periodo de pago en base de datos
     * @return boolean
     *
     * @deprecated Use PlanillasManager->existePlanillaInPeriodo(\DateTime $fechaInicio, \DateTime $fechaFin) insteed
     */
    public function existePeriodPagoenBasedeDatos()
    {
        $planillas = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')->findAll();
        if (count($planillas) == 0)
            return true;
        else {
            foreach ($planillas as $planilla) {
                if ($planilla->getFechaInicio() == $this->fechaInicio && $planilla->getFechaFin() == $this->fechaFin) {
                    return array(false, $planilla->getFechaInicio()->format('d/m/Y') . '-' . $planilla->getFechaFin()->format('d/m/Y'));
                }

                if ($this->fechaInicio <= $planilla->getFechaFin()) {
                    return array(false, $planilla->getFechaInicio()->format('d/m/Y') . '-' . $planilla->getFechaFin()->format('d/m/Y'));
                }
            }

            return true;
        }
    }

    /**
     * funcion que obtiene el ultimo perido de pago efectuado
     *
     * @deprecated
     */
    public function getUltimaPlanilla()
    {
        $sql = 'SELECT c  FROM PlanillasCoreBundle:CPlanillas c order by c.fechaFin desc';

        $query = $this->em->createQuery($sql);
        $oPlanillas = $query->getArrayResult();
        if (count($oPlanillas) > 0) {
            $sSalida = $oPlanillas[0]['fechaInicio']->format('d-m-Y') . ' al ' . $oPlanillas[0]['fechaFin']->format('d-m-Y');

            return $sSalida;
        }

        return "No existen pagos anteriores";
    }

    /**
     * funcion que busca la cantidad de incapacidades de un empleado antes de un fecha dada
     * @param  type $idEmpleado
     * @return type
     */
    public function getTotalIncapacidadesAnteriores($idEmpleado) {
        //$sql = 'SELECT c  FROM PlanillasCoreBundle:CIncapacidades c where c.empleado=' . $idEmpleado;
        //$sql .= ' and c.fechaFin < \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
        //$query = $this->em->createQuery($sql);
        $oIncapacidades = $this->em->getRepository('PlanillasCoreBundle:CIncapacidades')
            ->findIncapacidadesAnterioresAPeriodo($idEmpleado, $this->fechaInicio);
        /**$dTotal = 0;
        if (count($oIncapacidades) > 0) {
            foreach ($oIncapacidades as $incapacidad) {
                $diff = date_diff($incapacidad->getfechaFin(), $incapacidad->getfechaInicio());
                $dTotal+=($diff->days == 0) ? 1 : $diff->days;
            }
        }**/
        return count($oIncapacidades);
    }


    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function setFechaInicio($fechainicio)
    {
        $this->fechaInicio = $fechainicio;

        return $this;
    }

    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function setFechaFin($fechafin)
    {
        $this->fechaFin = $fechafin;

        return $this;
    }

    /**
     * @param mixed $idplanilla
     */
    public function setIdplanilla($idplanilla)
    {
        $this->idplanilla = $idplanilla;
    }

    /**
     * @return mixed
     */
    public function getIdplanilla()
    {
        return $this->idplanilla;
    }




    /*
     * Zona de reportes
     */

//    public function reportePrePagoPDF() {
//
//        $periodo = $this->fechaInicio->format('Y-m-d') . 'al ' . $this->fechaInicio->format('Y-m-d');
//        $periodo = sprintf("Periodo: %s al %s ", $this->fechaInicio->format('d-m-Y'), $this->fechaFin->format('d-m-Y'));
//        // create new PDF document
//        $pdf = new PdfObject();
//        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetAuthor('Jose Mojena Alpizar');
//        $pdf->SetTitle('Reporte general');
//        $pdf->SetSubject('Periodo');
//
//        $pdf->SetHeaderData('', '', "Planilla Efectivo", $periodo, array(0, 64, 255), array(0, 64, 128));
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
//
//        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//        $pdf->setFontSubsetting(true);
//        $pdf->SetFont('dejavusans', '', 9, '', true);
//
//        $aComplementos = array();
//        $tableHeaders = array(
//            'Cédula',
//            'Empleado',
//            'Salario',
//            'Bonificaciones',
//            'Rebajos',
//            'Días Extras',
//            'Horas Extras',
//            'Ausencias',
//            'Incapacidades',
//            'Total');
//        $html = '<table border="0" cellspacing="5" cellpadding="5">
//             <tr>
//
//                <td style="width:150px;text-align: left">' . $tableHeaders[1] . '</td>
//                <td style="width:65px;text-align: left">' . $tableHeaders[2] . '
//                </td>
//                <td style="width:100px;text-align: left">' . $tableHeaders[3] . '
//                </td>
//                 <td style="width:85px;text-align: left">' . $tableHeaders[4] . '
//                </td>
//                <td style="text-align: left">' . $tableHeaders[5] . '
//                </td>
//                <td style="text-align: left">' . $tableHeaders[6] . '
//                </td>
//                <td style="text-align: left">' . $tableHeaders[7] . '
//                </td>
//                <td style="width:100px;text-align: left">' . $tableHeaders[8] . '</td>
//                <td style="width:70px;text-align: left">' . $tableHeaders[9] . '
//                </td></tr>';
//
//        $aData = $this->resultHtmlPlanillas();
//        $dSalarioTotalTodos = 0;
//
//        for ($i = 0; $i < count($aData['empleados']); $i++) {
//            $empleado = $aData['empleados'][$i];
//            $datos_economicos = $aData['empleados'][$i]['datos_economicos'];
//
//            $bonificaciones = $datos_economicos['bonificaciones'];
//            //print_r($bonificaciones['bonificaciones']);exit;
//            $deudas = $datos_economicos['deudas'];
//            $dSalarioTotalTodos += $empleado['datos_economicos']['salario_total_empleado'];
//            $html .= '<tr>
//
//                    <td style="text-align: left">' . $empleado['datos_personales']['nombre'] . ' ' . $empleado['datos_personales']['apellidos'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['salario_base'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['bonificaciones']['total'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['deudas']['total'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['dias_extra']['total'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['horas_extras']['total'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['dias_menos']['total'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['incapacidades']['total'] . '</td>
//                    <td style="text-align: left">¢' . $empleado['datos_economicos']['salario_total_empleado'] . '</td>';
//
//            $html .= '</tr>';
//
//            $componentesHtml = '
//                   <h3>Empleado: ' . $empleado['datos_personales']['nombre'] . ' ' . $empleado['datos_personales']['apellidos'] . '</h3>
//
//                   <table>
//
//                                <tr>
//
//                                    <td>Bonificaciones</td>
//                                    <td style="text-align: right">Monto</td>
//
//                                </tr>
//                                ';
//
//            if (count($bonificaciones) > 0 && array_key_exists('bonificaciones', $bonificaciones)) {
//                $temp = $bonificaciones['bonificaciones'][0];
//                foreach ($bonificaciones['bonificaciones'] as $b) {
//                    $componentesHtml .= '
//                                            <tr>
//                                                <td>
//                                                   ' . $b['descripcion'] . '
//                                                </td>
//                                                <td style="text-align: right">
//                                                   ' . $b['monto_total'] . '
//                                                </td>
//
//                                            </tr>';
//                    //echo $b['descripcion'];exit;
//                }
//                $componentesHtml .= '
//                                     <tr>
//                                     <td style="text-align: right">
//                                      <strong>Total</strong>
//                                     </td>
//                                     <td>' . $bonificaciones['total'] . '</td>
//                                     </tr>
//                                   ';
//                $componentesHtml .= '
//                            </table>';
//            }
//            //print_r($componentesHtml);exit;
//            $componentesHtml .= '
//
//                   <h4>Deudas</h4>
//                   <table class="table table-bordered">
//                                <thead>
//                                <tr>
//
//                                    <th>Deuda</th>
//                                    <th style="text-align: right">Monto</th>
//
//                                </tr>
//                                </thead>
//                                <tbody>';
//            if (count($deudas) && array_key_exists('deudas', $deudas)) {
//                $temp = $deudas['deudas'];
//                foreach ($temp as $b) {
//                    $componentesHtml . '
//                                            <tr>
//                                                <td>
//                                                   holaa
//                                                </td>
//                                                <td style="text-align: right">
//                                                   fdime mano
//                                                </td>
//
//                                            </tr>';
//                }
//                $componentesHtml .= '</tbody>
//                            </table>';
//            }
//            $aComplementos[] = $componentesHtml;
//        }
//        $html .= '<tr>
//
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                    <td></td>
//                    <td style="text-align: right"><strong>Total:</strong></td>
//                    <td>¢' . $dSalarioTotalTodos . '</td>';
//
//        $html .= '</tr>';
//        $html .= '</table>';
//
//        $pdf->AddPage('L');
//        $pdf->writeHTML($html, true, false, true, false, '');
//
//        /* $pdf->AddPage();
//          foreach ($aComplementos as $complemento) {
//
//          $pdf->writeHTML($complemento, true, false, true, false, '');
//          } */
//
//        $pdf->Output('planillaPago.pdf', 'FD');
//    }

    public function reportePrePagoPDF()
    {
        $prePayment = $this->container->get('payments.pdf.prepayment.manager');
        $data = $this->resultHtmlPlanillas();
        $prePayment->export($data);
        $prePayment->Output('pre-planillapago.pdf');
    }

    public function reportePrePagoExcel()
    {
        $prePayment = $this->container->get('payments.excel.prepayment.manager');
        $data = $this->resultHtmlPlanillas();
        $prePayment->export($data);
        $prePayment->Output('pre-planillapago.xlsx');
    }

    public function reportePagoExcel()
    {
        $payment = $this->container->get('payments.excel.payment.manager');
        $data = $this->resultHtmlPlanillas();
        $payment->export($data);
        $payment->Output('planillapago.xlsx');
    }

    public function reportePagoPDF()
    {
        $payment = $this->container->get('payments.pdf.payment.manager');
        $data = $this->resultHtmlPlanillas();
        $payment->export($data);
        $payment->Output('planillapago.pdf');
    }

    public function reportePagoBilletesPDF()
    {
        $payment = $this->container->get('payments.pdf.billsinsolated.manager');
        $data = $this->resultHtmlPlanillas();
        $payment->export($data);
        $payment->Output('planillapagomonedas.pdf');
    }

    public function reportePagoBilletesExcel()
    {
        $payment = $this->container->get('payments.excel.billsinsolated.manager');
        $data = $this->resultHtmlPlanillas();
        $payment->export($data);
        $payment->Output('planillapagomonedas.xlsx');
    }

}
