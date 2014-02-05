<?php

/**
 * Created by JetBrains PhpStorm.
 * User: jose
 * Date: 26/01/14
 * Time: 22:57
 * To change this template use File | Settings | File Templates.
 */

namespace Planillas\CoreBundle\Managers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use Planillas\CoreBundle\Entity\CPlanillas;
use Planillas\CoreBundle\Entity\CPlanillasEmpleado;
use Planillas\EntidadesBundle\Controller\EComponentesSalarialesController;
use Symfony\Component\HttpFoundation\Request;
use Planillas\CoreBundle\Helper\HelperDate;

define('cantDiasHabiles', 30);
define('cantHorasPorMes', 240);
define('cantHorasDiarias', 8);

class CPlanillasManagers {

    public $em;
    private $prequest;
    private $aEmpleadosSalario;
    private $aDatosSalario;
    private $dTotal;
    private $dBonificacionesTotal;
    private $dRebajosTotal;
    private $fechaInicio;
    private $fechaFin;

    public function __construct(EntityManager $em, Request $request) {

        $this->em = $em;
        $this->prequest = $request;
        $this->planilla = null;

        $this->initialize();
    }

    public function initialize() {
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
        $this->aEmpleadosSalario['id_planilla']=0;
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
    public function savePlanilla() {
        $periodoPago = $this->getPeriodoPagoActivo();

        if ($periodoPago === false) {

            return false;
        }

        $oPlanillas = new CPlanillas();
        $oPlanillas->setFechaInicio($this->fechaInicio);
        $oPlanillas->setFechaFin($this->fechaFin);
        $oPlanillas->setPeriodo($periodoPago);
        $oPlanillas->setCreatedAt(new \DateTime('now'));
        $this->em->persist($oPlanillas);
        $this->em->flush();
        try {
            $this->em->beginTransaction();
            $this->savePlanillasIntoDependencias($oPlanillas);
            //vamos a salvar los datos del salario base para ese periodo 
            // porque el salario puede cambiar entonces convendria haber guardado el
            //salario del empleado para esa planilla en ese momento
            
            $this->em->commit();
            return true;
        } catch (Exception $e) {
            $this->em->rollback();

            $this->em->remove($oPlanillas);
            $this->em->flush();

            return false;
        }
    }

    public function savePlanillasIntoDependencias(CPlanillas $object) {
        if ($object instanceof CPlanillas) {
            $this->planilla = $object;
            $oEmpleados = $this->findAllEmployee();
            if ($oEmpleados != null) {
                foreach ($oEmpleados as $oEmpleado) {

                    
                    $empleadoplanillas= new CPlanillasEmpleado();
                    //ojo hay qye hacer una funcion generica que tenga en cuenta el periodo 
                    //ahora lo hace siempre semanal
                    $empleadoplanillas->setSalarioPeriodo($this->getSalarioSemanalByEmpleado($oEmpleado->getId()));
                    $empleadoplanillas->setSalarioTotal(1000);//por ahora no funciona
                    $empleadoplanillas->setPlanilla($object);
                    $this->em->persist($empleadoplanillas);
                    $this->em->flush();
                    
                    
                    $aBonificionesTotalTemp = $this->findBonificacionesByEmpleado($oEmpleado->getId(), true); //update not show
                    $aDeudasTotalTemp = $this->findDeudasByEmpleado($oEmpleado->getId(), true);
                    $aDiasExtraTemp = $this->findDiasExtrasByEmpleado($oEmpleado->getId(), true);
                    $aHorasExtrasTemp = $this->findHorasExtrasByEmpleado($oEmpleado->getId(), true);
                    $aDiasMenosTemp = $this->findDiasMenosByEmpleado($oEmpleado->getId(), true);
                }
            }
        }
    }

    public function getPlanillas() {
        $oPlanillas = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')->findAll();
        if (count($oPlanillas) > 0) {
            return $oPlanillas;
        }
        else
            return array();
    }

    /*     * Bloque de funciones find */

    /**
     *  funcion que imprime el html
     *  esta funcion primero debe buscar si el periodo de pago existe para si existe entonces
     *  cargarlo y no buscar todo de nuevo
     */
    public function resultHtmlPlanillas() {
        if ($this->fechaInicio != null && $this->fechaFin != null) {
            $this->aEmpleadosSalario['periodo']['inicio'] = $this->fechaInicio->format('Y-m-d');
            $this->aEmpleadosSalario['periodo']['fin'] = $this->fechaFin->format('Y-m-d');
        } else {
            $this->aEmpleadosSalario['periodo']['inicio'] = "";
            $this->aEmpleadosSalario['periodo']['fin'] = "";
            return $this->aEmpleadosSalario;
        }

        
        $oEmpleados = $this->findAllEmployee();
        /**
         * primero vamos a buscar si ya esta creada para asi hacer busquedas nada mas por la llave de la planilla 
         */
        $oPlanilla = $this->findPeriodoPagoByFecha();
        if ($oPlanilla == null)
            $idPlanilla = null;
        else
            $idPlanilla = $oPlanilla->getId();

        $this->aEmpleadosSalario['id_planilla'] = ($idPlanilla == null) ? 0 : $idPlanilla;
        //echo $this->aEmpleadosSalario['id_planilla'];exit;
        if ($oEmpleados != null) {

            $i = 0;
            foreach ($oEmpleados as $oEmpleado) {
                //por cada uno vamos a buscar  todos sus datos economicos

                $aTemp['salario_total_empleado'] = 0.0;
                $salarioBaseTemp = 0.0;
                $salarioBaseTemp = $this->getSalarioSemanalByEmpleado($oEmpleado->getId());
                /* Componentes */
                $aBonificionesTotalTemp = $this->findBonificacionesByEmpleado($oEmpleado->getId(), false, $idPlanilla);
                
                $aDeudasTotalTemp = $this->findDeudasByEmpleado($oEmpleado->getId(), false, $idPlanilla);
                
                $aDiasExtraTemp = $this->findDiasExtrasByEmpleado($oEmpleado->getId(), false, $idPlanilla);
                
                $aHorasExtrasTemp = $this->findHorasExtrasByEmpleado($oEmpleado->getId(), false, $idPlanilla);
                $aDiasMenosTemp = $this->findDiasMenosByEmpleado($oEmpleado->getId(), false, $idPlanilla);
                
                /* Asignando salario y demas componentes que afectan  el salario */
                $aTemp['salario_total_empleado'] = $salarioBaseTemp;
                //$aTemp['salario_periodo_pago']=$salarioBaseTemp
                $aTemp['salario_base'] = $salarioBaseTemp;
                $aTemp['bonificaciones'] = $aBonificionesTotalTemp;
                $aTemp['deudas'] = $aDeudasTotalTemp;
                $aTemp['dias_extra'] = $aDiasExtraTemp;
                $aTemp['horas_extras'] = $aHorasExtrasTemp;
                $aTemp['dias_menos'] = $aDiasMenosTemp;

                /* Sumando totales de bonificaciones y deudas */
                $aTemp['salario_total_empleado'] += $aBonificionesTotalTemp['total'];
                $aTemp['salario_total_empleado'] -= $aDeudasTotalTemp['total'];
                $aTemp['salario_total_empleado'] += $aDiasExtraTemp['total'];
                $aTemp['salario_total_empleado'] += $aHorasExtrasTemp['total'];
                $aTemp['salario_total_empleado'] -= $aDiasMenosTemp['total'];


                $this->aEmpleadosSalario['empleados'] [$i]['datos_personales'] = array(
                    'nombre' => $oEmpleado->getNombre(),
                    'apellidos' => $oEmpleado->getPrimerApellido() . ' ' . $oEmpleado->getSegundoApellido(),
                    'cedula' => $oEmpleado->getCedula(),
                    'id' => $oEmpleado->getId()
                );
                $this->aEmpleadosSalario['empleados'][$i]['datos_economicos'] = $aTemp;

                $i++;
            }
        }

        return $this->aEmpleadosSalario;
    }

    /*
     * Consultas sql para obtener componentes salariales y empleados
     *
     * 
     */

    public function findAllEmployee() {
        $oEmpleados = $this->em->getRepository('PlanillasCoreBundle:CEmpleado')->findBy(array('activo' => true));
        if (count($oEmpleados) > 0) {
            return $oEmpleados;
        }
        else
            return null;
    }

    public function findBonificacionesByEmpleado($idEMpleado, $update = false, $oPlanilla = null) {


        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            $oBonificaciones = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('componente' => 1, 'planilla' => $oPlanilla, 'empleado' => $idEMpleado));
        }
        else
            $oBonificaciones = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('componente' => 1, 'empleado' => $idEMpleado));

        if (count($oBonificaciones) > 0) {
            foreach ($oBonificaciones as $oBonificacion) {

                //chequeamos la variable indicador que dice si esta iterando sobre bonificaciones
                //de un planilla creada

                if ($bIndicador === true) { //esto se puede mejorar pero no hay tiempo
                    $aSalida['bonificaciones'][] = array(
                        'id' => $oBonificacion->getId(),
                        'descripcion' => $oBonificacion->getDescripcion(),
                        'fecha_inicio' => $oBonificacion->getFechaVencimiento()->format('Y-m-d'),
                        'monto_total' => number_format($oBonificacion->getCantidad(), 2, '.', ''));

                    $aSalida['total'] += $oBonificacion->getCantidad();
                    continue;
                }


                if ($oBonificacion->getFechaVencimiento() < $this->fechaInicio) {
                    continue; //ya se vencio la bonificacion
                }

                if ($update === true) {
                    $oBonificacion->setPlanilla($this->planilla);
                    $this->em->persist($oBonificacion);
                    $this->em->flush();
                } else {

                    $aSalida['bonificaciones'][] = array(
                        'id' => $oBonificacion->getId(),
                        'descripcion' => $oBonificacion->getDescripcion(),
                        'fecha_inicio' => $oBonificacion->getFechaVencimiento()->format('Y-m-d'),
                        'monto_total' => number_format($oBonificacion->getCantidad(), 2, '.', ''));

                    $aSalida['total'] += $oBonificacion->getCantidad();
                }
            }
        }

        return $aSalida;
    }

    /*
     * @param type $idEmpleado
     */

    public function findDeudasByEmpleado($idEmpleado, $update = false, $oPlanilla = null) {
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            $aDeudasTemp = $this->em->getRepository('PlanillasEntidadesBundle:EComponentesSalariales')->findBy(array('componente' => 0, 'planilla' => $oPlanilla, 'empleado' => $idEmpleado));
        } else {
            $sql = 'SELECT c  FROM PlanillasEntidadesBundle:EComponentesSalariales c INNER Join c.empleado e WHERE e.activo=1 and e.id=' . $idEmpleado;
            $sql .= 'and c.pagado=false and c.componente=0'; //componente ==0 para que solo verifique las deudas

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fechaInicio >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fechaInicio <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }
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
                        'fecha_inicio' => $sDeuda->getFechaInicio()->format('Y-m-d') . '/' . $sDeuda->getFechaVencimiento()->format('Y-m-d'),
                        'componente' => $sDeuda->getComponente(),
                        'monto_total' => number_format($sDeuda->getMontoTotal(), 2, '.', ''));

                    $aSalida['total'] += $sDeuda->getMontoTotal();
                    continue;
                }

                if ($update === true) {
                    $sDeuda->setPlanilla($this->planilla);
                    $this->em->persist($sDeuda);
                    $this->em->flush();
                } else {
                    $aSalida['deudas'][] = array(
                        'id' => $sDeuda->getId(),
                        'fecha_inicio' => $sDeuda->getFechaInicio()->format('Y-m-d') . '/' . $sDeuda->getFechaVencimiento()->format('Y-m-d'),
                        'componente' => $sDeuda->getComponente(),
                        'monto_total' => number_format($sDeuda->getMontoTotal(), 2, '.', ''));

                    $aSalida['total'] += $sDeuda->getMontoTotal();
                }
            }
        }
        return $aSalida;
    }

    /**
     * funcion que calcula el importe de los dias extras trabajados dado un determinado empleado
     * @param type $idEmpleado
     * @return type array de tuplas
     */
    public function findDiasExtrasByEmpleado($idEmpleado, $update = false, $oPlanilla = null) {
        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            $bIndicador = true;
            $oDiasExtra = $this->em->getRepository('PlanillasCoreBundle:CDiasExtra')->findBy(array('empleado' => $idEmpleado, 'planilla' => $oPlanilla));
        } else {
            //$oDiasExtra = $this->em->getRepository('PlanillasCoreBundle:CDiasExtra')->findBy(array('empleado' => $idEMpleado));
            $sql = 'SELECT c  FROM PlanillasCoreBundle:CDiasExtra c INNER Join c.empleado e WHERE  e.id=' . $idEmpleado;

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fecha >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fecha <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }

            $query = $this->em->createQuery($sql);
            $oDiasExtra = $query->getResult();
        }

        if (count($oDiasExtra) > 0) {
            foreach ($oDiasExtra as $oDiaExtra) {

                /*if ($bIndicador===true) {
                    $aSalida['deudas'][] = array(
                        'id' => $sDeuda->getId(),
                        'fecha_inicio' => $sDeuda->getFechaInicio()->format('Y-m-d') . '/' . $sDeuda->getFechaVencimiento()->format('Y-m-d'),
                        'componente' => $sDeuda->getComponente(),
                        'monto_total' => number_format($sDeuda->getMontoTotal(), 2, '.', ''));

                    $aSalida['total'] += $sDeuda->getMontoTotal();
                    continue;
                }*/
                //echo "llego";exit;
                if ($update === true && $bIndicador===false) {
                    $oDiaExtra->setPlanilla($this->planilla);
                    $this->em->persist($oDiaExtra);
                    $this->em->flush();
                } else {
                    $dImporte = $this->getSalarioSemanalByEmpleado($idEmpleado);
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
     * @param type $idEmpleado
     * @return type
     */
    public function findDiasMenosByEmpleado($idEmpleado, $update = false, $oPlanilla = null) {
        $aSalida = array();
        $aSalida['total'] = 0;
        $bIndicador = false;
        if ($oPlanilla != null) {
            $oDiasMenos = $this->em->getRepository('PlanillasCoreBundle:CAusencias')->findBy(array('empleado' => $idEmpleado, 'planilla' => $oPlanilla));
        } else {
            $sql = 'SELECT c  FROM PlanillasCoreBundle:CAusencias c INNER Join c.empleado e WHERE  e.id=' . $idEmpleado;

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fechaInicio >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fechaFin <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }


            $query = $this->em->createQuery($sql);
            $oDiasMenos = $query->getResult();
        }

        if (count($oDiasMenos) > 0) {
            $dImporte = $this->getSalarioSemanalByEmpleado($idEmpleado);
            //echo $dImporte;exit;
            foreach ($oDiasMenos as $oDiasMeno) {

                if ($update === true && $bIndicador === false) {
                    $oDiasMeno->setPlanilla($this->planilla);
                    $this->em->persist($oDiasMeno);
                    $this->em->flush();
                } else {

                    if($oDiasMeno->getTipoAusencia()==2){
                        continue;
                    }
                    $fechaInicio = $oDiasMeno->getFechaInicio();
                    $fechaFin = $oDiasMeno->getFechaFin();
                    $diff = date_diff($fechaFin, $fechaInicio);

                    if ($diff->days > 0) {//solo en caso de que la cantidad de dias de diferencia sea 
                        //mayor que cero enonces multiplicamos por la cantidad de dias
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

    public function findHorasExtrasByEmpleado($idEmpleado, $update = false, $oPlanilla = null) {
        $aSalida = array();
        $aSalida['total'] = 0;
        if ($oPlanilla != null) {
            $oHorasExtras = $this->em->getRepository('PlanillasCoreBundle:CHorasExtras')->findBy(array('empleado' => $idEmpleado, 'planilla' => $oPlanilla));
        } else {
            $sql = 'SELECT c  FROM PlanillasCoreBundle:CHorasExtras c INNER Join c.empleado e WHERE  e.id=' . $idEmpleado;

            if ($this->fechaInicio !== null && $this->fechaFin !== null) {
                $sql .= ' and c.fechaHorasExtras >= \'' . date_format($this->fechaInicio, 'Y-m-d') . '\'';
                $sql .= ' and c.fechaHorasExtras <= \'' . date_format($this->fechaFin, 'Y-m-d') . '\'';
            }


            $query = $this->em->createQuery($sql);
            $oHorasExtras = $query->getResult();
        }

        if (count($oHorasExtras) > 0) {
            foreach ($oHorasExtras as $oHoraExtra) {

                if ($update === true && $oPlanilla==null) {
                    $oHoraExtra->setPlanilla($this->planilla);
                    $this->em->persist($oHoraExtra);
                    $this->em->flush();
                } else {
                    $dImporte = $this->getSalarioPorHorasByEmpleado($idEmpleado);
                    $aSalida['dias_extras'][] = array(
                        'id' => $oHoraExtra->getId(),
                        'fecha' => $oHoraExtra->getFechaHorasExtras()->format('Y-m-d'),
                        /* Ojo tipo ausencia es en realidad cantidad de horas me di cuenta hace poco */
                        'monto_total' => number_format($dImporte * $oHoraExtra->getcantidadHoras(), 2, '.', ''));

                    $aSalida['total'] += $dImporte * $oHoraExtra->getCantidadHoras();
                }
            }
        }
        return $aSalida;
    }

    public function findPeriodoPagoByFecha() {
        //$fechaInicio=date_format($this->fechaInicio,'Y-m-d');
        $entity = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')->findOneBy(array('fechaInicio' => $this->fechaInicio));
        //print_r($entity);exit;
        if (!$entity) {
            return null;
        }
        else
            return $entity;
    }

    /**
     * funcion que obtiene el salario bruto de un empleado
     * @param type $idEmpleado
     * @return int
     */
    public function getSalarioBaseByEmpleado($idEmpleado) {

        /* Falta aplicar el rebajo de seguro */
        //$salarioBase = 0;
        $oSalarioBase = $this->em->getRepository('PlanillasCoreBundle:CSalarioBase')->findOneBy(array('empleado' => $idEmpleado));
        if ($oSalarioBase) {
            return $oSalarioBase->getSalarioBase();
        }
        return 0;
    }

    public function getSalarioSemanalByEmpleado($idEmpleado) {
        //ojo que el horario no esta entrando en juego, ya veremos mas adelante
        $dSalariobase = $this->getSalarioBaseByEmpleado($idEmpleado);
        //si el periodo es semanal entonces hay que aplicar otra tasa       
        return ($dSalariobase / cantDiasHabiles) * 7;
    }

    public function getSalarioPorHorasByEmpleado($idEmpleado) {
        $dSalariobase = $this->getSalarioSemanalByEmpleado($idEmpleado);
        return $dSalariobase / cantHorasDiarias;
    }

    /**
     * funcion que valida si un periodo de pago es valido teniendo en cuenta solo los intervalos
     * de dias entre ellos
     * @return array|bool
     */
    public function validarPeriodoPago() {
        if ($this->fechaInicio == null || $this->fechaFin == null) {
            return false;
        }
        $periodo_activo = $this->em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')->findOneBy(array('activo' => true));
        if (!$periodo_activo) {
            return array(false, "No hay definido ningun periodo de pago");
        } else {
            if (strtolower($periodo_activo->getPeriodo()) == "semanal") { //semanal
                $diff = date_diff($this->fechaFin, $this->fechaInicio);
               
                if ($diff->days == 7) {
                    return true;
                } else {
                    return false;
                }
            } else if (strtolower($periodo_activo->getPeriodo()) == "quincenal") { //Qunicenal
                $cantidadDias = HelperDate::getCountDaysByMonth($this->fechaFin->format('m'), $this->fechaFin->format('Y'));
                $dia_inicio = $this->fechaInicio->format('d');
                $dia_final = $this->fechaFin->format('d');
                if ($dia_inicio == 16 && $dia_final == $cantidadDias) {
                    return true;
                } else if ($dia_inicio == 1 && $dia_final == 15) {
                    return true;
                } else {
                    return false;
                }
            } else { //mensual
                if ($dia_inicio == 1 && $dia_final == HelperDate::getCountDaysByMonth($this->fechaFin->format('m'), $this->fechaFin->format('Y'))) {
                    return true;
                }
                else
                    return false;
            }
        }
    }

    /**
     * funcion que obtiene el periodo de pago activo
     * @return int
     */
    public function getPeriodoPagoActivo() {
        $periodo_activo = $this->em->getRepository('PlanillasNomencladorBundle:NPeriodoPago')->findOneBy(array('activo' => true));
        if (!$periodo_activo) {
            return false;
        } else {
            return $periodo_activo;
        }
    }

    public function existePeriodPagoenBasedeDatos() {
        $planillas = $this->em->getRepository('PlanillasCoreBundle:CPlanillas')->findAll();
        if (count($planillas) == 0)
            return true;
        else {
            foreach ($planillas as $planilla) {
                if ($planilla->getFechaInicio() == $this->fechaInicio && $planilla->getFechaFin() == $this->fechaFin) {
                    //echo "holaaa";exit;
                    return false;
                }
                //en este caso es porque esta dentro de un intervalo final
                /* else if($this->fechaInicio>$planilla->getFechaInicio() && $this->fechaInicio<=$planillas->getFechaInicio())
                  {
                  return false;
                  }
                  else if($this->fechaInicio>$planillas->getFechaFin())
                  {
                  return true;
                  } */
                if ($this->fechaInicio < $planilla->getFechaFin()) {
                     
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    public function getFechaInicio() {
        return $this->fechaInicio;
    }

    public function setFechaInicio($fechainicio) {
        $this->fechaInicio = $fechainicio;
        return $this;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function setFechaFin($fechafin) {
        $this->fechaFin = $fechafin;
        return $this;
    }

}