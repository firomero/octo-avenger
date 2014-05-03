<?php

namespace Planillas\PaymentsBundle\Managers\ExcelExport;

use Liuggio\ExcelBundle\Factory;

class PaymentsExcel extends AbstractExcelExporter
{
    function __construct(Factory $factory)
    {
        parent::__construct($factory);

        $this->columns = array(
            'nombre_empleado'   => 'Nombre',
            'sucursal'          => 'Sucursal',
            'bonificaciones'    => 'Bonificaciones',
            'rebajos'           => 'Rebajos',
            'dias_extra'        => 'Días Extras',
            'horas_extras'      => 'Horas Extras',
            'dias_menos'        => 'Días Menos',
            'incapacidades'     => 'Incapacidades',
            'monto_total'       => 'Monto Total',
            'firma'             => 'Firma',
        );

        $this->columnsTypes = array(
            'nombre_empleado'   => 'string',
            'sucursal'          => 'string',
            'bonificaciones'    => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'rebajos'           => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'dias_extra'        => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'horas_extras'      => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'dias_menos'        => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'incapacidades'     => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'monto_total'       => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00,
            'firma'             => 'string',
        );

        $this->init(
            null, // filename
            'Planilla de Pagos', // title
            'Planilla de Pagos', // subject
            'Planilla de pagos con los datos generados para el periodo', // description
            array(
                'Planilla', 'payments', 'pagos'
            )
        );

        $this->setHeaders($this->columns);
    }

    public function export($data)
    {
        $row = 2;
        $totalGeneral = 0;
        // iterando empleados
        foreach ($data['empleados'] as $empleado) {
            $datosEconomicos = $empleado['datos_economicos'];
            $datosPersonales = $empleado['datos_personales'];

            $bonificaciones = $datosEconomicos['bonificaciones'];
            $deudas         = $datosEconomicos['deudas'];
            $diasExtra      = $datosEconomicos['dias_extra'];
            $horasExtras    = $datosEconomicos['horas_extras'];
            $diasMenos      = $datosEconomicos['dias_menos'];
            $incapacidades  = $datosEconomicos['incapacidades'];

            $rowData = array(
                'nombre_empleado'   => sprintf('%s %s',$datosPersonales['nombre'],$datosPersonales['apellidos']),
                'sucursal'          => isset($datosPersonales['sucursal']) ?
                        $datosPersonales['sucursal'] : '--no asignado--',
                'bonificaciones'    => $bonificaciones['total'],
                'rebajos'           => $deudas['total'],
                'dias_extra'        => $diasExtra['total'],
                'horas_extras'      => $horasExtras['total'],
                'dias_menos'        => $diasMenos['total'],
                'incapacidades'     => $incapacidades['total'],
                'monto_total'       => $datosEconomicos['salario_total_empleado'],
                'firma'             => '_______________',
            );

            // iterando columnas
            foreach ($this->columns as $column => $headerTitle) {
                $pCoordinate = $this->headers[$column].$row;

                $this->excelObject->getActiveSheet()
                    ->setCellValue($pCoordinate, $rowData[$column]);

                // set cell format
                if($this->columnsTypes[$column] !== 'string') {
                    $this->excelObject->getActiveSheet()
                        ->getStyle($pCoordinate)
                        ->getNumberFormat()
                        ->setFormatCode($this->columnsTypes[$column]);
                }
            }

            $row++;
            $totalGeneral += $datosEconomicos['salario_total_empleado'];
        }

//        $pCoordinate = $this->headers['monto_total'].$row;
//        $this->excelObject->getActiveSheet()
//            ->setCellValue($pCoordinate, $totalGeneral);
//        $this->excelObject->getActiveSheet()
//            ->getStyle($pCoordinate)
//            ->getNumberFormat()
//            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
    }
} 