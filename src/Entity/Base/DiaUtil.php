<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Base;

use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade 'Dia Útil'.
 *
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Base\DiaUtilRepository")
 * @ORM\Table(name="bse_diautil")
 * @author Carlos Eduardo Pauluk
 */
class DiaUtil implements EntityId
{

    use EntityIdTrait;

    /**
     * @ORM\Column(name="dia", type="datetime", nullable=false)
     * @var null|\DateTime
     */
    public ?\DateTime $dia = null;

    /**
     * @ORM\Column(name="descricao", type="string", nullable=true, length=40)
     * @var null|string
     */
    public ?string $descricao = null;

    /**
     * @ORM\Column(name="comercial", type="boolean", nullable=false)
     * @var null|bool
     */
    public ?bool $comercial = false;

    /**
     * @ORM\Column(name="financeiro", type="boolean", nullable=false)
     * @var null|bool
     */
    public ?bool $financeiro = false;


}

