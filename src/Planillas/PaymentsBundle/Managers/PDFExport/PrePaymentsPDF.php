<?php

namespace Planillas\PaymentsBundle\Managers\PDFExport;


class PrePaymentsPDF extends AbstractPDFExporter
{
    /**
     * @var array
     */
    private $columnsWith;

    /**
     * @var array
     */
    private $columnsTypes;

    function __construct()
    {
        parent::__construct($pageOrientation='L');

        $this->headers = array(
            'nombre_empleado'   => 'Nombre',
            'sucursal'          => 'Sucursal',
            'bonificaciones'    => 'Bonificaciones',
            'rebajos'           => 'Rebajos',
            'dias_extra'        => 'Días Extras',
            'horas_extras'      => 'Horas Extras',
            'dias_menos'        => 'Días Menos',
            'incapacidades'     => 'Incapacidades',
            'monto_total'       => 'Monto Total',
            'tipo_pago'         => 'Tipo de Pago',
        );

        $this->columnsWith = array(
            'nombre_empleado'   => 40,
            'sucursal'          => 25,
            'bonificaciones'    => 30,
            'rebajos'           => 20,
            'dias_extra'        => 24,
            'horas_extras'      => 26,
            'dias_menos'        => 24,
            'incapacidades'     => 30,
            'monto_total'       => 25,
            'tipo_pago'         => 25,
        );

        $this->columnsTypes = array(
            'nombre_empleado'   => 'string',
            'sucursal'          => 'string',
            'bonificaciones'    => 'decimal',
            'rebajos'           => 'decimal',
            'dias_extra'        => 'decimal',
            'horas_extras'      => 'decimal',
            'dias_menos'        => 'decimal',
            'incapacidades'     => 'decimal',
            'monto_total'       => 'decimal',
            'tipo_pago'         => 'string',
        );

        $this->init(
            'Pre Planilla de Pago', // title
            'Pre-Planilla de pagos tipo \'borrador\' con datos sujetos a posibles cambios', // subject
            array(
                'Planilla', 'template', 'borrador', 'payments', 'pagos'
            )
        );
    }

    public function export(array $data)
    {
        $fechaInicio    = $data['periodo']['inicio'];
        $fechaFin       = $data['periodo']['fin'];
        $description    = sprintf("Periodo: %s al %s ", $fechaInicio, $fechaFin);

        $this->setHeaderAndFooterData('Pre-Planilla de Pago', $description);

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
                'tipo_pago'         => isset($datosPersonales['tipo_pago']) ?
                        $datosPersonales['tipo_pago'] : '--no asignado--',
            );

            // iterando columnas
            foreach ($this->headers as $key => $header) {
                $data = $rowData[$key];
                $align = 'L';
                if ($this->columnsTypes[$key] === 'decimal') {
                    $data = number_format($data, 2, '.',' ');
                    $align = 'R';
                }
                $this->pdfObject->Cell($this->columnsWith[$key], 6, $data, 'LR', 0, $align, $fill);
            }
            $this->pdfObject->Ln();
            $fill=!$fill;
        }
        $this->pdfObject->Cell(array_sum($this->columnsWith), 0, '', 'T');
    }
} 