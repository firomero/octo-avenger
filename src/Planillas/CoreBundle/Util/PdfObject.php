<?php

namespace Planillas\CoreBundle\Util;

class PdfObject extends \TCPDF
{
    public function __construct($orientation='P', $unit='mm', $format='LETTER', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
    {
        parent::__construct($orientation,$unit,$format,$unicode,$encoding,$diskcache,$pdfa);
    }
}
