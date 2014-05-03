<?php

namespace Planillas\PaymentsBundle\Managers\PDFExport;

abstract class AbstractPDFExporter
{
    /**
     * @var  \TCPDF
     */
    protected $pdfObject;

    /**
     * @var  array
     */
    protected $headers;

    /**
     * Abstract Constructor
     *
     * @param string $pageOrientation
     * @param string $units
     * @param string $pageFormat
     */
    public function __construct($pageOrientation = 'P', $units = 'mm', $pageFormat='A4')
    {
        $this->pdfObject = new \TCPDF($pageOrientation, $units, $pageFormat, true, 'UTF-8', false, false);
    }

    /**
     * Init PDF Object
     *
     * @param string $title
     * @param string $subject
     * @param array $keywords
     */
    public function init($title = '', $subject = '', array $keywords)
    {
        $this->pdfObject->SetCreator(PDF_CREATOR);
        //$this->pdfObject->SetAuthor('Nicola Asuni');
        $this->pdfObject->SetTitle($title);
        $this->pdfObject->SetSubject($subject);
        $this->pdfObject->SetKeywords(implode(', ', $keywords));

        $this->pdfObject->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->pdfObject->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->pdfObject->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $this->pdfObject->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->pdfObject->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->pdfObject->SetFooterMargin(PDF_MARGIN_FOOTER);
        $this->pdfObject->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->pdfObject->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->pdfObject->setFontSubsetting(true);
        $this->pdfObject->SetFont('dejavusans', '', 9, '', true);
    }

    /**
     * Set header
     *
     * @param $title
     * @param $description
     */
    public function setHeaderAndFooterData($title, $description)
    {
        $this->pdfObject->SetHeaderData('', '', $title, $description, array(0, 51, 102), array(0, 51, 102));
        $this->pdfObject->setFooterData(array(0, 64, 0), array(0, 64, 128));
    }

    public abstract function export(array $data);

    /**
     * Outputs the pdf file
     *
     * @param $filename
     */
    public function Output($filename)
    {
        $this->pdfObject->Output($filename, 'FD');
    }


} 