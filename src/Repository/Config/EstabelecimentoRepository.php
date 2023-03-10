<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\Estabelecimento;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * Repository para a entidade Estabelecimento.
 *
 * @author Carlos Eduardo Pauluk
 *
 */
class EstabelecimentoRepository extends FilterRepository
{
    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return Estabelecimento::class;
    }
}
