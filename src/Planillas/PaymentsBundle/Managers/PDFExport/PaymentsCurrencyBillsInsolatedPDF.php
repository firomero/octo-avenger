<?php
/**
 * Created by PhpStorm.
 * User: cinfante
 * Date: 02/05/14
 * Time: 06:41 AM
 */

namespace Planillas\PaymentsBundle\Managers\PDFExport;


class PaymentsCurrencyBillsInsolatedPDF extends AbstractPDFExporter
{
    /**
     * @var array
     */
    private $columnsWith;

    /**
     * @var array
     */
    private $columnsTypes;

    /**
     * @var  \Planillas\PaymentsBundle\Util\CurrencyBillsInsolation
     */
    private $billsInsolator;

    /**
     * Constructor
     */
    function __construct(\Planillas\PaymentsBundle\Util\CurrencyBillsInsolation $billsInsolation)
    {
        parent::__construct($pageOrientation='L');

        $this->billsInsolator = $billsInsolation;

        $this->headers = array(
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

        $this->columnsWith = array(
            'nombre_empleado'   => 50,
            '20000'             => 12,
            '10000'             => 12,
            '5000'              => 10,
            '2000'              => 10,
            '1000'              => 10,
            '500'               => 8,
            '100'               => 8,
            '50'                => 6,
            '25'                => 6,
            '10'                => 6,
            '5'                 => 6,
            'monto_total'       => 25
        );

        $this->columnsTypes = array(
            'nombre_empleado'   => 'string',
            '20000'             => 'int',
            '10000'             => 'int',
            '5000'              => 'int',
            '2000'              => 'int',
            '1000'              => 'int',
            '500'               => 'int',
            '100'               => 'int',
            '50'                => 'int',
            '25'                => 'int',
            '10'                => 'int',
            '5'                 => 'int',
            'monto_total'       => 'decimal'
        );

        $this->init(
            'Planilla de Pago Desglosado en Billetes', // title
            'Planilla de Pago Desglosado en Billetes', // subject
            array(
                'planilla',
                'pagos',
                'desglosado',
                'bills',
                'insolated'
            )
        );
    }

    public function export(array $data)
    {
        $fechaInicio    = $data['periodo']['inicio'];
        $fechaFin       = $data['periodo']['fin'];
        $description    = sprintf("Periodo: %s al %s ", $fechaInicio, $fechaFin);

        $this->setHeaderAndFooterData('Planilla de Pago Desglosado en Billetes', $description);

        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        //set headers
        $this->pdfObject->AddPage();

        $this->pdfObject->SetFillColor(0, 51, 102);
        $this->pdfObject->SetTextColor(255);
        $this->pdfObject->SetDrawColor(0, 0, 51);
        $this->pdfObject->SetLineWidth(0.3);
        $this->pdfObject->SetFont('', 'B');
        foreach ($this->headers as $key => $header) {
            $this->pdfObject->Cell($this->columnsWith[$key], 7, $header, 1, 0, 'C', 1);
        }

        $this->pdfObject->Ln();

        $this->pdfObject->SetFillColor(224, 235, 255);
        $this->pdfObject->SetTextColor(0);
        $this->pdfObject->SetFont('','',6);

        // iterando empleados
        $fill = 0;
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
            foreach ($this->headers as $key => $header) {
                $data = $rowData[$key];
                $align = 'L';
                if ($this->columnsTypes[$key] === 'int') {
                    $data = number_format($data, 0, '.',' ');
                    $align = 'R';
                } elseif ($this->columnsTypes[$key] === 'decimal') {
                    $data = number_format($data, 2, '.',' ');
                    $align = 'R';
                }
                $this->pdfObject->Cell($this->columnsWith[$key], 6, $data, 'LR', 0, $align, $fill);
            }
            $this->pdfObject->Ln();
            $fill=!$fill;
        }

        //montos totales
        foreach ($this->headers as $key => $header) {
            $data = $montosTotal[$key];
            $align = 'L';
            if ($this->columnsTypes[$key] === 'int') {
                $data = number_format($data, 0, '.',' ');
                $align = 'R';
            } elseif ($this->columnsTypes[$key] === 'decimal') {
                $data = number_format($data, 2, '.',' ');
                $align = 'R';
            }
            $this->pdfObject->Cell($this->columnsWith[$key], 6, $data, 'LR', 0, $align, $fill);
        }
        $this->pdfObject->Ln();

        $this->pdfObject->Cell(array_sum($this->columnsWith), 0, '', 'T');
    }
}
