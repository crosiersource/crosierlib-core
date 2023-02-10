<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Config;

use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;


/**
 * Entidade 'StoredViewInfo'.
 * Armazena informações sobre estado das páginas.
 *
 *
 * @author Carlos Eduardo Pauluk
 */
#[ORM\Table(name: 'cfg_stored_viewinfo')]
#[ORM\Entity(repositoryClass: 'CrosierSource\CrosierLibCoreBundle\Repository\Config\StoredViewInfoRepository')]
class StoredViewInfo implements EntityId
{

    use EntityIdTrait;

    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'view_name', type: 'string', length: 200, nullable: true)]
    public ?string $viewName = null;

    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'view_info', type: 'string', length: 15000, nullable: true)]
    public ?string $viewInfo = null;

    /**
     * @var null|int
     */
    #[ORM\Column(name: 'user_id', type: 'integer', nullable: false)]
    public ?int $user = null;

}