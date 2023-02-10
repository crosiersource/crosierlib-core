<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Base;

use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade 'Dia Útil'.
 *
 * @author Carlos Eduardo Pauluk
 */
#[ORM\Table(name: 'bse_diautil')]
#[ORM\Entity(repositoryClass: 'CrosierSource\CrosierLibCoreBundle\Repository\Base\DiaUtilRepository')]
class DiaUtil implements EntityId
{

    use EntityIdTrait;

    /**
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'dia', type: 'datetime', nullable: false)]
    public ?\DateTime $dia = null;

    /**
     * @var null|string
     */
    #[ORM\Column(name: 'descricao', type: 'string', nullable: true, length: 40)]
    public ?string $descricao = null;

    /**
     * @var null|bool
     */
    #[ORM\Column(name: 'comercial', type: 'boolean', nullable: false)]
    public ?bool $comercial = false;

    /**
     * @var null|bool
     */
    #[ORM\Column(name: 'financeiro', type: 'boolean', nullable: false)]
    public ?bool $financeiro = false;


}

