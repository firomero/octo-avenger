<?php

namespace Planillas\PaymentsBundle\Util;

class CurrencyBillsInsolation
{
    /**
     * @var array
     */
    private $currencyBills;

    /**
     * @var int
     */
    private $currencyBillsCount;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currencyBills = array(20000,10000,5000,2000,1000,500,100,50,25,10,5);
        $this->currencyBillsCount = 10;
    }

    /**
     * Return insolated bills in array
     *
     * @param $total Total amount to calculate
     * @param int $iterator
     * @return array
     */
    public function insolate($total, $iterator = 0, $array = array())
    {
        if($iterator > $this->currencyBillsCount)
            return $array;

        $div = $total / $this->currencyBills[$iterator];

        if(strpos($div.'','.') !== 0) {
            $div = (int)substr($div.'',0,strpos($div.'','.'));
        }

        $rest = $total % $this->currencyBills[$iterator];

        $array[$this->currencyBills[$iterator].''] = $div;

        return $this->insolate($rest, $iterator + 1, $array);
    }
}