<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\Estabelecimento;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class EstabelecimentoEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Estabelecimento::class;
    }
}