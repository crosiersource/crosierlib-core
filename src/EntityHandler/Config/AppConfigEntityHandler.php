<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\AppConfig;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;

/**
 * Class AppConfigEntityHandler
 * @package App\EntityHandler\Config
 *
 * @author Carlos Eduardo Pauluk
 */
class AppConfigEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return AppConfig::class;
    }

    public function beforeSave(/** @var AppConfig $appConfig */ $appConfig)
    {
        if (strpos($appConfig->chave, 'json') !== FALSE) {
            $appConfig->isJson = true;
        }
    }


}