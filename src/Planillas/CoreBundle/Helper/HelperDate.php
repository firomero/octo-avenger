<?php

namespace Planillas\CoreBundle\Helper;


/**
 * Helper calss.
 *
 */
class HelperDate
{

    /**
     * funcion que calcula la cantidad de dias que tiene un mes de un determinado anno
     * @param $mes
     * @param $anno
     * @return bool|string
     */

    public static function getCountDaysByMonth($mes, $anno)
    {
        try {
            $mktime = mktime(0, 0, 0, $mes, 1, $anno);
            $cant = date('t', $mktime);
            return $cant;
          } catch (Exception $e) {
            return $e->getMessage();
        }


    }

}
