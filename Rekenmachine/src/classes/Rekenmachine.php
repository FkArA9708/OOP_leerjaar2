<?php

namespace Rekenmachine\classes;

class Rekenmachine
{
    public function optellen($getal1, $getal2)
    {
        return $getal1 + $getal2;
    }

    public function subtract($a, $b)
    {
        return $a - $b;
    }

    public function multiply($a, $b)
    {
        return $a * $b;
    }

    public function divide($a, $b)
    {
        if ($b == 0) {
            throw new \Exception("Delen door nul is niet mogelijk!");
        }
        return $a / $b;
    }
}
