<?php

namespace Planillas\PaymentsBundle\Managers\ExcelExport;

use Liuggio\ExcelBundle\Factory;

class PaymentsCurrencyBillsInsolatedExcel extends AbstractExcelExporter
{
    /**
     * @var  \Planillas\PaymentsBundle\Util\CurrencyBillsInsolation
     */
    private $billsInsolator;

    function __construct(Factory $factory, \Planillas\PaymentsBundle\Util\CurrencyBillsInsolation $billsInsolation )
    {
        parent::__construct($factory);

        $this->billsInsolator = $billsInsolation;

        $this->columns = array(
            'nombre_empleado'   => 'Nombre',
            '20000'             => '20000',
            '10000'             => '10000',
            '5000'              => '5000',
            '2000'              => '2000',
            '1000'              => '1000',
            '500'               => '500',
            '100'               => '100',
            '50'                => '50',
            '25'                => '25',
            '10'                => '10',
            '5'                 => '5',
            'monto_total'       => 'Monto Total'
        );

        $this->columnsTypes = array(
            'nombre_empleado'   => 'string',
            '20000'             => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '10000'             => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '5000'              => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '2000'              => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '1000'              => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '500'               => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '100'               => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '50'                => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '25'                => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '10'                => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            '5'                 => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
            'monto_total'       => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER
        );

        $this->init(
            null, // filename
            'Planilla de Pago Desglosado en Billetes', // title
            'Planilla de Pago Desglosado en Billetes', // subject
            'Planilla de Pago Desglosado en Billetes', // description
            array(
                'Planilla', 'payments', 'pagos', 'insolated', 'bills'
            )
        );

        $this->setHeaders($this->columns);
    }

    public function export($data)
    {
        $row = 2;
        $totalGeneral = 0;
        $montosTotal = array(
            'nombre_empleado'   => 'Totales',
            '20000'             => 0,
            '10000'             => 0,
            '5000'              => 0,
            '2000'              => 0,
            '1000'              => 0,
            '500'               => 0,
            '100'               => 0,
            '50'                => 0,
            '25'                => 0,
            '10'                => 0,
            '5'                 => 0,
            'monto_total'       => 0
        );
        // iterando empleados
        foreach ($data['empleados'] as $empleado) {
            $datosEconomicos = $empleado['datos_economicos'];
            $total = $datosEconomicos['salario_total_empleado'];

            $bills = $this->billsInsolator->insolate($total);
            $datosPersonales = $empleado['datos_personales'];

            $rowData = array(
                'nombre_empleado'   => sprintf('%s %s',$datosPersonales['nombre'],$datosPersonales['apellidos']),
                '20000'             => $bills['20000'],
                '10000'             => $bills['10000'],
                '5000'              => $bills['5000'],
                '2000'              => $bills['2000'],
                '1000'              => $bills['1000'],
                '500'               => $bills['500'],
                '100'               => $bills['100'],
                '50'                => $bills['50'],
                '25'                => $bills['25'],
                '10'                => $bills['10'],
                '5'                 => $bills['5'],
                'monto_total'       => $total
            );

            $montosTotal = array(
                'nombre_empleado'   => 'Totales',
                '20000'             => $montosTotal['20000'] + $bills['20000'],
                '10000'             => $montosTotal['10000'] + $bills['10000'],
                '5000'              => $montosTotal['5000'] + $bills['5000'],
                '2000'              => $montosTotal['2000'] + $bills['2000'],
                '1000'              => $montosTotal['1000'] + $bills['1000'],
                '500'               => $montosTotal['500'] + $bills['500'],
                '100'               => $montosTotal['100'] + $bills['100'],
                '50'                => $montosTotal['50'] + $bills['50'],
                '25'                => $montosTotal['25'] + $bills['25'],
                '10'                => $montosTotal['10'] + $bills['10'],
                '5'                 => $montosTotal['5'] + $bills['5'],
                'monto_total'       => $montosTotal['monto_total'] + $total
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
        }

        //montos totales
        foreach ($this->columns as $column => $headerTitle) {
            $pCoordinate = $this->headers[$column].$row;

            $this->excelObject->getActiveSheet()
                ->setCellValue($pCoordinate, $montosTotal[$column]);

            // set cell format
            if($this->columnsTypes[$column] !== 'string') {
                $this->excelObject->getActiveSheet()
                    ->getStyle($pCoordinate)
                    ->getNumberFormat()
                    ->setFormatCode($this->columnsTypes[$column]);
            }
        }
    }
}
