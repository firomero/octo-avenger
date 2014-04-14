<?php

namespace Planillas\PaymentsBundle\Managers;

use Doctrine\ORM\EntityManager;
use Planillas\CoreBundle\Entity\CIncapacidades;
use Planillas\CoreBundle\Form\Models\IncapacidadesModel;
use Symfony\Bridge\Monolog\Logger;

class IncapacidadesManager
{

    /**
     * @var  \Doctrine\ORM\EntityManager $em
     */
    private $em;

    /**
     * @var  \Symfony\Bridge\Monolog\Logger $logger
     */
    private $logger;

    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em       = $em;
        $this->logger   = $logger;
    }

    /**
     * Crea incapacidades dado el modelo de datos entrado por parámetros
     *
     * @param IncapacidadesModel $model
     * @return bool
     * @throws \Exception
     */
    public function createIncapacidad(IncapacidadesModel $model)
    {
        $incapacidad = new CIncapacidades();

        $incapacidad->setEmpleado($model->getEmpleado());
        $incapacidad->setMotivo($model->getMotivo());
        $incapacidad->setTipoIncapacidad($model->getTipoIncapacidad());

        if($model->getFechaInicio() && !$model->getFechaFin()) {
            return $this->saveOneInstance($incapacidad, $model->getFechaInicio());
        } elseif ($model->getFechaFin() &&
            $model->getFechaInicio()->getTimestamp() === $model->getFechaFin()->getTimestamp()) {
            return $this->saveOneInstance($incapacidad, $model->getFechaInicio());
        } elseif ($model->getFechaFin() &&
            $model->getFechaInicio()->getTimestamp() !== $model->getFechaFin()->getTimestamp()) {
            return $this->saveMultipleInstances($incapacidad, $model->getFechaInicio(), $model->getFechaFin());
        } else {
            throw new \Exception('El crear Incapacidad no se ajusta a ninguna de las opciones proporcionadas.'.
                'Debe proveer al menos la fecha inicial para la incapacidad.');
        }
    }

    /**
     * Persiste los datos de la incapacidad para la fecha entrada por parámetros
     *
     * @param CIncapacidades $incapacidad
     * @param \DateTime $fecha
     * @return bool
     */
    public function saveOneInstance(CIncapacidades $incapacidad, \DateTime $fecha)
    {
        $incapacidad->setFecha($fecha);
        try {
            $this->em->persist($incapacidad);
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            $this->logger->addCritical(sprintf('Ha ocurrido un error persistiendo Incapacidad. Detalles del error: %s'),
                $e->getMessage());

            return false;
        }
    }

    /**
     * Crea varias instancias de una misma incapacidad para cada día en el rango de fechas definido
     *
     * @param CIncapacidades $incapacidad
     * @param \DateTime $fecha_inicio
     * @param \DateTime $fecha_fin
     * @return bool
     */
    public function saveMultipleInstances(CIncapacidades $incapacidad, \DateTime $fecha_inicio, \DateTime $fecha_fin)
    {
        try {
            $flag = false;
            do {
                if($flag)
                    $fecha_inicio->modify('+1 day');
                else
                    $flag = !$flag;

                $fecha_ref = clone($fecha_inicio);

                $new_instance = new CIncapacidades();
                $new_instance->setEmpleado($incapacidad->getEmpleado());
                $new_instance->setMotivo($incapacidad->getMotivo());
                $new_instance->setTipoIncapacidad($incapacidad->getTipoIncapacidad());
                $new_instance->setFecha($fecha_ref);

                $this->em->persist($new_instance);

            } while($fecha_ref->getTimestamp() !== $fecha_fin->getTimestamp());

            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            $this->logger->addCritical(sprintf('Ha ocurrido un error persistiendo Incapacidad para el rango de fechas'.
                'del %s al %s. Detalles del error: %s'),
                $fecha_inicio->format('d/m/Y'),
                $fecha_fin->format('d/m/Y'),
                $e->getMessage());

            return false;
        }

    }

    public function calcularMontoPorIncapacidadINS(CIncapacidades $oIncapacidad, $aSalida, $dImporte, $dImporteT)
    {
        $fechaInicio = $oIncapacidad->getFechaInicio();
        $fechaFin = $oIncapacidad->getFechaFin();
        $diff = date_diff($fechaFin, $fechaInicio);
        if ($diff->days > 0) {
            $dImporte += $dImporteT * $diff->days;
        }
        $aSalida['incapacidades'][] = array(
            'id' => $oIncapacidad->getId(),
            'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
            'descripcion' => $oIncapacidad->getMotivo(),
            'fecha' => $oIncapacidad->getFecha()->format('d/m/Y'),
            'monto_total' => number_format($dImporte, 2, '.', ''));

        $aSalida['total'] += number_format($dImporte, 2, '.', '');

        return $aSalida;
    }

    public function calcularMontoPorIncapacidadCCSS(CIncapacidades $oIncapacidad, $aSalida, $dImporte, $dImporteT, $iPagados)
    {
        $month = $this->getFechaInicio()->format('m');
        $monthTemp = $oIncapacidad->getFechaInicio()->format('m');
        //if ($month == $monthTemp) { //estamos en el mismo mes
        //le rebajamos el dia
        $fechaInicio = $oIncapacidad->getFechaInicio();
        $fechaFin = $oIncapacidad->getFechaFin();
        $diff = date_diff($fechaFin, $fechaInicio);
        if ($diff->days < 0) {
        //    continue;
        }
        //$iCount += ($diff->days == 0) ? 1 : $diff->days;
        $iCountInc = ($diff->days == 0) ? 1 : $diff->days;

        /**
         * Siempre que la cantidad de dias total del periodo analizado sea menor que 2
         * debemos descontar medio dia
         */
        /*
        if ($iCount <= 3) {//caso en que vamos a descontar medio dia
            if ($iCountInc == 1) {
                $iPagados +=1;
                $dImporte = ($dImporteT / 2) * 1;
            } else if ($iCountInc == 2) {
                $iPagados += 2;
                $dImporte = ($dImporteT / 2) * 2;
            } else {
                $iPagados += 3;
                $dImporte = ($dImporteT / 2) * 3;
            }


            $aSalida['incapacidades'][] = array(
                'id' => $oIncapacidad->getId(),
                'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
                'descripcion' => $oIncapacidad->getMotivo(),
                'fecha' => $oIncapacidad->getFechaInicio()->format('Y-m-d') . '/' . $oIncapacidad->getFechaFin()->format('Y-m-d'),
                'monto_total' => number_format($dImporte, 2, '.', ''));

            $aSalida['total'] += number_format($dImporte, 2, '.', '');
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
                $dImporte += ($dImporteT / 2) * $resto;
                $dImporte +=$dImporteT * ($iCountInc - $resto);
            } else {
                $dImporte = $dImporteT * $iCountInc;
            }

            $aSalida['incapacidades'][] = array(
                'id' => $oIncapacidad->getId(),
                'incapacidad' => $oIncapacidad->getTipoIncapacidad(),
                'descripcion' => $oIncapacidad->getMotivo(),
                'fecha' => $oIncapacidad->getFechaInicio()->format('Y-m-d') . '/' . $oIncapacidad->getFechaFin()->format('Y-m-d'),
                'monto_total' => number_format($dImporte, 2, '.', ''));

            $aSalida['total'] += number_format($dImporte, 2, '.', '');
        }*/
    }

    public function calcularIncapacidades($idEmpleado, \DateTime $fechaInicio, $oIncapacidades, $aSalida)
    {
        $incapacidadesAnteriores = $this->em->getRepository('PlanillasCoreBundle:CIncapacidades')
            ->findIncapacidadesAnterioresAPeriodo($idEmpleado, $fechaInicio);

        $countAnteriores    = count($incapacidadesAnteriores);
        $pagados            = ($countAnteriores >= 3) ? 3 : $countAnteriores;

        foreach ($oIncapacidades as $oIncapacidad) {
            if ($oIncapacidad->getTipoIncapacidad() === 'incapacidad_ins') {
                //$this->calcularMontoPorIncapacidadINS();
            } elseif ($oIncapacidad->getTipoIncapacidad() === 'incapacidad_ccss') {
                //$this->calcularMontoPorIncapacidadCCSS();
            }
        }
    }
}