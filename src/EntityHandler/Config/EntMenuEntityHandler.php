<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Config;

use CrosierSource\CrosierLibCoreBundle\Entity\Config\EntMenu;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Utils\StringUtils\StringUtils;

/**
 * Class EntMenuEntityHandler
 * @package CrosierSource\CrosierLibCoreBundle\EntityHandler\Config
 * @author Carlos Eduardo Pauluk
 */
class EntMenuEntityHandler extends EntityHandler
{

    public function beforeSave(/** @var EntMenu $entMenu */ $entMenu)
    {
        if (!$entMenu->ordem) {
            if ($entMenu->pai) {
                if ($entMenu->pai->filhos and $entMenu->pai->filhos->count() > 0) {
                    $ordem = $entMenu->pai->filhos->get($entMenu->pai->filhos->count() - 1)->ordem;
                    $entMenu->ordem = $ordem;
                } else {
                    $entMenu->ordem = $entMenu->pai->ordem;
                }
            } else {
                $entMenu->ordem = 9999999;
            }
        }

//        if ($entMenu->pai) {
//            $entMenu->setPaiUUID($entMenu->pai->getUUID());
//        }

        if (!$entMenu->paiUUID) {
            $entMenu->tipo = 'PAI';
        }
        if ($entMenu->tipo === 'PAI') {
            $entMenu->paiUUID = null;
        }
        if (!$entMenu->UUID) {
            $entMenu->UUID = StringUtils::guidv4();
        }

    }

    public function getEntityClass()
    {
        return EntMenu::class;
    }
}