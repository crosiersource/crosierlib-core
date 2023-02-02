<?php

namespace CrosierSource\CrosierLibCoreBundle\Service;

class NumberGeneratorService
{

    public function generate(int $max): int
    {
        return random_int(0, $max) + 2000000000;
    }

}
