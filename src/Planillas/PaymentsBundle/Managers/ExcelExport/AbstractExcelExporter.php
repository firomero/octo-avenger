<?php

namespace Planillas\PaymentsBundle\Managers\ExcelExport;

use Liuggio\ExcelBundle\Factory;

abstract class AbstractExcelExporter
{
    /**
     * @var  \Liuggio\ExcelBundle\Factory
     */
    protected $phpexcel;

    /**
     * @var  \PHPExcel
     */
    protected $excelObject;

    /**
     * @var  array
     */
    private $abc;

    /**
     * @var  array
     */
    protected $headers;

    /**
     * Columnas del excel
     *
     * @var  array
     */
    protected $columns;

    /**
     * Columnas con tipos de datos
     *
     * @var  array
     */
    protected $columnsTypes;

    /**
     * Constructor Abstracto para una nueva instancia del ExcelExporter
     *
     * @param Factory $phpexcel
     */
    public function __construct(Factory $phpexcel)
    {
        $this->phpexcel     = $phpexcel;
        $this->excelObject  = null;
        $this->abc = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $this->headers = array();
    }

    /**
     * Inicia los parámetros para un nuevo objeto excel
     *
     * @param null $filename
     * @param string $title
     * @param string $subject
     * @param string $description
     * @param array $keywords
     */
    public function init($filename = null, $title = '', $subject = '', $description = '', $keywords = array())
    {
        $this->excelObject  = $this->phpexcel->createPHPExcelObject($filename);
        $this->excelObject->getProperties()
            //->setCreator("Maarten Balliauw")
            //->setLastModifiedBy("Maarten Balliauw")
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description)
            ->setKeywords(implode(' ',$keywords))
            ->setCategory("Payments Excel Export");
        $this->setActiveSheet();
    }

    /**
     * Establece las cabeceras para el excel que se está trabajando
     *
     * @param  array $headers
     * @throws \Exception
     */
    public function setHeaders(array $headers)
    {
        if ($this->excelObject === null) {
            throw new \Exception('Debe primero inicializar un objeto PHPExcel para establecer los Headers.');
        }
        if (count($headers) == 0) {
            throw new \Exception('Tiene que entrar los parámetros para las cabeceras de las columnas.');
        }

        $count = 0;
        foreach ($headers as $key => $header) {
            $this->headers[$key] = $this->abc[$count];

            $this->excelObject->getActiveSheet()
                ->setCellValue($this->abc[$count].'1', $header);

            $this->excelObject->getActiveSheet()
                ->getColumnDimension($this->abc[$count])
                ->setAutoSize(true);

            $count++;
        }
    }

    /**
     * Establece la hoja activa para el excel en que se encuentra trabajando
     *
     * @param int $index
     */
    public function setActiveSheet($index = 0)
    {
        $this->excelObject->setActiveSheetIndex($index);
    }

    public abstract function export($data);

    /**
     * @param $filename
     * @param string $filetype
     */
    public function Output($filename, $filetype = 'Excel2007')
    {
        if ($filetype === 'Excel2007') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } else {
            header('Content-Type: application/vnd.ms-excel');
        }

        header(sprintf('Content-Disposition: attachment;filename="%s"',$filename));
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($this->excelObject, $filetype);
        $objWriter->save('php://output');
    }
} 