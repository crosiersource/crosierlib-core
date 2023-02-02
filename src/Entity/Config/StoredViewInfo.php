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
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Config\StoredViewInfoRepository")
 * @ORM\Table(name="cfg_stored_viewinfo")
 *
 * @author Carlos Eduardo Pauluk
 */
class StoredViewInfo implements EntityId
{

    use EntityIdTrait;

    /**
     * @ORM\Column(name="view_name", type="string", length=200, nullable=true)
     * @NotUppercase()
     * @var null|string
     */
    public ?string $viewName = null;

    /**
     * @ORM\Column(name="view_info", type="string", length=15000, nullable=true)
     * @NotUppercase()
     * @var null|string
     */
    public ?string $viewInfo = null;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @var null|int
     */
    public ?int $user = null;

}