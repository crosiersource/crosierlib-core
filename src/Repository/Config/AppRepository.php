<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\App;
use CrosierSource\CrosierLibCoreBundle\Repository\FilterRepository;

/**
 * Repository para a entidade App.
 *
 * @author Carlos Eduardo Pauluk
 *
 */
class AppRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return App::class;
    }

    public function findApps()
    {
        $dql = "SELECT a FROM App\Entity\Config\App a WHERE a.id != 1";
        $qry = $this->getEntityManager()->createQuery($dql);
        return $qry->getResult();
    }


}

