<?php
namespace Sample;

Class Math
{    
    static public function add (string $a, string $b)
    {
        $sum = '';

        do {
            $aLen = strlen($a);
            $bLen = strlen($b);

            if ($aLen < $bLen) {
                $a = '0' . $a;
            } else {
                $b = '0' . $b;
            }
        } while ($aLen != $bLen);

        $aLen = strlen($a);

        for ($i = 0; $i < $aLen; $i++) {
            $a1 = intval(substr($a, -1));
            $b1 = intval(substr($b, -1));
            $a = substr($a, 0, -1);
            $b = substr($b, 0, -1);
            $sum1 = $a1 + $b1 + $extra;
            $extra = intval($sum1 / 10);
            $sum1 = $sum1 % 10;
            $sum = $sum1 . $sum;
        }

        if ($extra) {
            $sum = $extra . $sum;
        }

        return $sum;
    }
}
